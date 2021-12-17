<?php

namespace App\Http\Controllers\Api;

use Anand\LaravelPaytmWallet\Facades\PaytmWallet;
use App\Classes\VideoStream;
use App\Models\Article;
use App\Models\ArticleRate;
use App\Models\Balance;
use App\Models\Blog;
use App\Models\Certificate;
use App\Models\Channel;
use App\Models\Content;
use App\Models\ContentCategory;
use App\Models\ContentComment;
use App\Models\ContentPart;
use App\Models\ContentRate;
use App\Models\ContentSupport;
use App\Models\Discount;
use App\Models\Event;
use App\Models\Follower;
use App\Models\Login;
use App\Models\Quiz;
use App\Models\QuizResult;
use App\Models\Record;
use App\Models\Requests;
use App\Models\Sell;
use App\Models\SellRate;
use App\Models\Tickets;
use App\Models\TicketsMsg;
use App\Models\Transaction;
use App\Models\TransactionCharge;
use App\User;
use Gathuku\Mpesa\Facades\Mpesa;
use http\Url;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ContentVip;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use kcfinder\fastImage;
use PayPal\Api\Amount;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Unicodeveloper\Paystack\Facades\Paystack;
use Illuminate\Support\Facades\Response;
use App\Models\QuizzesQuestion;
use App\Models\QuizzesQuestionsAnswer;


class ApiController extends Controller
{

