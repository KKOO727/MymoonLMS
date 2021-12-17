<?php

namespace App\Http\Controllers;

use App\Models\Balance;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use Stripe;
use Illuminate\Support\Facades\Session;
use Stripe\PaymentIntent;
use Stripe\StripeClient;
use Symfony\Component\HttpKernel\Exception\HttpException;

class StripeController extends Controller
{
    /**
     * payment view
     */
    public function handleGet()
    {
        return view('web.default.view.stripe');
    }

    /**
     * handling payment with POST
     */
    public function handlePost(Request $request)
    {
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        Stripe\Charge::create([
            "amount" => 100 * 150,
            "currency" => "usd",
            "source" => $request->stripeToken,
            "description" => "Making test payment."
        ]);

        Session::flash('success', 'Payment has been successfully processed.');

        return back();
    }

    public function createPaymentIntent()
    {


        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        header('Content-Type: application/json');
        try {
            // retrieve JSON from POST body
            $json_str = file_get_contents('php://input');
            $json_obj = json_decode($json_str);

            $paymentIntent = PaymentIntent::create([
                'amount' => $this->calculateOrderAmount($json_obj->items),
                'currency' => 'usd',
            ]);

            $output = [
                'clientSecret' => $paymentIntent->client_secret,
            ];

            echo json_encode($output);
        } catch (Error $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    function calculateOrderAmount(array $items): int
    {
        // Replace this constant with a calculation of the order's amount
        // Calculate the order total on the server to prevent
        // customers from directly manipulating the amount on the client

        //echo $items[0]->amount;
        return $items[0]->amount * 100;
    }

    public function topUpView(Request $request)
    {
        $amount = $request->amount;
        $user = $user = auth()->user();
        return view(getTemplate() . '.user.balance.charge', ['user' => $user,'amount'=>$amount]);

    }

    public function topUpSuccess(Request $request)
    {

        $user = Auth::user();
        $data = array(
            'title' => 'TopUp',
            'description' => $request->intent,
            'type' => 'add',
            'account' => 'self',
            'price' => $request->amount,
            'status' => 'success',
            'user_id' => $user->id,
            'created_at' => time(),
        );
        $create_balance_log = Balance::create($data);
        $newBalance = intval($user->credit) + intval($request->amount);
        $UpdateUser = User::where('id', $user->id)->update(['credit' => $newBalance]);
        return 'ok';

    }

    public function createCheckoutSession(Request $request)
    {

        $user = $user = auth()->user();
        $processor=$request->processor;
        $amount=$request->price;
        $currency=$request->cur;
        $YOUR_DOMAIN = URL::to('/');

        $amount_to_usd=currency($amount,$currency,'USD');
        $amount_usd=str_replace('$','',$amount_to_usd);


        if($processor == 'paypal'){
            $clientId = env('PAYPAL_CLIENT_ID', '');
            $clientSecret = env('PAYPAL_SECRET', '');

            $token=$user->id.'-'.$amount.'-'.time();
            $key=urlencode(encrypt_decrypt('encrypt',$token));

            $environment = new ProductionEnvironment($clientId, $clientSecret);
            $client = new PayPalHttpClient($environment);
            $request = new OrdersCreateRequest();
            $request->prefer('return=representation');
            $request->body = [
                "intent" => "CAPTURE",
                "purchase_units" => [[
                    "reference_id" => $token,
                    "amount" => [
                        "value" => $amount,
                        "currency_code" => $currency
                    ]
                ]],
                "application_context" => [
                    "cancel_url" => $YOUR_DOMAIN . '/user/balance/log?m=error&sess='.$key,
                    "return_url" => $YOUR_DOMAIN . '/user/balance/log?m=success&sess='.$key,
                ]
            ];

            try {
                $response = $client->execute($request);
                $url=$response->result->links[1]->href;

            }catch (HttpException $ex) {
                print_r($ex->getMessage());
            }


        }else{
            $stripe = new StripeClient(env('STRIPE_SECRET'));

            $pp = $stripe->prices->create([
                'unit_amount' => $request->price * 100,
                'currency' => $currency,
                'product' => 'prod_K5h0RrSVHJThc7',
            ]);

            $key=urlencode(encrypt_decrypt('encrypt',$pp->id));
            $token=$pp->id;
            if($currency == 'EUR'){
                $methods=['giropay','card','sepa_debit','sofort'];
            }elseif($currency == 'GBP'){
                $methods=['card'];
            }elseif($currency == 'USD'){
                $methods=['card'];
            }



            $checkout_session = $stripe->checkout->sessions->create([
                'line_items' => [[
                    'price' => $pp->id,
                    'quantity' => 1,
                ]],
                'payment_method_types' => $methods,

                'customer_email' => $user->email,
                'mode' => 'payment',
                'success_url' => $YOUR_DOMAIN . '/user/balance/log?m=success&sess='.$key,
                'cancel_url' => $YOUR_DOMAIN . '/user/balance/log?m=error&sess='.$key,
            ]);

            $url=$checkout_session->url;
        }

        $data = array(
            'title' => 'TopUp',
            'token' => $token,
            'type' => 'deposit',
            'account' => 'self',
            'description'=>'Pending Transaction',
            'price' => $amount_usd,
            'status' => 'pending',
            'user_id' => $user->id,
            'created_at' => time(),
            'exporter_id' => $processor,
        );
        $create_balance_log = Balance::create($data);

        return redirect($url);
    }


    public function payOut(Request $request){
        $user=Auth::user();
        $user_credit = getUserbalance($user->id);
        $amount = floatval($request->amount);

        $currency=$request->cur;
        $amount_to_usd=currency($amount,$currency,'USD');
        $amount_usd=str_replace('$','',$amount_to_usd);

        if($amount_usd > $user_credit){

            notify(trans('You dont have sufficient balance'), 'danger');
            return redirect('user/balance?tab=payout');
        }else{
            $service_charge=($amount_usd*1.5)/100;
            Balance::create([
                'title' => 'Withdrawal Request',
                'type' => 'withdrawal',
                'description' => 'wdrq_'.$user->id . '_'.time(),
                'price' => $amount_usd-$service_charge,
                'status' => 'pending',
                'user_id' => $user->id,
                'exporter_id' => $request->processor,
                'exporter_details' => $request->details,
                'created_at' => time()
            ]);

            Balance::create([
                'title' => 'Service charge for withdrawal',
                'type' => 'withdrawal_charge',
                'description' => 'wdrq_'.$user->id . '_'.time(),
                'price' => $service_charge,
                'status' => 'pending',
                'user_id' => 0,
                'exporter_id' => $request->processor,
                'exporter_details' => $request->details,
                'created_at' => time()
            ]);
            notify(trans('Withdrawal request submitted Successfully'), 'danger');
            return redirect('user/balance?tab=log');
        }
    }

}
