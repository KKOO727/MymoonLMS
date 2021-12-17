<?php

namespace App\Http\Controllers;

use Anand\LaravelPaytmWallet\Facades\PaytmWallet;
use App\Classes\CinetPay;
use App\Classes\VideoStream;
use App\Models\Article;
use App\Models\ArticleRate;
use App\Models\Balance;
use App\Models\Blog;
use App\Models\BlogComments;
use App\Models\Channel;
use App\Models\Content;
use App\Models\ContentCategory;
use App\Models\ContentCategoryFilterTag;
use App\Models\ContentCategoryFilterTagRelation;
use App\Models\ContentComment;
use App\Models\ContentPart;
use App\Models\ContentRate;
use App\Models\ContentSupport;
use App\Models\ContentVip;
use App\Models\Discount;
use App\Models\DiscountContent;
use App\Models\FaqsModel;
use App\Models\Favorite;
use App\Models\Follower;
use App\Models\HomeFeedbackModel;
use App\Models\Login;
use App\Models\MeetingDate;
use App\Models\QuizResult;
use App\Models\Record;
use App\Models\RecordFans;
use App\Models\RequestFans;
use App\Models\Requests;
use App\Models\RequestSuggestion;
use App\Models\Sell;
use App\Models\SellRate;
use App\Models\Social;
use App\Models\Transaction;
use App\Models\TransactionCharge;
use App\Models\Usage;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Razorpay\Api\Api;
use Unicodeveloper\Paystack\Facades\Paystack;
use Vimeo\Laravel\Facades\Vimeo;
use \Jenssegers\Agent\Agent;

class WebController extends Controller
{
    public function __construct()
    {


    }

    // home page

    private static function orderPrice($a, $b)
    {
        if ($a['metas']['price'] == $b['metas']['price'])
            return 0;
        if ($a['metas']['price'] < $b['metas']['price'])
            return 1;
        else
            return -1;
    }

    private static function orderCheap($a, $b)
    {
        if ($a['metas']['price'] == $b['metas']['price'])
            return 0;
        if ($a['metas']['price'] > $b['metas']['price'])
            return 1;
        else
            return -1;
    }

    private static function orderSell($a, $b)
    {
        if (count($a['sells']) == count($b['sells']))
            return 0;
        if (count($a['sells']) < count($b['sells']))
            return 1;
        else
            return -1;
    }

    private static function orderView($a, $b)
    {
        if ($a['view'] == $b['view'])
            return 0;
        if ($a['view'] < $b['view'])
            return 1;
        else
            return -1;
    }

    public function booking(Request $request, $id = null)
    {

        $user = (auth()->check()) ? auth()->user() : null;
        dd($user);

        //return view(getTemplate() . '.view.main', $data);
    }


    public function home()
    {

        $user = (auth()->check()) ? auth()->user() : null;
        $socials = Social::get();

        $home_feedback = HomeFeedbackModel::orderBy("serial")->get();


        $data = [
            'new_content' => $this->getContents_new(13, 'id', 'DESC', '', 3),
            'sell_content' => $this->getContents('sellCount', 'sells_count', 'DESC', 'sells'),
            'popular_content' => $this->getContents('popular', 'view', 'DESC'),
            'live_content' => $this->getLiveContents(),
            'blog_post' => $this->getBlogPosts(),
            'article_post' => $this->getArticlePosts(),
            'user_rate' => $this->getUsersWithRates(),
            'user_content' => $this->getUsersMostContent(),
            'user_popular' => $this->getPopularUsers(),
            'slider_container' => $this->getSliderContainer(),
            'channels' => $this->getChannels(),
            'user' => $user,
            'socials' => $socials,
            'feedbacks' => $home_feedback,
        ];

        return view(getTemplate() . '.view.main', $data);
    }

    private function getContents_new($catID, $orderByName, $orderByValue, $withCount = null, $limit = 10)
    {
        $query = Content::where('category_id', $catID)
            ->orderBy($orderByName, $orderByValue)
            ->limit($limit)
            ->with('metas', 'user');

        if (!empty($withCount)) {
            $query->withCount($withCount);
        }

        return $query->get();
    }

    private function getContents($tag, $orderByName, $orderByValue, $withCount = null, $limit = 10)
    {
        $query = Content::where('mode', 'publish')->where('type','3')
            ->orderBy($orderByName, $orderByValue)
            ->orderBy($orderByName, $orderByValue)
            ->limit($limit)
            ->with('metas', 'user');

        if (!empty($withCount)) {
            $query->withCount($withCount);
        }

        return $query->get();
    }

    private function getLiveContents()
    {
        $contents = Content::where('mode', 'publish')->orderBy('id', 'DESC')->limit(10)
            ->with('metas', 'user')
            ->where('type', 'LIKE', '%webinar%');

        return $contents->get();
    }

    private function getBlogPosts()
    {
        return Blog::where('mode', 'publish')
            ->orderBy('id', 'DESC')
            ->limit(get_option('main_page_blog_post_count', 1))
            ->get();

    }

    private function getArticlePosts()
    {
        return Article::where('mode', 'publish')
            ->orderBy('id', 'DESC')
            ->limit(get_option('main_page_article_post_count', 1))
            ->get();

    }

    private function getUsersWithRates()
    {
        $userQuery = $this->getUserQuery();
        return $userQuery->where('type', 'Teacher')
            ->with('usermetas')
            //->orderBy('rate_count', 'DESC')
            //->orderBy('rate_point', 'DESC')
            ->limit(12)
            ->get();
    }

    private function getUserQuery()
    {
        return User::where('mode', 'active')
            ->where('type', '!=', 'admin');

    }

    private function getUsersMostContent()
    {
        $userQuery = $this->getUserQuery();
        return $userQuery->withCount(['contents' => function ($query) {
            $query->where('mode', 'publish');
        }])
            ->with(['usermetas'])
            ->orderBy('contents_count', 'DESC')
            ->limit(4)
            ->get();
    }

    private function getPopularUsers()
    {
        $userQuery = $this->getUserQuery();
        return $userQuery->orderBy('view', 'DESC')
            ->with(['usermetas'])
            ->limit(4)
            ->get();
    }

    // .\ home page

    private function getContentVip()
    {
        $vipContentQuery = $this->getContentVipQuery();
        return $vipContentQuery->with('content.user')->get();

    }

    private function getContentVipQuery()
    {
        return ContentVip::where('mode', 'publish')
            ->where('first_date', '<', time())
            ->where('last_date', '>', time());
    }

    private function getSliderContainer()
    {
        $vipContentQuery = $this->getContentVipQuery();
        return $vipContentQuery->with(['content' => function ($query) {
            $query->with(['metas', 'user']);
        }])->get();

    }

    private function getChannels()
    {
        $channelsQuery = Channel::where('mode', 'active');

        $channels['new'] = $channelsQuery->orderBy('id', 'DESC')
            ->limit(4)
            ->get();


        $channels['view'] = $channelsQuery->orderBy('view', 'DESC')
            ->limit(4)
            ->get();


        $channels['popular'] = $channelsQuery->with(['user' => function ($query) {
            $query->withCount(['follow']);
        }])
            ->get()
            ->sortByDesc('user.followCount');


        return $channels;
    }

    public function category(Request $request, $id = null)
    {
        $order = $request->get('order', null);
        $price = $request->get('price', null);
        $course = $request->get('course', null);
        $off = $request->get('off', null);
        if ($request->get('filter') != null) {
            $filters = array_unique($request->get('filter'));
        } else {
            $filters = null;
        }
        $q = $request->get('q', null);
        $post_sell = $request->get('post-sell', null);
        $support = $request->get('support', null);

        $Category = ContentCategory::with(array('filters' => function ($q) {
            $q->with('tags');
        }))->where('class', $id)->first();


        $vipContent = [];
        if ($Category) {
            $vipContent = $this->getContentVipQuery()
                ->where('type', 'category')
                ->where('category_id', $Category->id)
                ->with(['content' => function ($q) {
                    $q->with(['metas', 'sells', 'discount'])->where('mode', 'publish');
                }])
                ->orderBy('id', 'DESC')
                ->get();
        }

        $content = Content::where('mode', 'publish')->with(['metas', 'sells', 'discount', 'user'])->withCount('parts');


        if (!empty($Category)) {
            //$content = $content->where('category_id', $Category->id);
        }

        if (isset($q) and $q != '') {
            $content->where('title', 'LIKE', '%' . $q . '%');
        }

        if (isset($post_sell) and $post_sell == 1) {
            $content->where('post', '1');
        }

        if (isset($support) and $support == 1) {
            $content->where('support', '1');
        }

        if (isset($order) and $order == 'old') {
            $content->orderBy('id');
        }

        if (isset($order) and $order == 'new') {
            $content->orderBy('id', 'DESC');
        }

        ## Set For Course
        switch ($course) {
            case 'one':
                $content->where('type', 'single');
                break;
            case 'multi':
                $content->where('type', 'course');
                break;
            case 'webinar':
                $content->where(function ($w) {
                    $w->where('type', 'webinar')->orwhere('type', 'course+webinar');
                });
                break;
            default:
                break;
        }

        $content = $content->get()->toArray();


        foreach ($content as $index => $c) {
            $content[$index]['metas'] = arrayToList($c['metas'], 'option', 'value');
        }

        ## Most Sell
        $mostSellContent = $content;
        usort($mostSellContent, array($this, 'orderSell'));
        $mostSellContent = array_slice($mostSellContent, 0, 3);


        ## Set For OrderBy
        switch ($order) {
            case 'price':
                usort($content, array($this, 'orderPrice'));
                break;
            case 'cheap':
                usort($content, array($this, 'orderCheap'));
                break;
            case 'sell':
                usort($content, array($this, 'orderSell'));
                break;
            case 'popular':
                usort($content, array($this, 'orderView'));
                break;
            default:
                break;
        }

        ## Set For Pricing
        switch ($price) {
            case 'all':
                break;
            case 'free':
                $content = $this->pricing($content, 'free');
                break;
            case 'price':
                $content = $this->pricing($content, 'price');
                break;
            default:
                break;
        }

        ## Set For Off
        if ($off == 1) {
            $content = $this->off($content);
        }

        ## Set For Filters
        if ($filters != '') {
            foreach ($content as $index => $c) {
                $getFilters = ContentCategoryFilterTagRelation::where('content_id', $c['id'])->pluck('tag_id')->toArray();
                if ($getFilters)
                    $content[$index]['metas']['filters'] = serialize($getFilters);
            }
            $content = $this->filters($content, $filters);
        }

        $data = [
            'category' => $Category,
            'contents' => $content,
            'vip' => $vipContent,
            'order' => $order,
            'pricing' => $price,
            'course' => $course,
            'off' => $off,
            'filters' => $filters,
            'mostSell' => $mostSellContent
        ];

        if ($id != null)
            return view(getTemplate() . '.view.category.category', $data);
        else
            return view(getTemplate() . '.view.category.category_base', $data);
    }