    public function __construct()
    {
        $paypal_conf = \Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential(
                $paypal_conf['client_id'],
                $paypal_conf['secret'])
        );
        $this->_api_context->setConfig($paypal_conf['settings']);
    }
    ## Private Function
    private function response($data){
        return ['status'=>'1','data'=>$data];
    }
    private function error($code = -1, $description){
        return ['status'=>-1,'code'=>$code,'error'=>$description];
    }
    private function userInfo($id){
        $user   = User::with('usermetas')->find($id);
        $metas  = arrayToList($user->usermetas,'option','value');
        $month  = strtotime(date('Y-m-01 00:00:00'));
        $day    = strtotime(date('Y-m-d 00:00:00'));
        if($user->token == null || $user->token == ''){
            $user->update(['token'=>Str::random(24)]);
            $user->refresh();
        }

        $couses         = Sell::where('buyer_id', $user->id)->where('mode','pay')->count();
        $contents       = Content::where('user_id', $user->id)->pluck('id')->toArray();

        $sell['today']  = Transaction::whereIn('content_id', $contents)->where('mode','deliver')->where('created_at','>',$day)->count();
        $sell['month']  = Transaction::whereIn('content_id', $contents)->where('mode','deliver')->where('created_at','>',$month)->count();
        $sell['total']  = Transaction::whereIn('content_id', $contents)->where('mode','deliver')->count();

        return [
            'id'        => $user->id,
            'username'  => $user->username,
            'name'      => $user->name,
            'email'     => $user->email,
            'phone'     => isset($metas['phone'])?$metas['phone']:'',
            'city'      => isset($metas['city'])?$metas['city']:'',
            'age'       => isset($metas['old'])?$metas['old']:'',
            'income'    => $user->income,
            'credit'    => $user->credit,
            'mode'      => $user->mode,
            'last_view' => '',
            'view'      => $user->view,
            'rate_point'=> $user->rate_point,
            'rate_count'=> $user->rate_count,
            'vendor'    => $user->vendor,
            'token'     => $user->token,
            'currency'  => currencySign(),
            'courses'   => $couses,
            'sell'      => $sell,
            'new_sales' => 0,
            'new_messages'  => 0,
            'comments'  => 0
        ];
    }
    private function checkUserToken(Request $request){
        $user = User::where('token', $request->token)->first();
        if(!$user)
            return false;

        return $this->userInfo($user->id);
    }
    private function stream($id){
        $part = ContentPart::with('content')->where('mode','publish')->find($id);
        $storagePath  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
        $file = $storagePath.'source/content-'.$part->content->id.'/video/part-'.$part->id.'.mp4';

        if(!file_exists($file))
            return 'no file exit';

        $stream = new VideoStream($file);
        $stream->start();
    }
    private function checkDiscount($code, $price = null){
        $Mode = false;

        if($price = null || $price == 0)
            return ['status'=>$Mode,'price'=>0];

        $Discount = Discount::where('code', $code)
            ->where('expire_at','>', time())
            ->where('mode','publish')
            ->first();

        if($Discount){
            $Mode = true;
            if($Discount->type == 'off'){
                $price = round($price * ((100 - $Discount->off)/100),2);
            }
            if($Discount->type == 'gift'){
                $price = round($price - $Discount->off,2);
            }
        }


        return ['status'=>$Mode,'price'=>$price];
    }
    private function productPrice($Product = null){
        if($Product == null)
            return 0;
        if(!isset($Product->metas))
            return 0;

        foreach ($Product->metas as $meta){
            if($meta['option'] == 'price'){
                $Price = pricePay($Product->id,$Product->category_id,$meta['value']);
                return intval($Price);
            }
        }

        return 0;
    }

    public function functionList(){
        echo '<ul>';
        echo '<li><a href="#">Content</a></li>';
        echo '<li><a href="#">index</a></li>';
        echo '</ul>';
    }

    ## Index Page ##
    public function functionIndex(Request $request){
        $result = [];
        $data   = [];
        ## Gateway
        $data['gateway'][] = 'income';
        if(get_option('gateway_paypal') == 1) $data['gateway'][] = 'paypal';
        if(get_option('gateway_paystack',0) == 1) $data['gateway'][] = 'paystack';
        if(get_option('gateway_paytm',0) == 1) $data['gateway'][] = 'paytm';
        if(get_option('gateway_payu',0) == 1) $data['gateway'][] = 'payu';
        ## Get Blog Posts
        $result['blogPosts']        = Blog::with('user.usermetas')->where('mode','publish')->limit(5)->orderBy('id','DESC')->get();## Get Blog Posts
        $result['articlePosts']     = Article::with('user.usermetas')->where('mode','publish')->limit(5)->orderBy('id','DESC')->get();
        $result['sell_content']     = Content::with('metas','user')->withCount('sells')->where('mode','publish')->limit(15)->orderBy('sells_count','DESC')->get();
        $result['new_content']      = Content::with('metas')->where('mode','publish')->limit(15)->orderBy('id','DESC')->get();
        $result['popular_content']  = Content::with('metas')->where('mode','publish')->limit(15)->orderBy('view','DESC')->get();
        $result['vip_content']      = ContentVip::with('content')->where('mode','publish')->where('first_date','<',time())->where('last_date','>',time())->limit(15)->get();
        $result['slider_container'] = ContentVip::with(['content'=>function($q){
            $q->with(['metas','user']);
        }])
            ->where('first_date','<',time())
            ->where('last_date','>',time())
            ->where('mode','publish')
            ->where('type','slide')
            ->get();

        $result['requests']         = Requests::with(['content','category'])->withCount(['fans'])->where('mode','<>','draft')->orderBy('id','DESC')->take(20)->get();

        $result['category']         = ContentCategory::withCount(['contents','childs'])->with(['childs'=>function($c){
            $c->withCount(['contents']);
        }])->where('parent_id','0')->get();

        $result['records']          = Record::with(['content','category','fans'])->withCount(['fans'])->where('mode','publish')->take(10)->get();

        foreach ($result['new_content'] as $newContent){
            $meta = arrayToList($newContent->metas,'option','value');
            $data['content']['new'][] = [
                'type'      => $newContent->type,
                'id'        => $newContent->id,
                'title'     => $newContent->title,
                'thumbnail' => $meta['thumbnail'],
                'price'     => isset($meta['price'])?$meta['price']:0,
                'currency'  => currencySign(),
                'duration'  => contentDuration($newContent->id)
            ];
        }
        foreach ($result['popular_content'] as $popularContent){
            $meta = arrayToList($popularContent->metas,'option','value');
            $data['content']['popular'][] = [
                'type'      => $popularContent->type,
                'id'        => $popularContent->id,
                'title'     => $popularContent->title,
                'thumbnail' => $meta['thumbnail'],
                'price'     => isset($meta['price'])?$meta['price']:0,
                'currency'  => currencySign(),
                'duration'  => contentDuration($popularContent->id)
            ];
        }
        foreach ($result['vip_content'] as $vipContent){
            $vipContent = $vipContent->content;
            $meta = arrayToList($vipContent->metas,'option','value');
            $data['content']['vip'][] = [
                'type'      => $vipContent->type,
                'id'        => $vipContent->id,
                'title'     => $vipContent->title,
                'thumbnail' => $meta['thumbnail'],
                'price'     => isset($meta['price'])?$meta['price']:0,
                'currency'  => currencySign(),
                'duration'  => contentDuration($vipContent->id)
            ];
        }
        foreach ($result['sell_content'] as $sellContent){
            $meta = arrayToList($sellContent->metas,'option','value');
            $data['content']['sell'][] = [
                'type'      => $sellContent->type,
                'id'        => $sellContent->id,
                'title'     => $sellContent->title,
                'thumbnail' => $meta['thumbnail'],
                'price'     => isset($meta['price'])?$meta['price']:0,
                'currency'  => currencySign(),
                'duration'  => contentDuration($sellContent->id)
            ];
        }
        foreach ($result['slider_container'] as $sliderContent){
            $sliderContent = $sliderContent->content;
            $meta = arrayToList($sliderContent->metas,'option','value');
            $data['content']['slider'][]    = checkUrl($meta['thumbnail']);
            $data['content']['slider_id'][] = $sliderContent->id;
        }
        foreach ($result['blogPosts'] as $post){
            $data['content']['news'][] = [
                'title'     => $post->title,
                'date'      => date('Y F d', $post->created_at),
                'url'       => url('/').'/blog/mobile/'.$post->id,
                'image'     => strpos($post->image,'http') !== false?$post->image:url('/').$post->image
            ];
        }
        foreach ($result['articlePosts'] as $article){
            $meta = arrayToList($article->user->usermetas,'option','value');
            $data['content']['article'][] = [
                'user'      => [
                    'id'        => $article->user->id,
                    'name'      => $article->user->name,
                    'avatar'    => isset($meta['avatar'])?url('/').$meta['avatar']:'',
                    'bio'       => isset($meta['short_biography'])?$meta['short_biography']:''
                ],
                'title'     => $article->title,
                'date'      => date('Y F d', $article->created_at),
                'url'       => url('/').'/blog/mobile/'.$article->id.'?type=article',
                'image'     => strpos($article->image,'http') !== false?$article->image:url('/').$article->image
            ];
        }
        foreach ($result['category'] as $cat){
            $count = 0;
            $childes = [];
            foreach ($cat->childs as $child){
                $count+= getCategoryCount($child->id);
                $childes[] = [
                    'id'        => $child->id,
                    'title'     => $child->title,
                    'icon'      => \url('/').$child->app_icon,
                    'parent_id' => $child->parent_id
                ];
            }
            $data['content']['category'][] = [
                'id'            => $cat->id,
                'icon'          => \url('/').$cat->app_icon,
                'count'         => $count,
                'title'         => $cat->title,
                'childs'        => $childes,
                'childsCount'   => $cat->childs_count
            ];
        }
        foreach ($result['requests'] as $req){
            if(isset($req->category->app_icon)) {
                $data['requests'][] = [
                    'id'            => $req->id,
                    'title'         => $req->title,
                    'description'   => $req->description,
                    'fans'          => $req->fans_count,
                    'image'         => isset($req->category->app_icon) ? checkUrl($req->category->app_icon) : ''
                ];
            }
        }
        foreach ($result['records'] as $record){
            $data['records'][] = [
                'id'            => $record->id,
                'title'         => $record->title,
                'description'   => $record->description,
                'image'         => checkUrl($record->image),
                'fans'          => $record->fans_count
            ];
        }

        ## User Data
        if(isset($request->Token)){
            $User = User::where('token', $request->Token)->first();
            if(isset($User)){
                $data['user'] = $this->userInfo($User->id);
            }
        }

        $data = stripTagsAll($data);
        return $this->response($data);
    }
    ## Content Section ##
    public function contents($last = 0){
        return Content::with('metas')
            ->where('mode','publish')
            ->where('id','>',$last)
            ->orderBy('id','DESC')
            ->take(20)
            ->get();
    }
    ## Product ##
    public function product($id,Request $request){
        $data = [];
        $User   = $this->checkUserToken($request);
        $content = Content::with(['metas','category','parts','rates','user.usermetas','comments.user','meetings','quizzes'=>function($q){$q->withCount('questions');}])->find($id);
        $meta    = arrayToList($content->metas, 'option','value');

        $MB = 0;
        foreach($content->parts as $part)
            $MB = $MB + $part['size'];

        ## Get PreCourse Content ##
        if(isset($meta['precourse']))
            $preCourseIDs = explode(',',rtrim($meta['precourse'],','));
        else
            $preCourseIDs = [];
        $preCousreContent = Content::with(['metas'])->where('mode','publish')->whereIn('id',$preCourseIDs)->get();
        $preRequisites    = [];
        foreach ($preCousreContent as $pcc){
            $meta = arrayToList($pcc->metas, 'option','value');
            $preRequisites[] = [
                'id'        => $pcc->id,
                'title'     => $pcc->title,
                'thumbnail' => $meta['thumbnail'],
                'price'     => isset($meta['price'])?$meta['price']:0,
                'currency'  => currencySign(),
                'duration'  => isset($meta['duration'])?convertToHoursMins($meta['duration']):0
            ];
        }

        ## Get Related Content ##
        $related        = [];
        $relatedCat     = $content->category_id;
        $relatedContent = Content::with(['metas'])->where('category_id',$relatedCat)->where('id','<>',$content->id)->where('mode','publish')->limit(3)->inRandomOrder()->get();
        foreach ($relatedContent as $rc){
            $meta = arrayToList($rc->metas, 'option','value');
            $related[] = [
                'id'        => $rc->id,
                'title'     => $rc->title,
                'thumbnail' => $meta['thumbnail'],
                'price'     => isset($meta['price'])?$meta['price']:0,
                'currency'  => currencySign(),
                'duration'  => contentDuration($rc->id)
            ];
        }

        ## Comments
        $comments = [];
        foreach ($content->comments as $comment){
            if($comment->mode == 'publish') {
                $comments[] = [
                    'id' => $comment->id,
                    'user' => isset($comment->user->name) ? $comment->user->name : '',
                    'comment' => $comment->comment
                ];
            }
        }

        ## Support
        $support  = [];
        if(isset($User)){
            $supports = ContentSupport::where(function($w) use ($User){
                $w->where('user_id', $User['id'])->orWhere('sender_id', $User['id']);
            })->where('content_id',$id)->where('mode','publish')->get();
            foreach ($supports as $s){
                if($s->sender_id == $s->user_id){
                    $type = 'user';
                }else{
                    $type = 'supporter';
                }
                $support[] = [
                    'name'      => $s->name,
                    'comment'   => $s->comment,
                    'type'      => $type,
                    'date'      => date('Y F,d H:i', $s->created_at)
                ];
            }
        }

        ## User
        $user['avatar'] = '';
        $user['id']     = $content->user_id;
        $user['name']   = isset($content->user->name)?$content->user->name:'';
        foreach ($content->user->usermetas as $um){
            if($um->option == 'avatar'){
                $user['avatar'] = checkUrl($um->value);
            }
        }

        ## Parts
        $parts = [];
        foreach ($content->parts as $part){
            $parts[] = [
                'id'        => $part->id,
                'title'     => $part->title,
                'duration'  => convertToHoursMins($part->duration),
                'free'      => $part->free,
            ];
        }

        ## Check User Purchase
        $buy = 0;
        if($User){
            $Purchase = Sell::where('buyer_id', $User['id'])->where('mode','pay')->where('content_id', $id)->count();
            if($Purchase > 0){
                $buy = 1;
            }
            if($content->user_id == $User['id']){
                $buy = 1;
            }
        }

        ## Meetings
        $Meetings = [];
        foreach ($content->meetings as $meeting){
            $Meetings[] = [
                'title'     => $meeting->title,
                'date'      => $meeting->date.' '.$meeting->time,
                'mode'      => $meeting->mode,
                'type'      => $meeting->type,
                'password'  => $meeting->password,
                'join'      => $meeting->join_url,
                'duration'  => $meeting->duration
            ];
        }

        ## Quizzes
        $Quizzes = [];
        foreach ($content->quizzes as $quizz){
            if($quizz->status == 'active') {
                $Quizzes[] = [
                    'id'        => $quizz->id,
                    'title'     => $quizz->name,
                    'time'      => $quizz->time,
                    'pass_mark' => $quizz->pass_mark,
                    'questions' => $quizz->questions_count,
                    'result'    => null
                ];
            }
        }
        # Quiz Result
        if($User){
            foreach ($Quizzes as $index=>$Quiz){
                $Quizzes[$index]['result'] = QuizResult::where('quiz_id', $Quiz['id'])->where('student_id', $User['id'])->orderBy('id','DESC')->select(['user_grade','status'])->first();
            }
        }

        ##Certificate
        $Certificate = [];
        if($User && count($Quizzes) > 0){
            $quizIds = [];
            foreach ($Quizzes as $Q){
                $quizIds[] = $Q['id'];
            }
            $Cer = Certificate::with(['quiz'])->where('student_id', $User['id'])->whereIn('quiz_id', $quizIds)->get();
            foreach ($Cer as $C){
                $img = explode('bin',$C->file);
                $Certificate[] = [
                    'title'         => $C->quiz->name,
                    'grade'         => $C->user_grade,
                    'certificate'   => \url('/').'/bin'.$img[1]
                ];
            }
        }


        $data    = [
            'id'                    => $content->id,
            'type'                  => $content->type,
            'user'                  => $user,
            'comments'              => $comments,
            'content'               => $content->content,
            'title'                 => $content->title,
            'category'              => ['id'=>$content->category->id,'title'=>$content->category->title],
            'sample'                => isset($meta['video'])?$meta['video']:'',
            'duration'              => contentDuration($id),
            'document'              => isset($meta['document'])?$meta['document']:'',
            'price'                 => isset($meta['price'])?$meta['price']:0,
            'post_price'            => isset($meta['post_price'])?$meta['post_price']:0,
            'cover'                 => isset($meta['cover'])?checkUrl($meta['cover']):'',
            'thumbnail'             => isset($meta['thumbnail'])?checkUrl($meta['thumbnail']):'',
            'currency'              => currencySign(),
            'support'               => $content->support,
            'supports'              => $support,
            'free'                  => $content->price == 1?0:1,
            'size'                  => isset($MB)?$MB:0,
            'date'                  => date('Y-m-d', $content->created_at),
            'parts'                 => $parts,
            'meetings'              => $Meetings,
            'quizzes'               => $Quizzes,
            'certificates'          => $Certificate,
            'quizzes_enable'        => count($Quizzes) >0?true:false,
            'certificates_enable'   => count($Certificate) >0?true:false,
            'prerequisites'         => $preRequisites,
            'related'               => $related,
            'buy'                   => $buy,
            'rates'                 => is_numeric($content->rates->avg('rate'))?$content->rates->avg('rate'):0
        ];

        return $this->response(['product'=>$data]);
    }
    public function productPay(Request $request){
        $User = $this->checkUserToken($request);
        if(!$User)
            return redirect('/')->with('msg',trans('main.user_not_found'));
        $user = $User;

        if($request->gateway == 'paypal'){
            $content = Content::with('metas')->where('mode','publish')->find($request->id);
            if(!$content)
                abort(404);

            if($content->private == 1)
                $site_income = get_option('site_income_private');
            else
                $site_income = get_option('site_income');

            $meta = arrayToList($content->metas,'option','value');

            if($request->mode == 'download')
                $Amount = $meta['price'];
            elseif ($request->mode == 'post')
                $Amount = $meta['post_price'];

            $Description = trans('admin.item_purchased').$content->title.trans('admin.by').$User['name']; // Required
            $Amount_pay = pricePay($content->id,$content->category_id,$Amount)['price'];


            $payer = new Payer();
            $payer->setPaymentMethod('paypal');
            $item_1 = new Item();
            $item_1->setName($content->title)
                ->setCurrency('USD')
                ->setQuantity(1)
                ->setPrice($Amount);
            $item_list = new ItemList();
            $item_list->setItems(array($item_1));
            $amount = new Amount();
            $amount->setCurrency('USD')
                ->setTotal($Amount);
            $transaction = new \PayPal\Api\Transaction();
            $transaction->setAmount($amount)
                ->setItemList($item_list)
                ->setDescription('Purchase Product');
            $redirect_urls = new RedirectUrls();
            $redirect_urls->setReturnUrl(url('/').'/bank/paypal/status')
                ->setCancelUrl(url('/').'/bank/paypal/cancel/'.$request->id);
            $payment = new Payment();
            $payment->setIntent('Sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirect_urls)
                ->setTransactions(array($transaction));
            try {
                $payment->create($this->_api_context);
            } catch (\PayPal\Exception\PPConnectionException $ex) {
                if (\Config::get('app.debug')) {
                    \Session::put('error', 'Connection timeout');
                    return Redirect::route('paywithpaypal');
                } else {
                    \Session::put('error', 'Some error occur, sorry for inconvenient');
                    return Redirect::route('paywithpaypal');
                }
            }
            foreach ($payment->getLinks() as $link) {
                if ($link->getRel() == 'approval_url') {
                    $redirect_url = $link->getHref();
                    break;
                }
            }
            $ids = $payment->getId();
            \Session::put('paypal_payment_id', $ids);
            Transaction::insert([
                'buyer_id'      => $User['id'],
                'user_id'       => $content->user_id,
                'content_id'    => $content->id,
                'price'         => $Amount_pay,
                'price_content' => $Amount,
                'mode'          => 'pending',
                'created_at'     => time(),
                'bank'          => 'paypal',
                'income'        => $Amount_pay - (($site_income/100)*$Amount_pay),
                'authority'     => $ids,
                'type'          => $request->mode
            ]);
            /** add payment ID to session **/
            if (isset($redirect_url)) {
                /** redirect to paypal **/
                return Redirect::away($redirect_url);
            }
            \Session::put('error', 'Unknown error occurred');
            return Redirect::route('paywithpaypal');
        }
        if($request->gateway == 'paytm'){
            $content = Content::with('metas')->where('mode','publish')->find($request->id);
            if(!$content)
                abort(404);

            if($content->private == 1)
                $site_income = get_option('site_income_private');
            else
                $site_income = get_option('site_income');

            $meta = arrayToList($content->metas,'option','value');

            if($request->mode == 'download')
                $Amount = $meta['price'];
            elseif ($request->mode == 'post')
                $Amount = $meta['post_price'];

            $Description = trans('admin.item_purchased').$content->title.trans('admin.by').$user['name']; // Required
            $Amount_pay = pricePay($content->id,$content->category_id,$Amount)['price'];

            $Transaction = Transaction::create([
                'buyer_id'      => $user['id'],
                'user_id'       => $content->user_id,
                'content_id'    => $content->id,
                'price'         => $Amount_pay,
                'price_content' => $Amount,
                'mode'          => 'pending',
                'created_at'     => time(),
                'bank'          => 'paytm',
                'authority'     => 0,
                'income'        => $Amount_pay - (($site_income/100)*$Amount_pay),
                'type'          => $request->mode
            ]);

            $payment = PaytmWallet::with('receive');
            $payment->prepare([
                'order'         => $Transaction->id,
                'user'          => $user['id'],
                'email'         => $user['email'],
                'mobile_number' => '00187654321',
                'amount'        => $Transaction->price,
                'callback_url'  => url('/').'/bank/paytm/status/'.$content->id
            ]);
            return $payment->receive();
        }
        if($request->gateway == 'payu'){
            $content = Content::with('metas')->where('mode','publish')->find($request->id);
            if(!$content)
                abort(404);

            if($content->private == 1)
                $site_income = get_option('site_income_private');
            else
                $site_income = get_option('site_income');

            $meta = arrayToList($content->metas,'option','value');

            if($request->mode == 'download')
                $Amount = $meta['price'];
            elseif ($request->mode == 'post')
                $Amount = $meta['post_price'];

            $Description = trans('admin.item_purchased').$content->title.trans('admin.by').$user['name']; // Required
            $Amount_pay = pricePay($content->id,$content->category_id,$Amount)['price'];
            $Transaction = Transaction::create([
                'buyer_id'      => $user['id'],
                'user_id'       => $content->user_id,
                'content_id'    => $content->id,
                'price'         => $Amount_pay,
                'price_content' => $Amount,
                'mode'          => 'pending',
                'created_at'     => time(),
                'bank'          => 'paytm',
                'authority'     => 0,
                'income'        => $Amount_pay - (($site_income/100)*$Amount_pay),
                'type'          => $request->mode
            ]);
        }
        if($request->gateway == 'paystack'){
            $content = Content::with('metas')->where('mode','publish')->find($request->id);
            if(!$content)
                abort(404);

            if($content->private == 1)
                $site_income = get_option('site_income_private');
            else
                $site_income = get_option('site_income');

            $meta = arrayToList($content->metas,'option','value');

            if($request->mode == 'download')
                $Amount = $meta['price'];
            elseif ($request->mode == 'post')
                $Amount = $meta['post_price'];

            $Description = trans('admin.item_purchased').$content->title.trans('admin.by').$user['name']; // Required
            $Amount_pay = pricePay($content->id,$content->category_id,$Amount)['price'];

            $Transaction = Transaction::create([
                'buyer_id'      => $user['id'],
                'user_id'       => $content->user_id,
                'content_id'    => $content->id,
                'price'         => $Amount_pay,
                'price_content' => $Amount,
                'mode'          => 'pending',
                'created_at'     => time(),
                'bank'          => 'paystack',
                'authority'     => 0,
                'income'        => $Amount_pay - (($site_income / 100) * $Amount_pay),
                'type'          => $request->mode
            ]);
            $payStack    = new \Unicodeveloper\Paystack\Paystack();
            $payStack->getAuthorizationResponse([
                "amount"        => $Amount_pay,
                "reference"     => Paystack::genTranxRef(),
                "email"         => $user['email'],
                "callback_url"  => url('/').'/api/v1/product/verify?gateway=paystack&content_id='.$content->id,
                'metadata'      => json_encode(['transaction'=>$Transaction->id])
            ]);
            return redirect($payStack->url);
        }
        if($request->gateway == 'credit'){
            $content = Content::with('metas')->where('mode','publish')->find($request->id);
            if(!$content)
                abort(404);

            $seller = User::with('category')->find($content->user_id);

            if($content->private == 1)
                $site_income = get_option('site_income_private');
            else
                $site_income = get_option('site_income');

            $meta = arrayToList($content->metas,'option','value');
            if($request->mode == 'download')
                $Amount = $meta['price'];
            elseif ($request->mode == 'post')
                $Amount = $meta['post_price'];

            $Amount_pay = pricePay($content->id,$content->category_id,$Amount)['price'];
            if($user['credit']-$Amount_pay<0) {
                return redirect('/api/v1/product/verify?gateway=credit&mode=failed&type=nocredit');
            }else{
                $transaction = Transaction::create([
                    'buyer_id'      =>$user['id'],
                    'user_id'       =>$content->user_id,
                    'content_id'    =>$content->id,
                    'price'         =>$Amount_pay,
                    'price_content' =>$Amount,
                    'mode'          =>'deliver',
                    'created_at'     =>time(),
                    'bank'          =>'credit',
                    'authority'     =>'000',
                    'income'        =>$Amount_pay - (($site_income/100)*$Amount_pay),
                    'type'          =>$request->mode
                ]);
                Sell::insert([
                    'user_id'       => $content->user_id,
                    'buyer_id'      => $user['id'],
                    'content_id'    => $content->id,
                    'type'          => $request->mode,
                    'created_at'     => time(),
                    'mode'          => 'pay',
                    'transaction_id'=> $transaction->id
                ]);

                $seller->update(['income'=>$seller->income+((100-$site_income)/100)*$Amount_pay]);
                $buyer = User::find($user['id']);
                $buyer->update(['credit'=>$user['credit']-$Amount_pay]);

                Balance::create([
                    'title'=>trans('admin.item_purchased').$content->title,
                    'description'=>trans('admin.item_purchased_desc'),
                    'type'=>'minus',
                    'price'=>$Amount_pay,
                    'mode'=>'auto',
                    'user_id'=>$buyer->id,
                    'exporter_id'=>0,
                    'created_at'=>time()
                ]);
                Balance::create([
                    'title'=>trans('admin.item_sold').$content->title,
                    'description'=>trans('admin.item_sold_desc'),
                    'type'=>'add',
                    'price'=>((100-$site_income)/100)*$Amount_pay,
                    'mode'=>'auto',
                    'user_id'=>$seller->id,
                    'exporter_id'=>0,
                    'created_at'=>time()
                ]);
                Balance::create([
                    'title'=>trans('admin.item_profit').$content->title,
                    'description'=>trans('admin.item_profit_desc').$buyer->username,
                    'type'=>'add',
                    'price'=>($site_income/100)*$Amount_pay,
                    'mode'=>'auto',
                    'user_id'=>0,
                    'exporter_id'=>0,
                    'created_at'=>time()
                ]);

                ## Notification Center
                $product = Content::find($transaction->content_id);
                sendNotification(0,['[c.title]'=>$product->title],get_option('notification_template_buy_new'),'user',$buyer->id);
                return redirect('/api/v1/product/verify?gateway=credit&mode=successfully')->with('msg',trans('admin.item_purchased_success'));
            }
            return redirect('/api/v1/product/verify?gateway=credit&mode=failed');
        }
    }
    public function productVerify(Request $request){

        if(isset($request->mode) && $request->mode == 'failed'){
            return view(getTemplate() . '.app.verify');
        }
        if(isset($request->mode) && $request->mode == 'successfully'){
            return view(getTemplate() . '.app.verify');
        }

        if($request->gateway == 'paypal'){
            $payment_id = \Session::get('paypal_payment_id');
            \Session::forget('paypal_payment_id');
            if (empty($request->PayerID) || empty($request->token)) {
                \Session::put('error', 'Payment failed');
                return Redirect::route('/');
            }
            $payment = Payment::get($payment_id, $this->_api_context);
            $execution = new PaymentExecution();
            $execution->setPayerId($request->PayerID);
            $result = $payment->execute($execution, $this->_api_context);
            $transaction = Transaction::where('mode','pending')->where('authority',$payment_id)->first();
            if ($result->getState() == 'approved') {
                $product = Content::find($transaction->content_id);
                $userUpdate = User::with('category')->find($transaction->user_id);
                if($product->private == 1)
                    $site_income = get_option('site_income_private')-$userUpdate->category->off;
                else
                    $site_income = get_option('site_income')-$userUpdate->category->off;

                if(empty($transaction))
                    \redirect('/product/'.$transaction->content_id);

                $Amount = $transaction->price;

                Sell::insert([
                    'user_id'       => $transaction->user_id,
                    'buyer_id'      => $transaction->buyer_id,
                    'content_id'    => $transaction->content_id,
                    'type'          => $transaction->type,
                    'created_at'     => time(),
                    'mode'          => 'pay',
                    'transaction_id'=> $transaction->id,
                    'remain_time'   => $transaction->remain_time
                ]);

                $userUpdate->update(['income'=>$userUpdate->income+((100-$site_income)/100)*$Amount]);
                Transaction::find($transaction->id)->update(['mode'=>'deliver','income'=>((100-$site_income)/100)*$Amount]);

                ## Notification Center
                sendNotification(0,['[c.title]'=>$product->title],get_option('notification_template_buy_new'),'user',$transaction->buyer_id);

                return redirect('/product/'.$transaction->content_id);

            }
            return redirect('/product/'.$transaction->content_id);
        }
        if($request->gateway == 'paytm'){
            $transaction = PaytmWallet::with('receive');
            $Transaction = Transaction::find($transaction->getOrderId());
            $response = $transaction->response();

            if($transaction->isSuccessful()){
                $product = Content::find($Transaction->content_id);
                $userUpdate = User::with('category')->find($Transaction->user_id);
                if($product->private == 1)
                    $site_income = get_option('site_income_private')-$userUpdate->category->off;
                else
                    $site_income = get_option('site_income')-$userUpdate->category->off;

                if(empty($transaction))
                    \redirect('/product/'.$Transaction->content_id);

                $Amount = $transaction->price;

                Sell::insert([
                    'user_id'       => $Transaction->user_id,
                    'buyer_id'      => $Transaction->buyer_id,
                    'content_id'    => $Transaction->content_id,
                    'type'          => $Transaction->type,
                    'created_at'     => time(),
                    'mode'          => 'pay',
                    'transaction_id'=> $Transaction->id,
                    'remiain_time'  => $Transaction->remain_time
                ]);

                $userUpdate->update(['income'=>$userUpdate->income+((100-$site_income)/100)*$Amount]);
                Transaction::find($Transaction->id)->update(['mode'=>'deliver','income'=>((100-$site_income)/100)*$Amount]);

                ## Notification Center
                sendNotification(0,['[c.title]'=>$product->title],get_option('notification_template_buy_new'),'user',$Transaction->buyer_id);

                return redirect('/product/'.$Transaction->content_id);

            }else if($transaction->isFailed()){
                return \redirect('/product/'.$Transaction->content_id)->with('msg',trans('admin.payment_failed'));
            }else if($transaction->isOpen()){
                //Transaction Open/Processing
            }
        }
        if($request->gateway == 'payu'){

        }
        if($request->gateway == 'paystack'){
            $payment = Paystack::getPaymentData();
            if(isset($payment['status']) && $payment['status'] == true){
                $Transaction = Transaction::find($payment['data']['metadata']['transaction']);
                $product = Content::find($Transaction->content_id);
                $userUpdate = User::with('category')->find($Transaction->user_id);
                if($product->private == 1)
                    $site_income = get_option('site_income_private')-$userUpdate->category->off;
                else
                    $site_income = get_option('site_income')-$userUpdate->category->off;

                if(empty($transaction))
                    return redirect('/api/v1/product/verify?mode=failed');

                $Amount = $Transaction->price;

                Sell::insert([
                    'user_id'       => $Transaction->user_id,
                    'buyer_id'      => $Transaction->buyer_id,
                    'content_id'    => $Transaction->content_id,
                    'type'          => $Transaction->type,
                    'created_at'     => time(),
                    'mode'          => 'pay',
                    'transaction_id'=> $Transaction->id,
                    'remain_time'   => $Transaction->remain_time
                ]);

                $userUpdate->update(['income'=>$userUpdate->income+((100-$site_income)/100)*$Amount]);
                Transaction::find($Transaction->id)->update(['mode'=>'deliver','income'=>((100-$site_income)/100)*$Amount]);

                ## Notification Center
                sendNotification(0,['[c.title]'=>$product->title],get_option('notification_template_buy_new'),'user',$Transaction->buyer_id);

                return redirect('/api/v1/product/verify?mode=successfully');
            }else{
                return redirect('/api/v1/product/verify?mode=failed');
            }
        }
        if($request->gateway == 'credit'){
            return view(getTemplate() . '.app.verify');
        }
    }
    public function productPart(Request $request){
        $id     = $request->id;
        $Part   = ContentPart::find($id);
        if(!$Part){
            return $this->error(-1,trans('admin.content_not_found'));
        }
        if($Part->free == 1){
            return $this->stream($id);
        }else{
            $User = $this->checkUserToken($request);
            if(!$User)
                return 'no user';

            if($Part->free == 0 && $User['id'] != $Part->content->user_id) {
                $sell = Sell::where('buyer_id', $User['id'])->where('content_id', $Part->content->id)->count();
                if ($sell == 0) {
                    return 'access denied!';
                }else{
                    return $this->stream($id);
                }
            }
        }

        return $this->error(-1,trans('main.not_purchased_item'));
    }
    public function productComment(Request $request){
        $data = [];
        $User = $this->checkUserToken($request);
        if(!$User)
            return $this->error(-1, trans('main.user_not_found'));

        $duplicate = ContentComment::where('content_id', $request->id)->where('user_id', $User['id'])->where('mode','draft')->count();
        if($duplicate > 0)
            return $this->error(-1,trans('duplicate draft review'));

        ContentComment::create([
            'comment'   => $request->comment,
            'user_id'   => $User['id'],
            'created_at' => time(),
            'name'      => $User['name'],
            'content_id'=> $request->id,
            'parent'    => 0,
            'mode'      => 'draft'
        ]);

        return $this->response($data);
    }
    public function productSearch(Request $request){
        $q = $request->q;
        $currency   = currencySign();
        $data       = [];
        $contents   = Content::with('metas')->where('mode','publish')->where('title','LIKE','%'.$q.'%')->orderBy('id','DESC')->take(20)->get();
        foreach ($contents as $content){
            $meta = arrayToList($content->metas,'option','value');
            $data[] = [
                'id'        => $content->id,
                'title'     => $content->title,
                'thumbnail' => checkUrl($meta['thumbnail']),
                'price'     => isset($meta['price'])?$meta['price']:0,
                'currency'  => $currency,
                'duration'  => isset($meta['duration'])?convertToHoursMins($meta['duration']):0
            ];
        }

        return $this->response($data);
    }
    public function productDiscount(Request $request){
        $productId  = $request->id;
        $code       = $request->code;

        $Product    = Content::with('metas')->find($productId);
        if(!$Product){
            return $this->error(-1, trans('main.not_found'));
        }

        $Price      = $this->productPrice($Product);
        $Discount   = $this->checkDiscount($code, $Price);

        return [
            'status'    => $Discount['status'],
            'price'     => $Price,
            'pay'       => $Discount['price'],
            'discount'  => ($Price > 0 && $Discount['price'] != false)?($Price - $Discount['price']):0,
            'currency'  => currencySign()
        ];
    }
    public function productDownload(Request $request){
        $User = $this->checkUserToken($request);
        if(!$User)
            return redirect('/')->with('msg',trans('main.user_not_found'));
        $user = $User;

        $part = ContentPart::with('content')->where('mode','publish')->find($request->part);
        if(!$part)
            abort(404);

        if($part->free == 0) {
            $sell = Sell::where('buyer_id', $user['id'])->where('content_id', $part->content->id)->count();
            if ($sell == 0)
                abort(404);
        }

        if($part->content->download == 0)
            abort(404);

        $storagePath  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
        $file = 'source/content-'.$part->content->id.'/video/part-'.$part->id.'.mp4';

        if(file_exists($storagePath.$file))
            return Response::download($storagePath.$file);
        else
            return abort(404);
    }
    public function productSupport(Request $request){
        $User = $this->checkUserToken($request);
        if(!$User)
            return redirect('/')->with('msg',trans('main.user_not_found'));
        $user = $User;

        $buy = Sell::where('buyer_id',$user['id'])->where('content_id',$request->content_id)->first();
        if(isset($buy)){
            ContentSupport::create([
                'comment'       => $request->comment,
                'user_id'       => $user['id'],
                'created_at'     => time(),
                'name'          => $user['name'],
                'content_id'    => $request->content_id,
                'mode'          => 'draft',
                'supporter_id'  => $buy->user_id,
                'sender_id'     => $user['id']
            ]);
            return $this->response(['description'=>trans('admin.support_success')]);
        }else {
            return $this->error(-1,trans('admin.support_failed'));
        }
    }
    ## Category ##
    public function category(Request $request){
        $currency   = currencySign();
        $data       = [];
        $contents   = Content::with(['metas','discount'])->where('mode','publish')->where('category_id',$request->id)->get();
        foreach ($contents as $content){
            $meta = arrayToList($content->metas,'option','value');
            $data[] = [
                'id'        => $content->id,
                'title'     => $content->title,
                'thumbnail' => checkUrl($meta['thumbnail']),
                'price'     => isset($meta['price'])?$meta['price']:0,
                'currency'  => $currency,
                'type'      => $content->type,
                'duration'  => contentDuration($content->id),
                'support'   => $content->support,
                'post'      => $content->post,
                'discount'  => $content->discount
            ];
        }

        ## Filters
        # Price
        if(isset($request->data[0]['price'])){
            if($request->data[0]['price'] == 1){
                foreach ($data as $index=>$f){
                    if($f['price'] != 0){
                        unset($data[$index]);
                    }
                }
            }
            if($request->data[0]['price'] == 0){
                foreach ($data as $index=>$f){
                    if($f['price'] == 0){
                        unset($data[$index]);
                    }
                }
            }
        }
        # Type
        if(isset($request->data[0]['type'])){
            if($request->data[0]['type'] == 0){
                foreach ($data as $index=>$f){
                    if($f['type'] != 'single'){
                        unset($data[$index]);
                    }
                }
            }
            if($request->data[0]['type'] == 1){
                foreach ($data as $index=>$f){
                    if($f['type'] != 'webinar' && $f['type'] != 'webinar+course'){
                        unset($data[$index]);
                    }
                }
            }
            if($request->data[0]['type'] == 2){
                foreach ($data as $index=>$f){
                    if($f['type'] != 'course'){
                        unset($data[$index]);
                    }
                }
            }
        }
        # Support
        if(isset($request->data[0]['support'])){
            if($request->data[0]['support'] == true){
                foreach ($data as $index=>$f){
                    if($f['support'] != 1){
                        unset($data[$index]);
                    }
                }
            }
        }
        # Physical
        if(isset($request->data[0]['physical'])){
            if($request->data[0]['physical'] == true){
                foreach ($data as $index=>$f){
                    if($f['post'] != 1){
                        unset($data[$index]);
                    }
                }
            }
        }
        # Discount
        if(isset($request->data[0]['discount'])){
            if($request->data[0]['discount'] == true){
                foreach ($data as $index=>$f) {
                    if ($f['discount'] == null) {
                        unset($data[$index]);
                    }
                }
            }
        }

        return $this->response(array_values($data));
    }

    ## User Section
    public function userRegister(Request $request){
        $duplicateUser = User::where('username',$request->username)->first();
        $duplicateEmail = User::where('email',$request->email)->first();

        if($duplicateUser)
        {
            return $this->error(-1,trans('main.user_exists'));
        }
        if($duplicateEmail)
        {
            return $this->error(-1,trans('main.user_exists'));
        }
        if($request->password != $request->re_password)
        {
            return $this->error('-1',trans('main.pass_confirmation_same'));
        }

        $newUser = [
            'name'          => $request->name,
            'username'      => $request->username,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'created_at'     => time(),
            'admin'         => 0,
            'mode'          => get_option('user_register_mode','active'),
            'category_id'   => get_option('user_default_category',0),
            'token'         => Str::random(24)
        ];
        $newUser = User::create($newUser);

        ## Send Suitable Email For New User ##
        if(get_option('user_register_mode') == 'deactive')
            sendMail(['template' => get_option('user_register_active_email'), 'recipient' => [$newUser->email]]);
        else
            sendMail(['template'=>get_option('user_register_wellcome_email'),'recipient'=>[$newUser->email]]);

        if(get_option('user_register_mode') == 'active')
            return $this->response(['description'=>trans('main.thanks_reg')]);
        else
            return $this->response(['description'=>trans('main.active_account_alert')]);
    }
    public function userRemember(Request $request){
        $str = Str::random(16);
        $update = User::where('email',$request->email)->update(['token'=>$str]);
        if($update) {
            sendMail(['template'=>get_option('user_register_reset_email'),'recipient'=>[$request->email]]);
            return $this->response(['description'=> trans('main.pass_change_link')]);
        }
        else {
            return $this->error('-1', trans('main.user_not_found'));
        }
    }
    public function userLogin(Request $request){
        $username = $request->username;
        $password = $request->password;

        $User = User::where(function ($w) use($username){
            $w->where('username',$username)->orWhere('email',$username);
        })->where('admin','0')->first();

        if($User && Hash::check($password,$User->password)){

            if($User->mode != 'active') {
                if (userMeta($User->id, 'blockDate', time()) < time()) {
                    $User->update(['mode'=>'active']);
                } else {
                    $jBlockDate = date('d F Y', userMeta($User->id, 'blockDate', time()));
                    return $this->error(-1, trans('main.access_denied') . $jBlockDate );
                }
            }


            Login::create([
                'user_id'       => $User->id,
                'created_at_sh' => time(),
                'updated_at_sh' => time()
            ]);
            Event::create([
                'user_id'   => $User->id,
                'type'      => 'Login Page',
                'ip'        => $request->ip()
            ]);

            //$User->update(['last_view'=>time()]);
            return $this->response(['user'=>$this->userInfo($User->id)]);

        }else{
            return $this->error(-2,trans('main.incorrect_login'));
        }
    }
    public function userInformation(Request $request){
        $User = $this->checkUserToken($request);
        if($User){
            return $this->response(['user'=>$User]);
        }

        return $this->error(-1,trans('main.user_login'));
    }
    public function userSetting(Request $request){
        $User = $this->checkUserToken($request);
        if(!$User)
            return $this->error(-1, trans('main.user_not_found'));

        if(isset($request->password) && ($request->password != $request->re_password))
            return $this->error(-1, trans('main.pass_confirmation_same'));

        if(isset($request->password) && ($request->password == $request->re_password))
            User::find($User['id'])->update(['password'=>encrypt($request->password)]);

        if(isset($request->name) && $request->name != '')
            User::find($User['id'])->update(['name'=>$request->name]);
        if(isset($request->phone) && $request->phone != '')
            setUserMeta($User['id'],'phone', $request->phone);
        if(isset($request->city) && $request->city != '')
            setUserMeta($User['id'],'city', $request->city);
        if(isset($request->age) && $request->age != '')
            setUserMeta($User['id'],'old', $request->age);

        return $this->response(['user'=> $this->userInfo($User['id'])]);
    }
    # Profile
    public function userProfile(Request $request)
    {
        $currency = currencySign();
        $user = $this->checkUserToken($request);
        $id = $request->id;
        $data = [];
        $profile = User::with('usermetas')->find($id);

        if (empty($profile))
            return redirect('/')->with('msg',trans('main.user_not_found'));

        $data['id']         = $profile->id;
        $data['username']   = $profile->username;
        $data['name']       = $profile->name;
        $data['bio']        = '';
        $data['avatar']     = '';
        foreach ($profile->usermetas as $umeta){
            if($umeta->option == 'biography')
                $data['bio'] = $umeta->value;
            if($umeta->option == 'avatar')
                $data['avatar'] = checkUrl($umeta->value);
        }

        $videos             = Content::with('metas')->where('user_id', $id)->where('mode', 'publish')->get();
        $data['follower']   = Follower::where('user_id', $id)->count();
        $articles           = Article::where('user_id', $id)->where('mode', 'publish')->orderBy('id', 'DESC')->get();
        $rates = getRate($profile);
        if ($user) {
            $data['follow'] = Follower::where('follower', $id)->where('user_id', $user['id'])->count();
        } else {
            $data['follow'] = 0;
        }

        $data['duration'] = 0;
        $data['courses']  = count($videos);
        foreach ($videos as $viid) {
            $meta = arrayToList($viid->metas, 'option', 'value');
            $data['videos'][] = [
                'id'        => $viid->id,
                'title'     => $viid->title,
                'date'      => date('Y F d', $viid->created_at),
                'thumbnail' => checkUrl($meta['thumbnail']),
                'currency'  => $currency,
                'duration'  => contentDuration($viid->id),
                'price'     => isset($meta['price'])?$meta['price']:'free'
            ];
            if (isset($meta['duration']))
                $data['duration'] += $meta['duration'];
        }

        foreach ($articles as $article){
            $data['articles'][] = [
                'title' => $article->title,
                'id'    => $article->id,
                'image' => checkUrl($article->image),
                'date'  => date('Y F d', $article->created_at),
                'url'   => url('/').'/blog/mobile/'.$article->id.'?type=article',
            ];
        }

        foreach ($rates as $rate){
            $value = explode(',', $rate['value']);
            $data['rates'][] = [
                'description'   => $rate['description'],
                'image'         => checkUrl($rate['image']),
                'mode'          => $rate['mode'],
                'text'          => trans('admin.from').' '.$value[0].' '.trans('admin.to').' '.$value[1].' '.$rate['mode']
            ];
        }

        return $this->response($data);
    }
    public function userProfileFollow(Request $request){
        $User = $this->checkUserToken($request);
        if(!$User)
            return $this->error(-1, trans('main.user_not_found'));

        Follower::create([
            'follower'  => $request->id,
            'user_id'   => $User['id'],
            'type'      => 'profile'
        ]);

        return $this->response([]);
    }
    public function userProfileUnFollow(Request $request){
        $User = $this->checkUserToken($request);
        if(!$User)
            return $this->error(-1, trans('main.user_not_found'));

        Follower::where('user_id', $User['id'])->where('follower', $request->id)->delete();
        return $this->response([]);
    }

    # Support
    public function supportCategory(Request $request){}
    public function supportList(Request $request){
        $data = [];
        $User = $this->checkUserToken($request);
        if(!$User)
            return $this->error(-1, trans('main.user_not_found'));

        $tickets    = Tickets::with(['category','messages'])->where('user_id', $User['id'])->orderBy('id','DESC')->get();
        $contents   = Content::where('user_id', $User['id'])->pluck('id');
        $comments   = ContentComment::with(['user','content'])->where('mode','publish')->whereIn('content_id', $contents->toArray())->get();
        $supports   = ContentSupport::with(['user','content'])->where('mode','publish')->whereIn('content_id', $contents->toArray())->get();


        foreach ($comments as $comment){
            $data['comments'][] = [
                'user'      => $comment->name,
                'course'    => isset($comment->content->title)?$comment->content->title:'',
                'date'      => date('Y F d', $comment->created_at),
                'comment'   => $comment->comment
            ];
        }

        foreach ($supports->unique(['user_id','content_id']) as $support){
            $data['supports'][] = [
                'user_id'   => $support->user_id,
                'content_id'=> $support->content->id,
                'user'      => $support->name,
                'course'    => isset($support->content->title)?$support->content->title:'',
                'date'      => date('Y F d', $support->created_at),
                'comment'   => $support->comment,
                'messages'  => []
            ];
        }
        if(isset($data['supports'])) {
            foreach ($data['supports'] as $index => $s) {
                foreach ($supports as $SO) {
                    if ($SO->user_id == $s['user_id'] && $SO->content_id == $s['content_id']) {
                        $data['supports'][$index]['messages'][] = [
                            'user_id' => $SO->user_id,
                            'content_id' => $SO->content->id,
                            'user' => $SO->name,
                            'course' => isset($SO->content->title) ? $SO->content->title : '',
                            'date' => date('Y F d', $SO->created_at),
                            'comment' => $SO->comment,
                            'messages' => []
                        ];
                    }
                }
            }
        }

        foreach ($tickets as $index=>$ticket){
            $data['tickets'][] = [
                'id'        => $ticket->id,
                'title'     => $ticket->title,
                'category'  => $ticket->category,
                'mode'      => $ticket->mode,
                'date'      => date('Y F d', $ticket->created_at),
            ];
            foreach ($ticket->messages as $message){
                $data['tickets'][$index]['messages'][] = [
                    'id'    => $message->id,
                    'msg'   => strip_tags($message->msg),
                    'attach'=> checkUrl($message->attach),
                    'mode'  => $message->mode,
                    'date'  => date('Y F d', $message->created_at),
                    'view'  => $message->view
                ];
            }
        }

        return $this->response($data);
    }
    public function supportMessages(Request $request){
        $data = [];
        $User = $this->checkUserToken($request);
        if(!$User)
            return $this->error(-1, trans('main.user_not_found'));

        $ticket = Tickets::where('user_id', $User['id'])->find($request->id);
        if(!$ticket)
            return $this->error(-1,trans('main.access_denied'));

        $messages = TicketsMsg::where('ticket_id', $request->id)->get();
        foreach ($messages as $message){
            $data['messages'][] = [
                'id'    => $message->id,
                'msg'   => strip_tags($message->msg),
                'attach'=> checkUrl($message->attach),
                'mode'  => $message->mode,
                'date'  => date('Y F d', $message->created_at),
                'view'  => $message->view
            ];
        }

        return $this->response($data);
    }
    public function supportAction(Request $request){
        $User = $this->checkUserToken($request);
        if(!$User)
            return $this->error(-1, trans('main.user_not_found'));

        if($request->action == 'close'){
            $Ticket = Tickets::where('user_id', $User['id'])->find($request->id);
            if(!$Ticket)
                return $this->error(-1, trans('main.access_denied'));

            $Ticket->update(['mode'=>'close']);
            return $this->response([]);
        }
    }
    public function supportReply(Request $request){
        $User = $this->checkUserToken($request);
        if(!$User)
            return $this->error(-1, trans('main.user_not_found'));

        $ticket = Tickets::find($request->ticket);

        $file = null;
        if($request->files->has('file')){
            $name = Str::random(16) . '.' . $request->file('file')->getClientOriginalExtension();
            $request->file('file')->move(getcwd().'/bin/ticket/',$name);
            $file = '/bin/ticket/'.$name;
        }

        TicketsMsg::create([
            'ticket_id' => $request->ticket,
            'msg'       => $request->msg,
            'user_id'   => $User['id'],
            'created_at' => time(),
            'mode'      => 'user',
            'attach'    => $file
        ]);

        if($ticket->mode == 'close'){
            $ticket->update(['mode'=>'open']);
        }

        return $this->response([]);
    }
    public function supportNew(Request $request){
        $User = $this->checkUserToken($request);
        if(!$User)
            return $this->error(-1, trans('main.user_not_found'));

        $file = null;
        if($request->files->has('file')){
            $name = Str::random(16) . '.' . $request->file('file')->getClientOriginalExtension();
            $request->file('file')->move(getcwd().'/bin/ticket/',$name);
            $file = '/bin/ticket/'.$name;
        }

        $newTicket = Tickets::create([
            'title'         => $request->title,
            'user_id'       => $User['id'],
            'created_at'     => time(),
            'mode'          =>'open',
            'category_id'   => 0,
            'attach'        => $file
        ]);

        TicketsMsg::create([
                'ticket_id' => $newTicket->id,
                'msg'       => $request->msg,
                'created_at' => time(),
                'user_id'   => $User['id'],
                'mode'      => 'user',
                'attach'    => $file
        ]);

        ## Notification Center
        sendNotification(0,['[t.title]'=>$request->title],get_option('notification_template_ticket_new'),'user',$User['id']);

        return $this->response([]);

    }
    public function supportContentReply(Request $request){
        $User = $this->checkUserToken($request);
        if(!$User)
            return $this->error(-1, trans('main.user_not_found'));

        $Contents   = Content::where('user_id', $User['id'])->pluck('id')->toArray();
        if(in_array($request->content_id,$Contents)){
            ContentSupport::create([
                'comment'       => $request->comment,
                'user_id'       => $request->user_id,
                'supporter_id'  => $User['id'],
                'sender_id'     => $User['id'],
                'name'          => $User['name'],
                'content_id'    => $request->content_id,
                'mode'          => 'publish',
                'created_at'    => time(),
                'rate'          => 1
            ]);
        }else{
            return $this->error(-1,'User Not Permission');
        }
    }

    ## Courses
    public function coursesList(Request $request){
        $currency = currencySign();
        $data = [];
        $User = $this->checkUserToken($request);
        if(!$User)
            return $this->error(-1, trans('main.user_not_found'));


        $lists = Content::where('user_id',$User['id'])->with(['metas'])->withCount('sells')->orderBy('id','DESC')->get();
        foreach ($lists as $item){
            $meta = arrayToList($item->metas,'option','value');
            $data['courses'][] = [
                'id'        => $item->id,
                'title'     => $item->title,
                'thumbnail' => checkUrl($meta['thumbnail']),
                'mode'      => $item->mode,
                'sales'     => $item->sells_count
            ];
        }

        $purchases = Sell::with(['content'=>function($q){
            $q->with(['metas']);
        },'transaction.balance'])->where('buyer_id',$User['id'])->orderBy('id','DESC')->get();
        foreach ($purchases as $item){
            if(isset($item->content)) {
                $meta = arrayToList($item->content->metas, 'option', 'value');
                $data['purchases'][] = [
                    'id'        => $item->content->id,
                    'title'     => $item->content->title,
                    'thumbnail' => checkUrl($meta['thumbnail']),
                    'price'     => isset($meta['price']) ? $meta['price'] : 'free',
                    'amount'    => $item->transaction->price,
                    'currency'  => $currency,
                    'date'      => date('Y F d | H:i', $item->created_at)
                ];
            }
        }


        return $this->response($data);
    }
    ## Financial
    public function financialList(Request $request){
        $currency = currencySign();
        $data = [];
        $User = $this->checkUserToken($request);
        if(!$User)
            return $this->error(-1, trans('main.user_not_found'));

        $list       = Sell::with(['content','transaction'])->where('user_id',$User['id'])->where('mode','pay')->orderBy('id','DESC')->get();
        $documents  = Balance::where('user_id', $User['id'])->orderBy('id','DESC')->get();
        foreach ($list as $item){
            $data['sells'][] = [
                'title'     => isset($item->content->title)?$item->content->title:'',
                'date'      => date('Y F d | H:i', $item->created_at),
                'income'    => $item->transaction->income,
                'currency'  => $currency
            ];
        }
        foreach ($documents as $item){
            $data['documents'][] = [
                'title'   => $item->title,
                'date'    => date('Y F d | H:i', $item->created_at),
                'type'    => $item->type,
                'amount'  => $item->price,
                'currency'=> $currency
            ];
        }

        $data['income']     = $User['income'];
        $data['credit']     = $User['credit'];
        $data['currency']   = $currency;

        return $this->response($data);
    }
    ## Channel
    public function channelList(Request $request){
        $data = [];
        $User = $this->checkUserToken($request);
        if(!$User)
            return $this->error(-1, trans('main.user_not_found'));

        $channels = Channel::withCount('contents')->where('user_id',$User['id'])->get();
        foreach ($channels as $item){
            $data['channels'][] = [
                'title'     => $item->title,
                'thumbnail' => checkUrl($item->image),
                'mode'      => $item->mode,
                'contents'  => $item->contents_count
            ];
        }

        return $this->response($data);
    }
    public function channelNew(Request $request){}

    ## Wallet
    public function walletPay(Request $request){
        $User = $this->checkUserToken($request);
        if(!$User)
            return redirect('/')->with('msg',trans('main.user_not_found'));
        $user = $User;

        if (!is_numeric($request->price) || $request->price == 0)
            return redirect('/')->with('msg', trans('main.number_only'));

        if($request->type == 'paypal') {
            $payer = new Payer();
            $payer->setPaymentMethod('paypal');
            $item_1 = new Item();
            $item_1->setName('charge account')
                ->setCurrency('USD')
                ->setQuantity(1)
                ->setPrice($request->price);
            $item_list = new ItemList();
            $item_list->setItems(array($item_1));
            $amount = new Amount();
            $amount->setCurrency('USD')
                ->setTotal($request->price);
            $transaction = new \PayPal\Api\Transaction();
            $transaction->setAmount($amount)
                ->setItemList($item_list)
                ->setDescription('Charge Your Wallet');
            $redirect_urls = new RedirectUrls();
            $redirect_urls->setReturnUrl(url('/').'/api/v1/user/wallet/verify?gateway=paypal')
                ->setCancelUrl(url('/').'/payment/wallet/cancel');
            $payment = new Payment();
            $payment->setIntent('Sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirect_urls)
                ->setTransactions(array($transaction));
            try {
                $payment->create($this->_api_context);
            } catch (\PayPal\Exception\PPConnectionException $ex) {
                if (\Config::get('app.debug')) {
                    \Session::put('error', 'Connection timeout');
                    return Redirect::route('paywithpaypal');
                } else {
                    \Session::put('error', 'Some error occur, sorry for inconvenient');
                    return Redirect::route('paywithpaypal');
                }
            }
            foreach ($payment->getLinks() as $link) {
                if ($link->getRel() == 'approval_url') {
                    $redirect_url = $link->getHref();
                    break;
                }
            }
            TransactionCharge::create([
                'user_id'   => $User['id'],
                'price'     => $request->price,
                'mode'      => 'pending',
                'authority' => $payment->getId(),
                'created_at' => time(),
                'bank'      => 'paypal'
            ]);
            /** add payment ID to session **/
            \Session::put('paypal_payment_id', $payment->getId());
            if (isset($redirect_url)) {
                /** redirect to paypal **/
                return Redirect::away($redirect_url);
            }
            \Session::put('error', 'Unknown error occurred');
            return Redirect::route('paywithpaypal');
        }
        if($request->type == 'income'){
            if($request->price <= $user['income']){
                User::find($user['id'])->update([
                    'income'=>$user['income']-$request->price,
                    'credit'=>$user['credit']+$request->price
                ]);
                Balance::create([
                    'title'=>trans('main.user_account_charge'),
                    'description'=>trans('main.account_charged'),
                    'type'=>'add',
                    'price'=>$request->price,
                    'mode'=>'auto',
                    'user_id'=>$user['id'],
                    'exporter_id'=>0,
                    'created_at'=>time()
                ]);
                Balance::create([
                    'title'=>trans('main.income_deduction'),
                    'description'=>trans('main.charge_transfer'),
                    'type'=>'minus',
                    'price'=>$request->price,
                    'mode'=>'auto',
                    'user_id'=>$user['id'],
                    'exporter_id'=>0,
                    'created_at'=>time()
                ]);
                return redirect()->back()->with('msg',trans('main.account_charged_success'));
            }else{
                return redirect()->back()->with('msg',trans('main.income_not_enough'));
            }
        }
        if($request->type == 'paytm'){
            $payment = PaytmWallet::with('receive');
            $Transaction = TransactionCharge::create([
                'user_id'   => $user['id'],
                'price'     => $request->price,
                'mode'      => 'pending',
                'authority' => $payment->getId(),
                'created_at' => time(),
                'bank'      => 'paytm'
            ]);
            $payment->prepare([
                'order'         => $Transaction->id,
                'user'          => $user['id'],
                'email'         => $user['email'],
                'mobile_number' => '00187654321',
                'amount'        => $Transaction->price,
                'callback_url'  => url('/').'/api/v1/user/wallet/verify?gateway=paytm'
            ]);

            return $payment->receive();
        }
        if($request->type == 'paystack'){
            $payStack    = new \Unicodeveloper\Paystack\Paystack();
            $Transaction = TransactionCharge::create([
                'user_id'   => $user['id'],
                'price'     => $request->price,
                'mode'      => 'pending',
                'authority' => 0,
                'created_at' => time(),
                'bank'      => 'paystack'
            ]);
            $payStack->getAuthorizationResponse([
                "amount"        => $request->price,
                "reference"     => Paystack::genTranxRef(),
                "email"         => $user['email'],
                "callback_url"  => url('/').'/api/v1/user/wallet/verify?gateway=paystack',
                'metadata'      => json_encode(['transaction'=>$Transaction->id])
            ]);
            return redirect($payStack->url);
        }

        return redirect()->back()->with('msg',trans('main.feature_disabled'));
    }
    public function walletVerify(Request $request){
        if(isset($request->gateway) && $request->gateway == 'paypal') {
            $payment_id = \Session::get('paypal_payment_id');
            \Session::forget('paypal_payment_id');
            if (empty($request->PayerID) || empty($request->token)) {
                \Session::put('error', 'Payment failed');
                return view(getTemplate() . '.app.wallet',['mode'=>'failed']);
            }
            $payment = Payment::get($payment_id, $this->_api_context);
            $execution = new PaymentExecution();
            $execution->setPayerId($request->PayerID);
            $result = $payment->execute($execution, $this->_api_context);
            if ($result->getState() == 'approved') {
                $transaction = TransactionCharge::where('mode', 'pending')->where('authority', $payment_id)->first();
                $Amount = $transaction->price;
                Balance::create([
                    'title' => 'Wallet',
                    'description' => 'Wallet charge',
                    'type' => 'add',
                    'price' => $Amount,
                    'mode' => 'auto',
                    'user_id' => $transaction->user_id,
                    'exporter_id' => 0,
                    'created_at' => time()
                ]);
                $userUpdate = User::find($transaction->user_id);
                $userUpdate->update(['credit' => $userUpdate->credit + $Amount]);

                TransactionCharge::find($transaction->id)->update(['mode' => 'deliver']);
                return view(getTemplate() . '.app.wallet',['mode'=>'successfully']);
            }
        }
        if(isset($request->gateway) && $request->gateway == 'paytm'){
            $transaction = PaytmWallet::with('receive');
            $Transaction = TransactionCharge::find($transaction->getOrderId());

            if($transaction->isSuccessful()){
                $Amount = $Transaction->price;
                Balance::create([
                    'title' => 'Wallet',
                    'description' => 'Wallet',
                    'type' => 'add',
                    'price' => $Amount,
                    'mode' => 'auto',
                    'user_id' => $Transaction->user_id,
                    'exporter_id' => 0,
                    'created_at' => time()
                ]);
                $userUpdate = User::find($Transaction->user_id);
                $userUpdate->update(['credit' => $userUpdate->credit + $Amount]);

                TransactionCharge::find($Transaction->id)->update(['mode' => 'deliver']);
                return view(getTemplate() . '.app.wallet',['mode'=>'successfully']);
            }else{
                return view(getTemplate() . '.app.wallet',['mode'=>'failed']);
            }
        }
        if(isset($request->gateway) && $request->gateway == 'paystack'){
            $payment = Paystack::getPaymentData();
            if(isset($payment['status']) && $payment['status'] == true){
                $Transaction = TransactionCharge::find($payment['data']['metadata']['transaction']);
                $Amount = $Transaction->price;
                Balance::create([
                    'title' => 'Wallet',
                    'description' => 'Wallet charge',
                    'type' => 'add',
                    'price' => $Amount,
                    'mode' => 'auto',
                    'user_id' => $Transaction->user_id,
                    'exporter_id' => 0,
                    'created_at' => time()
                ]);
                $userUpdate = User::find($Transaction->user_id);
                $userUpdate->update(['credit' => $userUpdate->credit + $Amount]);

                TransactionCharge::find($Transaction->id)->update(['mode' => 'deliver']);
                return view(getTemplate() . '.app.wallet',['mode'=>'successfully']);
            }else{
                return view(getTemplate() . '.app.wallet',['mode'=>'failed']);
            }
        }

        return view(getTemplate() . '.app.wallet',['mode'=>'failed']);
    }

    ## Quiz
    public function quiz($quiz_id,Request $request){
        $user = $this->checkUserToken($request);
        if(!$user)
            dd(trans('main.no_user'));

        $quiz = Quiz::where('id', $quiz_id)
            ->with(['questions' => function ($query) {
                $query->with(['questionsAnswers']);
            }, 'questionsGradeSum'])
            ->first();

        if ($quiz) {
            $attempt_count = $quiz->attempt;
            $userQuizDone = QuizResult::where('quiz_id', $quiz->id)
                ->where('student_id', $user['id'])
                ->get();
            $status_pass = false;
            foreach ($userQuizDone as $result) {
                if ($result->status == 'pass') {
                    $status_pass = true;
                }
            }

            if (!isset($quiz->attempt) or (count($userQuizDone) < $attempt_count and !$status_pass)) {
                $newQuizStart = QuizResult::create([
                    'quiz_id'   => $quiz->id,
                    'student_id'=> $user['id'],
                    'results'   => '',
                    'user_grade'=> '',
                    'status'    => 'waiting',
                    'created_at'=> time()
                ]);

                $data = [
                    'quiz' => $quiz,
                    'newQuizStart' => $newQuizStart
                ];

                return view( 'app.quiz.start', $data);
            } else {
                dd('main.quiz_number_attempt');
            }
        }
        dd('No Quiz Found!');
    }
    public function quizStore(Request $request, $quiz_id)
    {
        $user = $this->checkUserToken($request);
        if(!$user)
            dd(trans('main.no_user'));
        $quiz = Quiz::where('id', $quiz_id)->first();
        if ($quiz) {
            $results = $request->get('question');
            $quiz_result_id = $request->get('quiz_result_id');
            if (!empty($quiz_result_id)) {
                $quiz_result = QuizResult::where('id', $quiz_result_id)
                    ->where('student_id', $user['id'])
                    ->first();

                if (!empty($quiz_result)) {
                    $pass_mark = $quiz->pass_mark;
                    $total_mark = 0;
                    $status = '';

                    foreach ($results as $question_id => $result) {
                        if (!is_array($result)) {
                            unset($results[$question_id]);
                        } else {
                            $question = QuizzesQuestion::where('id', $question_id)
                                ->where('quiz_id', $quiz->id)
                                ->first();
                            if ($question and !empty($result['answer'])) {
                                $answer = QuizzesQuestionsAnswer::where('id', $result['answer'])
                                    ->where('question_id', $question->id)
                                    ->where('user_id', $quiz->user_id)
                                    ->first();

                                $results[$question_id]['status'] = false;
                                $results[$question_id]['grade'] = $question->grade;

                                if ($answer and $answer->correct) {
                                    $results[$question_id]['status'] = true;
                                    $total_mark += (int)$question->grade;
                                }

                                if ($question->type == 'descriptive') {
                                    $status = 'waiting';
                                    //$total_mark += (int)$question->grade;
                                }
                            }
                        }
                    }

                    if (empty($status)) {
                        $status = ($total_mark >= $pass_mark) ? 'pass' : 'fail';
                    }

                    $quiz_result->update([
                        'results' => json_encode($results),
                        'user_grade' => $total_mark,
                        'status' => $status,
                        'created_at' => time()
                    ]);

                    return view('app.quiz.results',['grade'=>$total_mark]);
                }
            }
        }
        abort(404);
    }


    ## Mpesa
    public function mpesaPay($id,$mode = 'product'){
        if($mode == 'product')
            $Transaction = Transaction::find($id);
        if($mode == 'wallet')
            $Transaction = TransactionCharge::find($id);

        return view('web.default.app.mpesa',['mode'=>'pay','transaction'=>$Transaction,'type'=>$mode]);
    }
    public function mpesaVerify(Request $request){
        if($request->mode == 'product')
            $Transaction = Transaction::find($request->id);
        if($request->mode == 'wallet')
            $Transaction = TransactionCharge::find($request->id);

        $response = Mpesa::simulateC2B($Transaction->price,$request->phone,$Transaction->id);
        $response = json_decode($response,true);
        if(isset($response['ConversationID'])) {
            return view('web.default.app.mpesa', ['mode' => 'verify','id'=>$Transaction->id,'type'=>$request->mode,'Transaction'=>$Transaction]);
        }else
            return redirect('/')->with('msg',trans('main.failed'));

    }
    public function mpesaConfirm(Request $request){
        if(isset($request->TransID)){
            $Transaction = Transaction::find($request->InvoiceNumber);
            $Transaction->update(['mode'=>'deliver']);
        }
    }
    public function mpesaAjax($id,$type){
        if($type == 'product'){
            $Transaction = Transaction::find($id);
        }else{
            $Transaction = TransactionCharge::find($id);
        }

        if($Transaction->mode == 'deliver')
            return 1;
        else
            return 0;



        return 0;
    }
}