    public function teachers(Request $request)
    {

        $price = $request->get('price', null);
        $order = $request->get('order', null);

        $categories = ContentCategory::get();
        $teachers = User::where('mode', 'active')->where('type', 'Teacher')
            ->with('category')
            ->with('contents')
            ->with('point')
            ->with('quizzes')
            ->get();

        $data = [
            'teachers' => $teachers,
            //'contents' => $content,
            //'vip' => $vipContent,
            'order' => $order,
            'pricing' => $price,
            //'course' => $course,
            //'off' => $off,
            //'filters' => $filters,
            'categories' => $categories
        ];
        return view(getTemplate() . '.view.category.teacher_base', $data);
    }

    private function pricing($array, $mode)
    {
        if ($mode == 'price') {
            foreach ($array as $index => $a) {
                if (!isset($a['metas']['price']) || $a['metas']['price'] == 0) {
                    unset($array[$index]);
                }
            }
        }
        if ($mode == 'free') {
            foreach ($array as $index => $a) {
                if (!empty($a['metas']['price']) and $a['metas']['price'] > 0) {
                    unset($array[$index]);
                }
            }
        }
        return $array;
    }

    private function off($array)
    {
        foreach ($array as $index => $a) {
            if (!isset($a['metas']['price']) || $a['metas']['price'] == 0) {
                unset($array[$index]);
            } else {
                if ($a['discount'] == null)
                    unset($array[$index]);
            }
        }

        return $array;
    }

    private function filters($array, $filter)
    {
        foreach ($array as $index => $a) {
            if (isset($a['metas']['filters'])) {
                $filters_in = unserialize($a['metas']['filters']);
                $c = array_intersect($filters_in, $filter);
                if (count($c) == 0)
                    unset($array[$index]);
            } else {
                unset($array[$index]);
            }
        }
        return $array;
    }


    public function search(Request $request)
    {
        $agent = new Agent();

        return view(getTemplate() . '.view.search.search', compact('agent'));
    }

    public function ajaxSearch(Request $request)
    {
        $agent = new Agent();

        $filters = false;
        $search_input = $request->input('q');
        $words = array();
        if ($search_input != null || $search_input != '') {
            $words = explode(" ", $search_input);
            $filters = true;
        }

        $key_categories = array();
        $teaches = $request->input('teaches');
        if ($teaches != null || $teaches != '') {
            $key_categories = $teaches;
            $filters = true;
        }

        $key_styles = array();
        $styles = $request->input('styles');
        if ($styles != null || $styles != '') {
            $key_styles = $styles;
            $filters = true;
        }

        $key_languages = array();
        $language = $request->input('language');
        if ($language != null || $language != '') {
            $key_languages = $language;
            $filters = true;
        }

        $key_genders = array();
        $gender = $request->input('gender');
        if ($gender != null || $gender != '') {
            $key_genders = $gender;
            $filters = true;
        }

        $key_country = array();
        $country = $request->input('country');
        if ($country != null || $country != '') {
            $key_country = $country;
            $filters = true;
        }

        $key_students = array();
        $student_type = $request->input('student_type');
        if ($student_type != null || $student_type != '') {
            $key_students = $student_type;
            $filters = true;
        }
        $key_order = $request->order;
        if ($key_order == null || $key_order == '') {
            $key_order = 'Ratings';
            $filters = true;
        }

        $temp_teachers = [];
        $allTeachers = User::where('mode', 'active')->where('type', 'Teacher')->get();

        if ($filters) {
            foreach ($allTeachers as $teacher) {
                $include = false;
                $userMeta = get_all_user_meta($teacher->id);
                $content_info = get_teacher_contents_info($teacher->id);

                //Filters for input text
                $include_word = false;
                if (count($words) > 0) {
                    foreach ($words as $word) {
                        if (strtolower($word) == 'man' || strtolower($word) == 'men' || strtolower($word) == 'brother') {
                            $word = 'male';
                        }
                        if (strtolower($word) == 'woman' || strtolower($word) == 'women' || strtolower($word) == 'sister') {
                            $word = 'female';
                        }

                        if (stripos($teacher->name, $word) !== false) {
                            $include_word = true;
                            break;
                        }

                        foreach ($userMeta as $key => $value) {
                            if ($key == 'meta_gender') {
                                if ($value == $word) {
                                    $include_word = true;
                                    break;
                                }
                            } else {
                                if (stripos($value, $word) !== false) {
                                    $include_word = true;
                                    break;
                                }
                            }
                        }
                    }
                } else {
                    $include_word = true;
                }

                $include_cat = false;
                $categories = $content_info['category'];
                if (count($key_categories) > 0) {
                    foreach ($key_categories as $filter) {
                        if (in_array($filter, $categories) || $filter=='All') {
                            $include_cat = true;
                            break;
                        }
                    }
                } else {
                    $include_cat = true;
                }

                $include_style = false;
                if (count($key_styles) > 0) {
                    $content_types = $content_info['type'];
                    foreach ($key_styles as $filter) {
                        if (in_array($filter, $content_types)) {
                            $include_style = true;
                            break;
                        }
                    }
                } else {
                    $include_style = true;
                }

                $include_lan = false;
                if (count($key_languages) > 0) {
                    $languages = $content_info['language'];
                    foreach ($key_languages as $filter) {
                        if (in_array($filter, $languages)  || $filter=='All') {
                            $include_lan = true;
                            break;
                        }
                    }
                } else {
                    $include_lan = true;
                }

                $include_gender = false;
                if (count($key_genders) > 0) {
                    if (isset($userMeta['meta_gender'])) {
                        foreach ($key_genders as $word) {
                            if (strtolower($userMeta['meta_gender']) == strtolower($word) || $word=='All') {
                                $include_gender = true;
                                break;
                            }
                        }
                    }
                } else {
                    $include_gender = true;
                }

                $include_country = false;
                if (count($key_country) > 0) {
                    if (isset($userMeta['meta_country'])) {
                        foreach ($key_country as $word) {
                            if (stripos($userMeta['meta_country'], $word) !== false || $word=='All') {
                                $include_country = true;
                                break;
                            }
                        }
                    }
                } else {
                    $include_country = true;
                }

                $include_st = false;

                if (count($key_students) > 0) {
                    if (isset($userMeta['meta_student_type'])) {
                        foreach ($key_students as $word) {
                            if (stripos($userMeta['meta_student_type'], $word) !== false || $word=='All') {
                                $include_st = true;
                                break;
                            }
                        }
                    }
                } else {
                    $include_st = true;
                }


                if ($include_st && $include_country && $include_gender && $include_lan && $include_style && $include_cat && $include_word) {
                    $temp_teachers[] = $this->includeTeacher($teacher, 'satisfied');
                }


            }
        } else {
            foreach ($allTeachers as $teacher) {
                $temp_teachers[] = $this->includeTeacher($teacher, 'all');
            }
        }

        if ($key_order == 'Lowest') {
            usort($temp_teachers, function ($a, $b) {
                return $a['hourly_rate'] <=> $b['hourly_rate'];
            });
        } elseif ($key_order == 'Highest') {
            usort($temp_teachers, function ($a, $b) {
                return $b['hourly_rate'] <=> $a['hourly_rate'];
            });
        } else if ($key_order == 'Ratings') {
            usort($temp_teachers, function ($a, $b) {
                return $b['rating'] <=> $a['rating'];
            });
        }

        if ($agent->isMobile()) {
            return $this->generateTeacherBox($temp_teachers, 'mobile');    
        }else{
            return $this->generateTeacherBox($temp_teachers, 'desktop');    
        }
    }

    public function includeTeacher($teacher, $reason)
    {
        $data = [
            'rating' => get_user_rating($teacher->id),
            'hourly_rate' => get_user_meta($teacher->id, 'meta_hourly_rate', 0),
            'reason' => $reason,
            'id' => $teacher->id,
        ];
        return $data;
    }

    public function generateTeacherBox($teachers, $device)
    {
        $str = '';

        if($device == 'desktop') {
            foreach ($teachers as $teacher) {
                $str .= '<div class="teacher-row"><div class="row"><div class="col-md-2 text-center">';
                $str .= getProfileBox($teacher['id']);
                $str .= getTeacherRating($teacher['id']);
                $str .= '<a href="/profile/' . $teacher['id'] . '" class="btn btn-primary">BOOK NOW</a></div>';
                $str .= '<div class="col-md-5" style="border-right:1px solid #e1e1e1">';
                $str .= getProfileText($teacher['id'], true);
                $str .= '</div><div class="col-md-5"><div class="teacher-rate">' . currency(floatval($teacher['hourly_rate'])) . '<span style="color:#cccccc;font-size:16px">/hour</span></div>';
                $video = get_user_meta($teacher['id'], 'meta_intro_video', '');
                $str .= getVideoPlayer($video, '100%', '180');
                $str .= '</div></div></div>';
            }
        }else{
            foreach ($teachers as $teacher) {
                $str .= '<div class="teacher-row"><div class="row d-flex"><div class="col-md-3">';
                $str .= getProfileBox($teacher['id']);
                // $str .= getTeacherRating($teacher['id']);
                // $str .= '<a href="/profile/' . $teacher['id'] . '" class="btn btn-primary">BOOK NOW</a></div>';
                $str .= '</div><div class="col-md-9">';
                $str .= getProfileText($teacher['id'], false);
                $str .= getTeacherRatingForMobile($teacher['id']);
                $str .= '</div></div><br><div class="row col-md-12">';
                $str .= getProfileForMobile($teacher['id']);
                $str .= '</div><div class="align-items-center row"><div class="teacher-rate pull-left" style="padding-top: 3%; padding-left: 5%;">' . currency(floatval($teacher['hourly_rate'])) . '<span style="color:#cccccc;font-size:16px">/hour</span></div><div class="book_box pull-right" style="padding-right: 5%;"><a href="/profile/' . $teacher['id'] . '" class="btn btn-primary">BOOK NOW</a></div></div>';
                // $video = get_user_meta($teacher['id'], 'meta_intro_video', '');
                // $str .= getVideoPlayer($video, '100%', '180');
                $str .= '</div>';
            }
        }

        return $str;
    }


    private function getContentIds($q)
    {
        $tag_ids = ContentCategoryFilterTag::where('tag', 'LIKE', '%' . $q . '%')->pluck('id')->toArray();

        $content_ids = ContentCategoryFilterTagRelation::whereIn('tag_id', $tag_ids)->pluck('content_id')->toArray();

        return array_unique($content_ids);
    }

    public function jsonSearch(Request $request)
    {
        $q = $request->get('q', null);
        if (strlen($q) < 5) {
            $content = ["title" => " trans('admin.searching') $q..."];
            return $content;
        }

        $contentQuery = Content::where('mode', 'publish');

        $content = $contentQuery->select('id', 'title')
            ->where('title', 'LIKE', '%' . $q . '%')
            ->take(3)
            ->get();

        $content_ids = $this->getContentIds($q);

        $content_filter = $contentQuery->select('id', 'title')
            ->whereIn('id', $content_ids)
            ->get();


        $content = array_merge($content->toArray(), $content_filter->toArray());
        foreach ($content as $index => $con) {
            $content[$index]['code'] = "(VT-" . $con['id'] . ")";
        }
        return $content;

    }

    public function blog()
    {
        $posts = Blog::where('mode', 'publish')->orderBy('id', 'DESC')->get();

        return view(getTemplate() . '.view.blog.blog', ['posts' => $posts]);
    }

    public function blogPost($id)
    {
        $post = Blog::where('id', $id)->where('mode', 'publish')
            ->with(array('category', 'comments' => function ($query) {
                $query->where('parent', 0)->where('mode', 'publish')->with(array('childs' => function ($q) {
                    $q->where('mode', 'publish');
                }));
            }))->first();

        return view(getTemplate() . '.view.blog.post', ['post' => $post]);
    }

    public function blogPostMobile(Request $request, $id)
    {
        $type = $request->get('type', null);

        $post = Blog::where('mode', 'publish');
        if (isset($type) and $type == 'article') {
            $post = Article::where('mode', 'publish');
        }
        $post = $post->find($id);

        return view(getTemplate() . '.app.post', ['post' => $post]);
    }

    public function blogCategory($id)
    {
        $posts = Blog::where('mode', 'publish')
            ->where('category_id', $id)
            ->orderBy('id', 'DESC')
            ->get();

        if ($posts) {
            return view(getTemplate() . '.view.blog.blog', ['posts' => $posts]);
        }

        abort(404);
    }

    public function blogPostCommentStore(Request $request)
    {
        $user = (auth()->check()) ? auth()->user() : null;

        if (!empty($user)) {
            $data = $request->all();
            $data['mode'] = 'draft';
            $data['created_at'] = time();
            $data['user_id'] = $user->id;
            $data['name'] = $user->name;
            BlogComments::create($data);
        }
        return back();
    }

    public function blogTags($key)
    {
        $posts = Blog::where('mode', 'publish')
            ->where('tags', 'LIKE', '%' . $key . '%')
            ->orderBy('id', 'DESC')
            ->get();
        return view(getTemplate() . '.view.blog.blog', ['posts' => $posts]);
    }

    public function giftChecker($code)
    {
        $gift = Discount::where('code', $code)
            ->where('created_at', '<', time())
            ->where('expire_at', '>', time())
            ->first();

        if (!empty($gift)) {
            session(['gift' => $gift->id]);
            return $gift;
        }

        return 0;
    }

    ## Gift & Off

    public function chanel($username)
    {
        $user = (auth()->check()) ? auth()->user() : false;
        $chanel = Channel::where('username', $username)
            ->where('mode', 'active')
            ->with(['contents' => function ($q) {
                $q->with(['content' => function ($c) {
                    $c->with('metas')->where('mode', 'publish');
                }]);
            }])->withCount(['contents'])
            ->first();

        if (!empty($chanel)) {
            $follow = 0;
            if ($user) {
                $follow = Follower::where('user_id', $user->id)
                    ->where('type', 'chanel')
                    ->where('follower', $chanel->user_id)
                    ->count();
            }

            $duration = 0;
            foreach ($chanel->contents as $c) {
                if (!empty($c->content->metas)) {
                    $meta = arrayToList($c->content->metas, 'option', 'value');
                    if (isset($meta['duration'])) {
                        $duration = $duration + $meta['duration'];
                    }
                }
            }

            return view(getTemplate() . '.view.chanel.chanel', ['chanel' => $chanel, 'follow' => $follow, 'duration' => $duration]);
        }

        abort(404);
    }

    ## Chanel Section

    public function chanelFollow($id)
    {
        $user = (auth()->check()) ? auth()->user() : false;
        if ($user) {
            Follower::create([
                'user_id' => $user->id,
                'type' => 'chanel',
                'follower' => $id
            ]);
        }

        return redirect('/login');
    }

    public function chanelUnFollow($id)
    {
        $user = (auth()->check()) ? auth()->user() : false;
        if ($user) {
            Follower::where('user_id', $user->id)->where('type', 'chanel')->where('follower', $id)->delete();
        }

        return redirect('/login');
    }

    public function page($key)
    {
        //return view(getTemplate() . '.view.blog.'.$key, ['content' => get_option($key, trans('admin.content_not_found'))]);
        return view(getTemplate() . '.view.blog.' . $key);
    }

    ## Page Section ##

    public function product($id)
    {
        //error_reporting(0);

        $user = (auth()->check()) ? auth()->user() : null;

        $content = Content::where('id', $id)->where('mode', 'publish')->first();
        if (empty($content)) {
            return abort(404);
        }

        if ($user) {
            $buy = Sell::where('buyer_id', $user->id)->where('content_id', $id)->count();
        } else {
            $buy = 0;
        }

        $product = $content->withCount(['comments' => function ($q) {
            $q->where('mode', 'publish');
        }])->find($id);

        $hasCertificate = false;
        $canDownloadCertificate = false;

        if ($user) {
            $quizzes = $product->quizzes;
            foreach ($quizzes as $quiz) {
                $canTryAgainQuiz = false;
                $userQuizDone = QuizResult::where('quiz_id', $quiz->id)
                    ->where('student_id', $user->id)
                    ->orderBy('id', 'desc')
                    ->get();

                if (count($userQuizDone)) {
                    $quiz->user_grade = $userQuizDone->first()->user_grade;
                    $quiz->result_status = $userQuizDone->first()->status;
                    $quiz->result = $userQuizDone->first();
                    if ($quiz->result_status == 'pass') {
                        $canDownloadCertificate = true;
                    }
                }

                if (!isset($quiz->attempt) or (count($userQuizDone) < $quiz->attempt and $quiz->result_status !== 'pass')) {
                    $canTryAgainQuiz = true;
                }

                $quiz->can_try = $canTryAgainQuiz;

                if ($quiz->certificate) {
                    $hasCertificate = true;
                }
            }
        }

        if (!$product)
            return abort(404);

        ## Update View
        $product->increment('view');

        //if ($product->price == 0 and $user)
            //$buy = 1;

        $subscribe = false;
        if (isset($buy->tupe) and $buy->type == 'subscribe' and $buy->remain_time - time()) {
            $buy = 0;
            $subscribe = true;
        }

        $meta = arrayToList($product->metas, 'option', 'value');
        $parts = $product->parts->toArray();
        $rates = [];

        ## Get Related Content ##
        $relatedCat = $product->category_id;
        $relatedContent = Content::with(['metas'])
            ->where('category_id', $relatedCat)
            ->where('id', '<>', $product->id)
            ->where('mode', 'publish')
            ->limit(3)
            ->inRandomOrder()
            ->get();


        ## Get PreCourse Content ##
        $preCourseIDs = [];
        $preCousreContent = null;


        if (!cookie('cv' . $id)) {
            $product->increment('view');
            setcookie('cv' . $id, '1');
        }

        $Duration = 0;
        $MB = 0;
        foreach ($parts as $part) {

            if ($content->type == '3' && $part['preview'] == '1') {

                return redirect('/product/part/' . $content->id . '/' . $part['id']);
                break;
            }
            $Duration = $Duration + $part['duration'];
            $MB = $MB + $part['size'];
        }

        ## Live video ##
        if ($buy || (isset($user) && $user->id == $product->user_id)) {
            $live = MeetingDate::where('mode', 'active')->where('content_id', $id)->where('date', date('Y-m-d', time()))->get();
        } else {
            $live = false;
        }

        $profile = User::where('id', $content->user_id)->first();

        $data = [
            'product' => $product,
            'hasCertificate' => $hasCertificate,
            'canDownloadCertificate' => $canDownloadCertificate,
            'meta' => $meta,
            'parts' => $parts,
            'rates' => $rates,
            'buy' => $buy,
            'related' => $relatedContent,
            'precourse' => $preCousreContent,
            'subscribe' => $subscribe,
            'Duration' => $Duration,
            'MB' => $MB,
            'live' => $live,
            'userMetas' => get_all_user_meta($profile->id),
            'profile' => $profile
        ];
        return view(getTemplate() . '.view.product.product', $data);
    }

    ### Product Section ###

    public function productPart($id, $pid)
    {

        //error_reporting(0);
        $user = (auth()->check()) ? auth()->user() : null;

        if (!$user) {
            //return redirect('/product/' . $id)->with('msg', trans('admin.login_to_play_video'));
        }

        $content = Content::where('id', $id)->where('mode', 'publish')->find($id);

        $product = $content->find($id);


        if (!$product)
            return abort(404);

        ## Update View
        $product->increment('view');
        if ($user) {
            $buy = Sell::where('buyer_id', $user->id)->where('content_id', $id)->count();
        } else {
            $buy = 0;
        }

        $meta = arrayToList($product->metas, 'option', 'value');
        $parts = $product->parts->toArray();
        $rates = [];


        $Duration = 0;
        $MB = 0;

        foreach ($parts as $part) {
            $Duration = $Duration + $part['duration'];
            $MB = $MB + $part['size'];
        }


        $Part = ContentPart::find($pid);
        if ($buy || $Part->preview == '1') {
            if ($Part->server == 'vimeo') {
                $vid = $Part->upload_video;
                $vimeo = Vimeo::request($vid, [], 'GET');
                $embed = $vimeo['body']['embed']['html'];
                $embed_exploded = explode(' ', $embed);
                $only_url = str_replace(['src="', '"'], ['', ''], $embed_exploded[1]);
                //$Part->upload_video=$only_url;

                $iframe = '<div style="padding:56.25% 0 0 0;position:relative;"><iframe src="' . $only_url . '" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen style="position:absolute;top:0;left:0;width:100%;height:100%;"></iframe></div><script src="https://player.vimeo.com/api/player.js"></script>';
                $Part->upload_video = $iframe;

            } else {
                $partVideo = '/video/stream/' . $pid;
            }
        } else {
            return redirect('/product/' . $product->id);
        }

        $live = false;
        $profile = User::where('id', $content->user_id)->first();
        $data = [
            'product' => $product,
            'meta' => $meta,
            'parts' => $parts,
            'rates' => $rates,
            'buy' => $buy,
            'precourse' => [],
            'Duration' => $Duration,
            'MB' => $MB,
            'currentPart' => $Part,
            'live' => $live,
            'userMetas' => get_all_user_meta($profile->id),
            'profile' => $profile
        ];

        return view(getTemplate() . '.view.product.product', $data);
    }

    public function productCaptivate($id, $pid)
    {
        session_start();
        error_reporting(0);
        $user = (auth()->check()) ? auth()->user() : null;

        if (!$user) {
            return redirect('/product/' . $id)->with('msg', trans('admin.login_to_play_video'));
        }

        $content = Content::where('id', $id)->where('mode', 'publish')->find($id);
        $buy = Sell::where('buyer_id', $user->id)->where('content_id', $id)->count();
        $product = $content->find($id)->withCount(['comments' => function ($q) {
            $q->where('mode', 'publish');
        }])->with(['discount', 'category' => function ($c) use ($id) {
            $c->with(['discount' => function ($dc) use ($id) {
                $dc->where('off_id', Content::find($id)->category->id);
            }]);
        }, 'rates', 'user' => function ($u) {
            $u->with(['usermetas', 'point', 'contents' => function ($cQuery) {
                $cQuery->where('mode', 'publish')->limit(3);
            }]);
        }, 'metas', 'parts' => function ($query) {
            $query->where('mode', 'publish')->orderBy('sort');
        }, 'favorite' => function ($fquery) use ($user) {
            $fquery->where('user_id', $user->id);
        }, 'comments' => function ($ccquery) use ($id) {
            $ccquery->where('mode', 'publish')->with(['user' => function ($uquery) use ($id) {
                $uquery->with(['category', 'usermetas'])->withCount(['buys' => function ($buysq) use ($id) {
                    $buysq->where('content_id', $id);
                }, 'contents' => function ($contentq) use ($id) {
                    $contentq->where('id', $id);
                }]);
            }, 'childs' => function ($cccquery) use ($id) {
                $cccquery->where('mode', 'publish')->with(['user' => function ($cuquery) use ($id) {
                    $cuquery->with(['category', 'usermetas'])->withCount(['buys' => function ($buysq) use ($id) {
                        $buysq->where('content_id', $id);
                    }, 'contents' => function ($contentq) use ($id) {
                        $contentq->where('id', $id);
                    }]);
                }]);
            }]);
        }, 'supports' => function ($q) use ($user) {
            $q->with(['user.usermetas', 'supporter.usermetas', 'sender.usermetas'])->where('sender_id', $user->id)->where('mode', 'publish')->orderBy('id', 'DESC');
        }])->where(function ($where) {
            $where->where('mode', 'publish');
        })->find($id);

        $Part = ContentPart::find($pid);

        if (!$product || !$Part)
            return abort(404);

        if ($buy == 0 && $content->user_id != $user->id)
            return abort(404);

        ## Ok Se Show Captivate Content
        $UserRoute = md5('prodevelopers' . $user->username);
        $Route = getcwd() . '/captivate/' . $UserRoute . '/content-' . $id . '/' . $pid . '/index.html';
        $Extract = getcwd() . '/captivate/' . $UserRoute . '/content-' . $id . '/' . $pid . '/';
        $Redirect = '/captivate/' . $UserRoute . '/content-' . $id . '/' . $pid . '/index.html';
        unset($_SESSION['user']);
        $_SESSION['user'] = $UserRoute;
        if (file_exists($Route)) {
            return redirect($Redirect);
        } else {
            $zip = new ZipArchive;
            $res = $zip->open(getcwd() . $Part->upload_video);
            if ($res === TRUE) {
                $zip->extractTo($Extract);
                $zip->close();
                return redirect($Redirect);
            }
        }
    }

    public function productFavorite($id)
    {
        $user = (auth()->check()) ? auth()->user() : null;
        if ($user) {
            Favorite::create([
                'content_id' => $id,
                'user_id' => $user->id
            ]);
        }
        return back();
    }

    public function productUnFavorite($id)
    {
        $user = (auth()->check()) ? auth()->user() : null;
        if ($user) {
            Favorite::where('content_id', $id)->where('user_id', $user->id)->delete();
        }
        return back();
    }

    public function productCommentStore($id, Request $request)
    {
        $user = (auth()->check()) ? auth()->user() : null;
        if ($user == null)
            return redirect()->back()->with('msg', trans('admin.login_to_comment'));
        ContentComment::create([
            'comment' => $request->comment,
            'user_id' => $user->id,
            'created_at' => time(),
            'name' => $user->name,
            'content_id' => $id,
            'parent' => $request->parent,
            'mode' => 'draft'
        ]);

        return redirect()->back()->with('msg', trans('admin.comment_success'));
    }

    public function productSupportStore(Request $request)
    {
        $user = (auth()->check()) ? auth()->user() : null;
        if ($user == null) {
            return redirect()->back()->with('msg', trans('admin.login_alert'));
        }
        $id = $request->content_id;

        $buy = Sell::where('buyer_id', $user->id)->where('content_id', $id)->first();

        if (isset($buy) and !empty($buy->user_id)) {
            ContentSupport::create([
                'comment' => $request->comment,
                'user_id' => $user->id,
                'created_at' => time(),
                'name' => $user->name,
                'content_id' => $id,
                'mode' => 'draft',
                'supporter_id' => $buy->user_id,
                'sender_id' => $user->id
            ]);
            return redirect()->back()->with('msg', trans('admin.support_success'));
        } else {
            return redirect()->back()->with('msg', trans('admin.support_failed'));
        }
    }

    public function productSupportRate($id, $rate)
    {
        $user = (auth()->check()) ? auth()->user() : null;
        $support = ContentSupport::where('sender_id', $user->id)->find($id);

        if (!$support) {
            return 0;
        }

        if ($rate > 5 or $rate < 0) {
            return 0;
        }

        ## Update Support Message
        $support->update(['rate' => $rate]);

        ## Update Content Rate
        $avg_rate = ContentSupport::where('content_id', $support->content_id)
            ->whereRaw('user_id=supporter_id')
            ->get()
            ->avg('rate');

        Content::find($support->content_id)->update(['support_rate' => $avg_rate]);

        return 1;
    }

    public function productRate($id, $rate)
    {
        $user = (auth()->check()) ? auth()->user() : null;
        if ($rate > 5 || $rate < 0) {
            return redirect()->back()->with('msg', trans('admin.rate_alert'));
        }
        if ($user == null) {
            return redirect()->back()->with('msg', trans('admin.rate_login'));
        }

        $content = Content::where('id', $id)->with('metas')->first();

        $contentMeta = $content->metas->pluck('value', 'option');
        if (!empty($contentMeta['price']) and $contentMeta['price'] > 0) {

            $buy = Sell::where('buyer_id', $user->id)->where('content_id', $id)->count();

            if ($buy > 0) {
                ContentRate::updateOrCreate(
                    [
                        'content_id' => $id,
                        'user_id' => $user->id
                    ],
                    [
                        'content_id' => $id,
                        'rate' => $rate,
                        'user_id' => $user->id
                    ]
                );
                return redirect()->back()->with('msg', trans('admin.rating_success'));
            } else {
                return redirect()->back()->with('msg', trans('admin.rating_students'));
            }
        } else {
            ContentRate::updateOrCreate(
                [
                    'content_id' => $id,
                    'user_id' => $user->id
                ],
                [
                    'content_id' => $id,
                    'rate' => $rate,
                    'user_id' => $user->id
                ]
            );
            return redirect()->back()->with('msg', trans('admin.rating_success'));
        }
    }

    public function productSubscribe($id, $type, $payMode, Request $request)
    {
        $user = (auth()->check()) ? auth()->user() : null;
        if (empty($user)) {
            return Redirect::to('/user?redirect=/product/' . $id);
        }

        $content = Content::where('mode', 'publish')->with('metas')->find($id);

        if (!$content)
            abort(404);

        if ($content->private == 1)
            $site_income = get_option('site_income_private');
        else
            $site_income = get_option('site_income');

        $meta = arrayToList($content->metas, 'option', 'value');

        if ($type == 3) {
            $remain = 3 * 30 * 86400;
            $Amount = $content->price_3;
        }
        if ($type == 6) {
            $remain = 6 * 30 * 86400;
            $Amount = $content->price_6;
        }
        if ($type == 9) {
            $remain = 9 * 30 * 86400;
            $Amount = $content->price_9;
        }
        if ($type == 12) {
            $remain = 12 * 30 * 86400;
            $Amount = $content->price_12;
        }

        $Amount_pay = pricePay($content->id, $content->category_id, $Amount)['price'];
        if ($payMode == 'paypal') {
            $payer = new Payer();
            $payer->setPaymentMethod('paypal');
            $item_1 = new Item();
            $item_1->setName($content->title)
                ->setCurrency('USD')
                ->setQuantity(1)
                ->setPrice($Amount_pay);
            $item_list = new ItemList();
            $item_list->setItems(array($item_1));
            $amount = new Amount();
            $amount->setCurrency('USD')
                ->setTotal($Amount_pay);
            $transaction = new \PayPal\Api\Transaction();
            $transaction->setAmount($amount)
                ->setItemList($item_list)
                ->setDescription('Purchase Product');
            $redirect_urls = new RedirectUrls();
            $redirect_urls->setReturnUrl(url('/') . '/bank/paypal/status')
                ->setCancelUrl(url('/') . '/bank/paypal/cancel/' . $id);
            $payment = new Payment();
            $payment->setIntent('Sale')
                ->setPayer($payer)
                ->setRedirectUrls($redirect_urls)
                ->setTransactions(array($transaction));
            try {
                $payment->create($this->_api_context);
            } catch (PPConnectionException $ex) {
                if (Config::get('app.debug')) {
                    Session::put('error', 'Connection timeout');
                    return Redirect::route('paywithpaypal');
                } else {
                    Session::put('error', 'Some error occur, sorry for inconvenient');
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
            Session::put('paypal_payment_id', $ids);
            Transaction::insert([
                'buyer_id' => $user['id'],
                'user_id' => $content->user_id,
                'content_id' => $content->id,
                'price' => $Amount_pay,
                'price_content' => $Amount,
                'mode' => 'pending',
                'created_at' => time(),
                'bank' => 'paypal',
                'income' => $Amount_pay - (($site_income / 100) * $Amount_pay),
                'authority' => $ids,
                'type' => 'subscribe',
                'remain_time' => time() + $remain,
                'type' => 'subscribe'
            ]);
            /** add payment ID to session **/
            if (isset($redirect_url)) {
                /** redirect to paypal **/
                return Redirect::away($redirect_url);
            }
            Session::put('error', 'Unknown error occurred');
            return Redirect::route('paywithpaypal');
        }
        if ($payMode == 'credit') {
            if ($user['credit'] - $Amount_pay < 0) {
                return redirect('/product/' . $id)->with('msg', trans('admin.no_charge_error'));
            } else {
                $seller = User::with('category')->find($content->user_id);
                $transaction = Transaction::create([
                    'buyer_id' => $user['id'],
                    'user_id' => $content->user_id,
                    'content_id' => $content->id,
                    'price' => $Amount_pay,
                    'price_content' => $Amount,
                    'mode' => 'deliver',
                    'created_at' => time(),
                    'bank' => 'credit',
                    'authority' => '000',
                    'income' => $Amount_pay - (($site_income / 100) * $Amount_pay),
                    'type' => 'subscribe',
                    'remain_time' => time() + $remain
                ]);
                Sell::insert([
                    'user_id' => $content->user_id,
                    'buyer_id' => $user['id'],
                    'content_id' => $content->id,
                    'type' => 'subscribe',
                    'created_at' => time(),
                    'mode' => 'pay',
                    'transaction_id' => $transaction->id,
                    'remain_time' => time() + $remain
                ]);

                $seller->update(['income' => $seller->income + ((100 - $site_income) / 100) * $Amount_pay]);
                $buyer = User::find($user['id']);
                $buyer->update(['credit' => $user['credit'] - $Amount_pay]);

                Balance::create([
                    'title' => trans('admin.item_purchased') . $content->title,
                    'description' => trans('admin.item_purchased_desc'),
                    'type' => 'minus',
                    'price' => $Amount_pay,
                    'mode' => 'auto',
                    'user_id' => $buyer->id,
                    'exporter_id' => 0,
                    'created_at' => time()
                ]);
                Balance::create([
                    'title' => trans('admin.item_sold') . $content->title,
                    'description' => trans('admin.item_sold_desc'),
                    'type' => 'add',
                    'price' => ((100 - $site_income) / 100) * $Amount_pay,
                    'mode' => 'auto',
                    'user_id' => $seller->id,
                    'exporter_id' => 0,
                    'created_at' => time()
                ]);
                Balance::create([
                    'title' => trans('admin.item_profit') . $content->title,
                    'description' => trans('admin.item_profit_desc') . $buyer->username,
                    'type' => 'add',
                    'price' => ($site_income / 100) * $Amount_pay,
                    'mode' => 'auto',
                    'user_id' => 0,
                    'exporter_id' => 0,
                    'created_at' => time()
                ]);

                ## Notification Center
                $product = Content::find($transaction->content_id);
                sendNotification(0, ['[c.title]' => $product->title], get_option('notification_template_buy_new'), 'user', $buyer->id);
                return redirect()->back()->with('msg', trans('admin.item_purchased_success'));
            }
        }
    }

    ## product Subscribe

    public function articles(Request $request)
    {
        $order = $request->get('order', null);
        $cats = $request->get('cat', null);

        $Articles = Article::where('mode', 'publish');

        if ($cats != null) {
            $Articles->whereIn('cat_id', $cats);
        }

        switch ($order) {
            case 'old':
                $Articles->orderBy('id');
                break;
            case 'new':
                $Articles->orderBy('id', 'DESC');
                break;
            case 'view':
                $Articles->orderBy('view', 'DESC');
                break;
            case 'popular':
                $Articles->orderBy('rate_count', 'DESC');
                break;
            default:
                $Articles->orderBy('id', 'DESC');
        }

        $Category = ContentCategory::where('parent_id', '0')->get();

        $posts = $Articles->withCount('rate')
            ->with(['user', 'rate'])
            ->get();


        $data = [
            'posts' => $posts,
            'category' => $Category,
            'order' => $order,
            'cats' => $cats
        ];

        return view(getTemplate() . '.view.article.list', $data);
    }


    ## Article Section

    public function articleShow($id)
    {

        $post = Article::with(['category', 'user.usermetas'])
            ->where('mode', 'publish')
            ->find($id);


        if (empty($post)) {
            abort(404);
        }

        $rates = [];
        if ($post->user != null) {
            $rates = getRate($post->user->toArray());
        }

        $userContent = Content::where('mode', 'publish')
            ->where('user_id', $post->user_id)
            ->with(['metas'])
            ->limit(4)
            ->inRandomOrder()
            ->get();


        $relContent = Content::where('mode', 'publish')
            ->where('category_id', $post->cat_id)
            ->with(['metas'])
            ->limit(4)
            ->inRandomOrder()
            ->get();


        $post->increment('view');

        $data = [
            'post' => $post,
            'rates' => $rates,
            'userContent' => $userContent,
            'relContent' => $relContent,
            'userMeta' => arrayToList($post->user->usermetas, 'option', 'value')
        ];

        return view(getTemplate() . '.view.article.article', $data);
    }

    public function requests(Request $request)
    {
        $user = (auth()->check()) ? auth()->user() : null;
        $list = Requests::where('mode', '<>', 'draft')->orderBy('id', 'DESC');

        # Mode Filter
        if ($request->get('mode', null) != null) {
            switch ($request->get('mode', null)) {
                case 'all':
                    break;
                case 'publish':
                    $list->where('mode', 'publish');
                    break;
                case 'accept':
                    $list->where('mode', 'accept');
                    break;
                default:
                    break;
            }
        }

        # Category Filter
        if ($request->get('cat', null) != null) {
            $list->whereIn('category_id', $request->get('cat', null));
        }

        # Search Filter
        if ($request->get('q', null) != null) {
            $list->where('title', 'LIKE', '%' . $request->get('q', null) . '%');
        }

        if (isset($user))
            $lists = $list->with(['content', 'category', 'fans' => function ($q) use ($user) {
                $q->where('user_id', $user->id);
            }])->withCount(['fans'])
                ->get();
        else
            $lists = $list->with(['content', 'category', 'fans'])->withCount(['fans'])->get();


        return view(getTemplate() . '.view.request.request', ['list' => $lists]);
    }

    ## Request Section

    public function newRequest()
    {
        $user = (auth()->check()) ? auth()->user() : null;
        if ($user == null)
            return redirect('/login?redirect=/request/new');

        return view(getTemplate() . '.view.request.new');
    }

    public function storeRequest(Request $request)
    {
        $user = (auth()->check()) ? auth()->user() : null;
        if ($user == null) {
            return redirect('/login?redirect=/request/new');
        } else {
            Requests::create([
                'user_id' => 0,
                'requester_id' => $user->id,
                'category_id' => $request->category_id,
                'title' => $request->title,
                'description' => $request->descriptoin,
                'mode' => 'draft',
                'created_at' => time()
            ]);

            ## Notification Center
            sendNotification(0, ['[r.title]' => $request->title], get_option('notification_template_request_get'), 'user', $user->id);

            return redirect()->back()->with('msg', trans('admin.request_sent_approve'));
        }
    }

    public function followRequest($id)
    {
        $user = (auth()->check()) ? auth()->user() : null;
        if ($user != null) {
            RequestFans::create(['user_id' => $user->id, 'request_id' => $id]);
            return redirect()->back();
        } else {
            return redirect()->back()->with('msg', trans('admin.login_alert'));
        }
    }

    public function unFollowRequest($id)
    {
        $user = (auth()->check()) ? auth()->user() : null;
        if ($user != null) {
            RequestFans::where('user_id', $user->id)->where('request_id', $id)->delete();
            return redirect()->back();
        } else {
            return redirect()->back()->with('msg', trans('admin.login_alert'));
        }
    }

    public function suggestionRequest($id, $suggest)
    {
        $user = (auth()->check()) ? auth()->user() : null;
        if ($user == null) {
            return redirect()->back()->with('msg', trans('admin.login_alert'));
        } else {
            $content = Content::where('title', 'LIKE', $suggest)->where('mode', 'publish')->first();

            if ($content != null) {
                RequestSuggestion::create([
                    'user_id' => $user->id,
                    'request_id' => $id,
                    'content_id' => $content->id
                ]);
            }
            return redirect()->back()->with('msg', trans('admin.request_sent_alert'));
        }
    }

    public function records(Request $request)
    {
        $user = (auth()->check()) ? auth()->user() : null;
        $query = Record::where('mode', 'publish');
        # Mode Filter
        if ($request->get('mode', null) != null) {
            switch ($request->get('mode', null)) {
                case 'all':
                    break;
                case 'publish':
                    $query->where('content_id', null);
                    break;
                case 'accept':
                    $query->where('content_id', '<>', '0');
                    break;
                default:
                    break;
            }
        }

        $category_id = $request->get('cat', null);
        # Category Filter
        if ($category_id != null) {
            $query->whereIn('category_id', $category_id);
        }

        # Search Filter
        $search_param = $request->get('q', null);
        if ($search_param != null) {
            $query->where('title', 'LIKE', '%' . $search_param . '%');
        }

        if (isset($user))
            $records = $query->with(['content', 'category', 'fans' => function ($q) use ($user) {
                $q->where('user_id', $user->id);
            }])->withCount(['fans'])->get();
        else
            $records = $query->with(['content', 'category', 'fans'])->withCount(['fans'])->get();


        return view(getTemplate() . '.view.record.record', ['list' => $records]);
    }

    ### Record Section ###

    public function recordFollow($id)
    {
        $user = (auth()->check()) ? auth()->user() : null;
        if ($user != null) {
            RecordFans::create(['user_id' => $user->id, 'record_id' => $id]);
            return redirect()->back();
        } else {
            return redirect()->back()->with('msg', trans('admin.login_alert'));
        }
    }

    public function recordUnFollow($id)
    {
        $user = (auth()->check()) ? auth()->user() : null;
        if ($user != null) {
            RecordFans::where('user_id', $user->id)->where('record_id', $id)->delete();
            return redirect()->back();
        } else {
            return redirect()->back()->with('msg', trans('admin.login_alert'));
        }
    }

    public function videoStream($id)
    {
        $user = (auth()->check()) ? auth()->user() : null;
        if (!$user) {
            return back()->with('msg', trans('admin.login_to_play_video'));
        }

        $part = ContentPart::where('id', $id)
            ->where('mode', 'publish')
            ->with('content')
            ->first();

        if (empty($part)) {
            abort(404);
        }

        if ($part->free == 0 and $user->id != $part->content->user_id) {
            $sellCount = Sell::where('buyer_id', $user->id)
                ->where('content_id', $part->content->id)
                ->count();

            if ($sellCount == 0) {
                abort(404);
            }
        }

        $storagePath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
        $file = $storagePath . 'source/content-' . $part->content->id . '/video/part-' . $part->id . '.mp4';

        if (!file_exists($file)) {
            abort(404);
        }

        $stream = new VideoStream($file);
        $stream->start();

    }

    ## Video Section ##

    public function videoDownload($id)
    {
        $user = (auth()->check()) ? auth()->user() : null;
        if (!$user) {
            return back()->with('msg', 'Please Login First');
        }

        $part = ContentPart::where('id', $id)
            ->where('mode', 'publish')
            ->with('content')
            ->first();

        if (empty($part)) {
            abort(404);
        }

        if ($part->free == 0) {
            $sellCount = Sell::where('buyer_id', $user->id)
                ->where('content_id', $part->content->id)
                ->count();

            if ($sellCount == 0) {
                return back()->with('msg', 'Please Purchase This Course First');
            }
        }

        if ($part->content->download == 0)
            abort(404);

        $storagePath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
        $file = 'source/content-' . $part->content->id . '/video/part-' . $part->id . '.mp4';
        $file1 = 'source/content-' . $part->content->id . '/video/part-' . $part->id . '.pdf';
        $file2 = 'source/content-' . $part->content->id . '/video/part-' . $part->id . '.zip';
        $file3 = 'source/content-' . $part->content->id . '/video/part-' . $part->id . '.docx';
        $file4 = 'source/content-' . $part->content->id . '/video/part-' . $part->id . '.rar';
        $file5 = 'source/content-' . $part->content->id . '/video/part-' . $part->id . '.mp3';

        if (file_exists($storagePath . $file))
            return Response::download($storagePath . $file);
        if (file_exists($storagePath . $file1))
            return Response::download($storagePath . $file1);
        if (file_exists($storagePath . $file2))
            return Response::download($storagePath . $file2);
        if (file_exists($storagePath . $file3))
            return Response::download($storagePath . $file3);
        if (file_exists($storagePath . $file4))
            return Response::download($storagePath . $file4);
        if (file_exists($storagePath . $file5))
            return Response::download($storagePath . $file5);

        return back();

    }

    public function videoProgress()
    {
        $logPath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . 'source/log';
        $content = $logPath . '/block.txt';
        echo $this->progressBar($content);
    }

    public function progressBar($txt)
    {
        $content = @file_get_contents($txt);
        if ($content) {
            //get duration of source
            preg_match("/Duration: (.*?), start:/", $content, $matches);
            if (!isset($matches[1]))
                return 'error';
            $rawDuration = $matches[1];

            //rawDuration is in 00:00:00.00 format. This converts it to seconds.
            $ar = array_reverse(explode(":", $rawDuration));
            $duration = floatval($ar[0]);
            if (!empty($ar[1])) $duration += intval($ar[1]) * 60;
            if (!empty($ar[2])) $duration += intval($ar[2]) * 60 * 60;

            //get the time in the file that is already encoded
            preg_match_all("/time=(.*?) bitrate/", $content, $matches);

            $rawTime = array_pop($matches);

            //this is needed if there is more than one match
            if (is_array($rawTime)) {
                $rawTime = array_pop($rawTime);
            }

            //rawTime is in 00:00:00.00 format. This converts it to seconds.
            $ar = array_reverse(explode(":", $rawTime));
            $time = floatval($ar[0]);
            if (!empty($ar[1])) $time += intval($ar[1]) * 60;
            if (!empty($ar[2])) $time += intval($ar[2]) * 60 * 60;

            //calculate the progress
            $progress = round(($time / $duration) * 100);

            //echo "Duration: " . $duration . "<br>";
            //echo "Current Time: " . $time . "<br>";
            //echo "Progress: " . $progress . "%";

            return $progress;

        }
    }

    public function loginTrack($user)
    {
        $New = Login::create([
            'user_id' => $user,
            'created_at_sh' => time(),
            'updated_at_sh' => time()
        ]);
        return $New;
    }

    ## Login

    public function usageTrack($product_id, $user_id)
    {
        $New = Usage::create([
            'user_id' => $user_id,
            'product_id' => $product_id,
            'created_at_sh' => time(),
            'updated_at_sh' => time()
        ]);
        return $New;
    }

    ## Usage

    public function walletStatus(Request $request)
    {
        if (!isset($request->gateway)) {
            $payment_id = Session::get('paypal_payment_id');
            Session::forget('paypal_payment_id');
            if (empty($request->PayerID) || empty($request->token)) {
                Session::put('error', 'Payment failed');
                return Redirect::route('/');
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
                return redirect('/user/balance/charge')->with('msg', '');
            }
        }

        if (isset($request->gateway) and $request->gateway == 'paytm') {
            $transaction = PaytmWallet::with('receive');
            $Transaction = TransactionCharge::find($transaction->getOrderId());

            if ($transaction->isSuccessful()) {
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
                return redirect('/user/balance/charge')->with('msg', '');
            }
        }
        if (isset($request->gateway) and $request->gateway == 'paystack') {
            $payment = Paystack::getPaymentData();
            if (isset($payment['status']) and $payment['status'] == true) {
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
                return redirect('/user/balance/charge')->with('msg', '');

            }
        }
        if (isset($request->gateway) && $request->gateway == 'razorpay') {
            $razorpay = new Api(env('RAZORPAY_KEY_ID'), env('RAZORPAY_KEY_SECRET'));
            $order = $razorpay->utility->verifyPaymentSignature([
                'razorpay_signature' => $request->razorpay_signature,
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'razorpay_order_id' => $request->razorpay_order_id
            ]);
            if ($order == null) {
                $Transaction = TransactionCharge::where('authority', $request->razorpay_order_id)->first();
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
                return redirect('/user/balance/charge')->with('msg', trans('main.successful'));
            }
        }

        return redirect('/user/balance/charge')->with('msg', 'Error');
    }

    public function paypalStatus(Request $request)
    {
        $payment_id = Session::get('paypal_payment_id');
        Session::forget('paypal_payment_id');
        if (empty($request->PayerID) || empty($request->token)) {
            Session::put('error', 'Payment failed');
            return Redirect::route('/');
        }
        $payment = Payment::get($payment_id, $this->_api_context);
        $execution = new PaymentExecution();
        $execution->setPayerId($request->PayerID);
        $result = $payment->execute($execution, $this->_api_context);
        $transaction = Transaction::where('mode', 'pending')->where('authority', $payment_id)->first();
        if ($result->getState() == 'approved') {
            $product = Content::find($transaction->content_id);
            $userUpdate = User::with('category')->find($transaction->user_id);
            if ($product->private == 1)
                $site_income = get_option('site_income_private') - $userUpdate->category->off;
            else
                $site_income = get_option('site_income') - $userUpdate->category->off;

            if (empty($transaction))
                \redirect('/product/' . $transaction->content_id);

            $Amount = $transaction->price;

            Sell::insert([
                'user_id' => $transaction->user_id,
                'buyer_id' => $transaction->buyer_id,
                'content_id' => $transaction->content_id,
                'type' => $transaction->type,
                'created_at' => time(),
                'mode' => 'pay',
                'transaction_id' => $transaction->id,
                'remain_time' => $transaction->remain_time
            ]);

            $userUpdate->update(['income' => $userUpdate->income + ((100 - $site_income) / 100) * $Amount]);
            Transaction::find($transaction->id)->update(['mode' => 'deliver', 'income' => ((100 - $site_income) / 100) * $Amount]);

            ## Notification Center
            sendNotification(0, ['[c.title]' => $product->title], get_option('notification_template_buy_new'), 'user', $transaction->buyer_id);

            return redirect('/product/' . $transaction->content_id);

        }
        return redirect('/product/' . $transaction->content_id);
    }

    ######################
    ### Bank Section ##
    ######################

    ## Paypal

    public function paypalCancel($id, Request $request)
    {
        return \redirect('/product/' . $id)->with('msg', trans('admin.payment_failed'));
    }

    public function paytmStatus($product_id, Request $request)
    {
        $transaction = PaytmWallet::with('receive');
        $Transaction = Transaction::find($transaction->getOrderId());
        $response = $transaction->response();

        if ($transaction->isSuccessful()) {
            $product = Content::find($Transaction->content_id);
            $userUpdate = User::with('category')->find($Transaction->user_id);
            if ($product->private == 1)
                $site_income = get_option('site_income_private') - $userUpdate->category->off;
            else
                $site_income = get_option('site_income') - $userUpdate->category->off;

            if (empty($transaction))
                \redirect('/product/' . $Transaction->content_id);

            $Amount = $Transaction->price;

            Sell::insert([
                'user_id' => $Transaction->user_id,
                'buyer_id' => $Transaction->buyer_id,
                'content_id' => $Transaction->content_id,
                'type' => $Transaction->type,
                'created_at' => time(),
                'mode' => 'pay',
                'transaction_id' => $Transaction->id,
                'remain_time' => $Transaction->remain_time
            ]);

            $userUpdate->update(['income' => $userUpdate->income + ((100 - $site_income) / 100) * $Amount]);
            Transaction::find($Transaction->id)->update(['mode' => 'deliver', 'income' => ((100 - $site_income) / 100) * $Amount]);

            ## Notification Center
            sendNotification(0, ['[c.title]' => $product->title], get_option('notification_template_buy_new'), 'user', $Transaction->buyer_id);

            return redirect('/product/' . $Transaction->content_id);

        } else if ($transaction->isFailed()) {
            return \redirect('/product/' . $product_id)->with('msg', trans('admin.payment_failed'));
        } else if ($transaction->isOpen()) {
            //Transaction Open/Processing
        }
    }

    ## Paytm

    public function paytmCancel($id, Request $request)
    {
        return \redirect('/product/' . $id)->with('msg', trans('admin.payment_failed'));
    }

    public function payuStatus($product_id, Request $request)
    {
        $Payment = Payment::capture();
        if ($Payment->status == 'Completed') {
            $Transaction = Transaction::where('authority', $Payment->txnid)->first();
            $product = Content::find($Transaction->content_id);
            $userUpdate = User::with('category')->find($Transaction->user_id);
            if ($product->private == 1)
                $site_income = get_option('site_income_private') - $userUpdate->category->off;
            else
                $site_income = get_option('site_income') - $userUpdate->category->off;

            if (empty($transaction))
                \redirect('/product/' . $Transaction->content_id);

            $Amount = $Transaction->price;

            Sell::insert([
                'user_id' => $Transaction->user_id,
                'buyer_id' => $Transaction->buyer_id,
                'content_id' => $Transaction->content_id,
                'type' => $Transaction->type,
                'created_at' => time(),
                'mode' => 'pay',
                'transaction_id' => $Transaction->id,
                'remain_time' => $Transaction->remain_time
            ]);

            $userUpdate->update(['income' => $userUpdate->income + ((100 - $site_income) / 100) * $Amount]);
            Transaction::find($Transaction->id)->update(['mode' => 'deliver', 'income' => ((100 - $site_income) / 100) * $Amount]);

            ## Notification Center
            sendNotification(0, ['[c.title]' => $product->title], get_option('notification_template_buy_new'), 'user', $Transaction->buyer_id);

            return redirect('/product/' . $Transaction->content_id);
        } else {
            return \redirect('/product/' . $product_id)->with('msg', trans('admin.payment_failed'));
        }
    }

    ## Payu

    public function payuCancel($id, Request $request)
    {
        return \redirect('/product/' . $id)->with('msg', trans('admin.payment_failed'));
    }

    public function paystackStatus($product_id, Request $request)
    {
        $payment = Paystack::getPaymentData();
        if (isset($payment['status']) && $payment['status'] == true) {
            $Transaction = Transaction::find($payment['data']['metadata']['transaction']);
            $product = Content::find($Transaction->content_id);
            $userUpdate = User::with('category')->find($Transaction->user_id);
            if ($product->private == 1)
                $site_income = get_option('site_income_private') - $userUpdate->category->off;
            else
                $site_income = get_option('site_income') - $userUpdate->category->off;

            if (empty($transaction))
                \redirect('/product/' . $Transaction->content_id);

            $Amount = $Transaction->price;

            Sell::insert([
                'user_id' => $Transaction->user_id,
                'buyer_id' => $Transaction->buyer_id,
                'content_id' => $Transaction->content_id,
                'type' => $Transaction->type,
                'created_at' => time(),
                'mode' => 'pay',
                'transaction_id' => $Transaction->id,
                'remain_time' => $Transaction->remain_time
            ]);

            $userUpdate->update(['income' => $userUpdate->income + ((100 - $site_income) / 100) * $Amount]);
            Transaction::find($Transaction->id)->update(['mode' => 'deliver', 'income' => ((100 - $site_income) / 100) * $Amount]);

            ## Notification Center
            sendNotification(0, ['[c.title]' => $product->title], get_option('notification_template_buy_new'), 'user', $Transaction->buyer_id);

            return redirect('/product/' . $Transaction->content_id);
        } else {
            return \redirect('/product/' . $product_id)->with('msg', trans('admin.payment_failed'));
        }
    }

    ## PayStack

    public function razorpayStatus($product_id, Request $request)
    {
        $razorpay = new Api(env('RAZORPAY_KEY_ID'), env('RAZORPAY_KEY_SECRET'));
        $order = $razorpay->utility->verifyPaymentSignature([
            'razorpay_signature' => $request->razorpay_signature,
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'razorpay_order_id' => $request->razorpay_order_id
        ]);
        if ($order == null) {
            $Transaction = Transaction::where('authority', $request->razorpay_order_id)->first();
            $product = Content::find($Transaction->content_id);
            $userUpdate = User::with('category')->find($Transaction->user_id);
            if ($product->private == 1)
                $site_income = get_option('site_income_private') - $userUpdate->category->off;
            else
                $site_income = get_option('site_income') - $userUpdate->category->off;

            if (empty($Transaction))
                \redirect('/product/' . $Transaction->content_id);

            $Amount = $Transaction->price;

            Sell::insert([
                'user_id' => $Transaction->user_id,
                'buyer_id' => $Transaction->buyer_id,
                'content_id' => $Transaction->content_id,
                'type' => $Transaction->type,
                'created_at' => time(),
                'mode' => 'pay',
                'transaction_id' => $Transaction->id,
                'remain_time' => $Transaction->remain_time
            ]);

            $userUpdate->update(['income' => $userUpdate->income + ((100 - $site_income) / 100) * $Amount]);
            Transaction::find($Transaction->id)->update(['mode' => 'deliver', 'income' => ((100 - $site_income) / 100) * $Amount]);

            ## Notification Center
            sendNotification(0, ['[c.title]' => $product->title], get_option('notification_template_buy_new'), 'user', $Transaction->buyer_id);

            return redirect('/product/' . $Transaction->content_id);
        } else {
            return \redirect('/product/' . $product_id)->with('msg', trans('admin.payment_failed'));
        }
    }

    ## Razorpay

    public function wecashupCallback(Request $request)
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST');

        $merchant_uid = env('Merchant_UID');
        $merchant_public_key = env('Merchant_Public_Key');
        $merchant_secret = env('Merchant_Secret_Key');
        $transaction_uid = '';
        $transaction_token = '';
        $transaction_provider_name = '';
        $transaction_confirmation_code = '';
        if (isset($_POST['transaction_uid'])) {
            $transaction_uid = $_POST['transaction_uid'];
        }
        if (isset($_POST['transaction_token'])) {
            $transaction_token = $_POST['transaction_token'];
        }
        if (isset($_POST['transaction_provider_name'])) {
            $transaction_provider_name = $_POST['transaction_provider_name'];
        }
        if (isset($_POST['transaction_confirmation_code'])) {
            $transaction_confirmation_code = $_POST['transaction_confirmation_code'];
        }
        $url = 'https://www.wecashup.com/api/v2.0/merchants/' . $merchant_uid . '/transactions/' . $transaction_uid . '?merchant_public_key=' . $merchant_public_key;

        $fields = array(
            'merchant_secret' => urlencode($merchant_secret),
            'transaction_token' => urlencode($transaction_token),
            'transaction_uid' => urlencode($transaction_uid),
            'transaction_confirmation_code' => urlencode($transaction_confirmation_code),
            'transaction_provider_name' => urlencode($transaction_provider_name),
            '_method' => urlencode('PATCH')
        );
        foreach ($fields as $key => $value) {
            $fields_string .= $key . '=' . $value . '&';
        }
        rtrim($fields_string, '&');
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //Step 9  : Retrieving the WeCashUp Response

        $server_output = curl_exec($ch);

        echo $server_output;

        curl_close($ch);

        $data = json_decode($server_output, true);

        if ($data['response_status'] == "success") {

            //Do wathever you want to tell the user that it's transaction succeed or redirect him/her to a success page

            $location = 'https://www.wecashup.cloud/cdn/tests/websites/PHP/responses_pages/success.html';

        } else {

            //Do wathever you want to tell the user that it's transaction failed or redirect him/her to a failure page

            $location = 'https://www.wecashup.cloud/cdn/tests/websites/PHP/responses_pages/failure.html';

        }

        //redirect to your feedback page
        echo '<script>top.window.location = "' . $location . '"</script>';
    }

    ## Wecashup

    public function wecashupHook(Request $request)
    {

        $merchant_secret = env('Merchant_Secret_Key');
        $received_transaction_merchant_secret = null;
        $received_transaction_uid = null;
        $received_transaction_status = null;
        $received_transaction_details = null;
        $received_transaction_token = null;
        $authenticated = 'false';

        if (isset($_POST['merchant_secret'])) {
            $received_transaction_merchant_secret = $_POST['merchant_secret'];
        }

        if (isset($_POST['transaction_uid'])) {
            $received_transaction_uid = $_POST['transaction_uid'];
        }
        if (isset($_POST['transaction_status'])) {
            $received_transaction_status = $_POST['transaction_status'];
        }
        if (isset($_POST['transaction_amount'])) {
            $received_transaction_amount = $_POST['transaction_amount'];
        }

        if (isset($_POST['transaction_receiver_currency'])) {
            $received_transaction_receiver_currency = $_POST['transaction_receiver_currency'];
        }

        if (isset($_POST['transaction_details'])) {
            $received_transaction_details = $_POST['transaction_details'];
        }

        if (isset($_POST['transaction_token'])) {
            $received_transaction_token = $_POST['transaction_token'];
        }

        if (isset($_POST['transaction_type'])) {
            $received_transaction_type = $_POST['transaction_type'];
        }

        echo '<br><br> received_transaction_merchant_secret : ' . $received_transaction_merchant_secret;
        echo '<br><br> received_transaction_uid : ' . $received_transaction_uid;
        echo '<br><br> received_transaction_token : ' . $received_transaction_token;
        echo '<br><br> received_transaction_details : ' . $received_transaction_details;
        echo '<br><br> received_transaction_amount : ' . $received_transaction_amount;
        echo '<br><br> received_transaction_status : ' . $received_transaction_status;
        echo '<br><br> received_transaction_type : ' . $received_transaction_type;

        /***** SAVE THIS IN YOUD DATABASE - start ****************/


        /***** SAVE THIS IN YOUD DATABASE - end ****************/


//Authentication |We make sure that the received data come from a system that knows our secret key (WeCashUp only)
        if ($received_transaction_merchant_secret != null && $received_transaction_merchant_secret == $merchant_secret) {
            //received_transaction_merchant_secret is Valid

            echo '<br><br> merchant_secret [MATCH]';

            //Now check if you have a transaction with the received_transaction_uid and received_transaction_token

            $database_transaction_uid = 'TEST_UID';//************* LOAD FROM YOUR DATABASE ****************
            $database_transaction_token = 'TEST_TOKEN';//************* LOAD FROM YOUR DATABASE ****************

            if ($received_transaction_uid != null && $received_transaction_uid == $database_transaction_uid) {
                //received_transaction_merchant_secret is Valid

                echo '<br><br> transaction_uid [MATCH]';

                if ($received_transaction_token != null && $received_transaction_token == $database_transaction_token) {
                    //received_transaction_token is Valid

                    echo '<br><br> transaction_token [MATCH]';

                    //All the 3 parameters match, so...
                    $authenticated = 'true';
                }
            }
        }

        echo '<br><br>authenticated : ' . $authenticated;

        if ($authenticated == 'true') {

            //Update and process your transaction

            if ($received_transaction_status == "PAID") {
                //Save the transaction status in your database and do whatever you want to tell the user that it's transaction succeed
                echo '<br><br> transaction_status : ' . $transaction_status;

            } else { //Status = FAILED

                //Save the transaction status in your database and do whatever you want to tell the user that it's transaction failed
                echo '<br><br> transaction_status : ' . $transaction_status;
            }

            /***** SAVE THIS IN YOUD DATABASE - start ****************/

            $file = 'transactions.txt';
            $txt = "received_transaction_merchant_secret : " . $received_transaction_merchant_secret . "\n" .
                "received_transaction_uid : " . $received_transaction_uid . "\n" .
                "received_transaction_token : " . $received_transaction_token . "\n" .
                "received_transaction_details : " . $received_transaction_details . "\n" .
                "received_transaction_status : " . $received_transaction_status . "\n" .
                "received_transaction_type : " . $received_transaction_type . "\n";

            $myfile = fopen("newfile.txt", "w") or die("Unable to open file!");
            fwrite($myfile, $txt);
            fclose($myfile);
            /***** SAVE THIS IN YOUD DATABASE - end ****************/

            /*
                NOTE : 	You can analyze each variable in order to process further operations like sending
                        an email to the customer to inform him that his transaction failed or launching the
                        delivery process if the transaction succeed.
            */

        }
    }

    public function cinetpayNotify(Request $request)
    {
        if (isset($request->cpm_trans_id)) {
            $id_transaction = $_POST['cpm_trans_id'];
            $Transaction = Transaction::find($id_transaction);
            $platform = "PROD";
            $version = "V1";
            $CinetPay = new CinetPay(env('CINET_SITE_ID'), env('CINET_API_KEY'), $platform, $version);
            $CinetPay->setTransId($id_transaction)->getPayStatus();
            $cpm_site_id = $CinetPay->_cpm_site_id;
            $signature = $CinetPay->_signature;
            $cpm_amount = $CinetPay->_cpm_amount;
            $cpm_trans_id = $CinetPay->_cpm_trans_id;
            $cpm_custom = $CinetPay->_cpm_custom;
            $cpm_currency = $CinetPay->_cpm_currency;
            $cpm_payid = $CinetPay->_cpm_payid;
            $cpm_payment_date = $CinetPay->_cpm_payment_date;
            $cpm_payment_time = $CinetPay->_cpm_payment_time;
            $cpm_error_message = $CinetPay->_cpm_error_message;
            $payment_method = $CinetPay->_payment_method;
            $cpm_phone_prefixe = $CinetPay->_cpm_phone_prefixe;
            $cel_phone_num = $CinetPay->_cel_phone_num;
            $cpm_ipn_ack = $CinetPay->_cpm_ipn_ack;
            $created_at = $CinetPay->_created_at;
            $updated_at = $CinetPay->_updated_at;
            $cpm_result = $CinetPay->_cpm_result;
            $cpm_trans_status = $CinetPay->_cpm_trans_status;
            $cpm_designation = $CinetPay->_cpm_designation;
            $buyer_name = $CinetPay->_buyer_name;
            if ($cpm_result == '00') {
                if ($Transaction->price == $cpm_amount) {
                    $product = Content::find($Transaction->content_id);
                    $userUpdate = User::with('category')->find($Transaction->user_id);
                    if ($product->private == 1)
                        $site_income = get_option('site_income_private') - $userUpdate->category->off;
                    else
                        $site_income = get_option('site_income') - $userUpdate->category->off;

                    if (empty($Transaction))
                        abort(404);

                    $Amount = $Transaction->price;

                    Sell::insert([
                        'user_id' => $Transaction->user_id,
                        'buyer_id' => $Transaction->buyer_id,
                        'content_id' => $Transaction->content_id,
                        'type' => $Transaction->type,
                        'created_at' => time(),
                        'mode' => 'pay',
                        'transaction_id' => $Transaction->id,
                        'remain_time' => $Transaction->remain_time
                    ]);

                    $userUpdate->update(['income' => $userUpdate->income + ((100 - $site_income) / 100) * $Amount]);
                    Transaction::find($Transaction->id)->update(['mode' => 'deliver', 'income' => ((100 - $site_income) / 100) * $Amount]);

                    ## Notification Center
                    sendNotification(0, ['[c.title]' => $product->title], get_option('notification_template_buy_new'), 'user', $Transaction->buyer_id);
                }
            }
        }
    }

    ## Cintepay

    public function cinetpayReturn(Request $request)
    {
        if (isset($_POST['cpm_trans_id'])) {
            $Transaction = Transaction::find($request->cpm_trans_id);
            if ($Transaction && $Transaction->mode == 'deliver') {
                return redirect('/product/' . $Transaction->content_id);
            } else {
                return redirect('/product/' . $Transaction->content_id)->with('msg', trans('admin.payment_failed'));
            }
        }
    }

    public function cinetpayCancel(Request $request)
    {
        return \redirect('/');
    }

    private function course($array, $mode)
    {
        if ($mode == 'one') {
            foreach ($array as $index => $a) {
                if (!empty($a['parts_count']) and $a['parts_count'] > 1)
                    unset($array[$index]);
            }
        }
        if ($mode == 'multi') {
            foreach ($array as $index => $a) {
                if (!empty($a['parts_count']) and $a['parts_count'] == 1)
                    unset($array[$index]);
            }
        }
        return $array;
    }


    public function localeUpdate(Request $request)
    {
        $locale = $request->locale;

        Cookie::queue(Cookie::forever('lang', $locale));
        $value = $request->cookie('lang');
        return 'ok';

    }

    public function currencyUpdate(Request $request)
    {
        $cur = $request->cur;
        Cookie::queue(Cookie::forever('cur', $cur));
        $value = $request->cookie('cur');
        return 'ok';

    }

    public function faqs()
    {
        $data['student_faqs'] = FaqsModel::where('type', 'student')->orderBy('serial')->get();
        $data['teacher_faqs'] = FaqsModel::where('type', 'teacher')->orderBy('serial')->get();
        return view(getTemplate() . '.view.blog.faqs', $data);
    }

    public function contact(Request $request)
    {
        $name=$request->name;
        $email=$request->email;
        $phone=$request->phone;
        $message=$request->message;
        if($email !='' && $email!= null){
            sendMail([
                'recipient' => ['mymoondevelopment@gmail.com'],
                'template' => get_option('email_notification_template', 0),
                'subject' => 'New message from MyMoon Contact form',
                'title' => $name,
                'content' => $message,
            ]);
            return back()->with('msg', trans('Your message has been sent successfully'));
        }
        return back()->with('msg', trans('Something went wrong. Please try again'));
    }
}
