<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use Config;
use Session;
use View;
use Auth;
use URL;
use Event;
use DB;
use Exception;

use App\Events\LoginSuccessHandler;
use App\Events\LogoutSuccessHandler;

use App\Services\SocialService;
use App\Services\RedisService;
use App\Services\LanguageService;
use App\Services\ProfileService;
use App\Services\PostService;
use App\Services\GroupSeriesService;
use App\Services\CategoryService;
use App\Services\UserService;

use App\Data\Blog\Notification;
use App\Data\Blog\UserBan;
use App\Data\Blog\Post;
use App\Data\Blog\Stock;
use App\Data\Blog\GroupSeries;
use App\Data\System\User;

class UsersController extends BaseController
{
    /**
     * Instantiate a new HomeController instance.
     */
    public function __construct()
    {
        parent::__construct();
        $this->viewData['title'] = 'User';
        // Authentication filter
        $this->middleware('auth', [
            'except' => [
                'getRegister',
                'postSignup',
                'getLogin',
                'postLogin',
                'getConfirm',
                'postConfirm',
                'getPosts',
                'getStock',
                'getShow',
                'getTermsOfService',
                'socialRegistration',
                'getConfirmWorkEmail',
                'resendActiveEmail',
            ]
        ]);
        $this->middleware('authority:add-edit-delete,privilege', [
            'only' => [
                'getStatistic',
                'getView',
                'postDestroy',
                'postRestore',
                'postActive',
                'postUnBan',
                'postBan',
                'changeUserDefaultPostLanguage',
            ]
        ]);
    }

    /**
     * Default user page
     * Route /user/index
     * @return response
     */
    public function getIndex()
    {
        $this->viewData['title'] = trans('messages.user.index_title');
        return View::make('user.index', $this->viewData);
    }

    /**
     * Signup page
     * Route /user/signup
     * @return response
     */
    public function getRegister()
    {
        if (Auth::check()) {
            return Redirect::action('HomeController@getTopPage');
        }
        $this->viewData['title'] = trans('messages.user.signup_title');
        return View::make('user.signup', $this->viewData);
    }

    /**
     * Create a new user registration
     * Route /user/signup
     * @return redirect
     */
    public function postSignup(Request $request)
    {
        $result = array();
        $result['success'] = false;
        $input = $request->all();
        if (isset($input['tos'])) {
            unset($input['tos']);
        }
        $validator = Validator::make($input, User::$createRules, User::$messagesCreate);
        $validator->setAttributeNames([
            'name' => trans('labels.name'),
            'username' => trans('labels.username'),
            'email' => trans('labels.email'),
            'password' => trans('labels.password_2'),
            'password_confirmation' => trans('labels.confirm_pwd'),
        ]);

        if ($validator->fails()) {
            $result['messages'] = $validator->messages();
            return Response::json($result);
        }
        $input['lang'] = $this->lang;
        $signupResult = UserService::signup($input, true);
        if ($signupResult) {
            $result = [
                'success' => true,
                'url' => URL::action('UsersController@getLogin'),
            ];
            Session::flash('message', trans('messages.user.signup_success'));
        }
        return Response::json($result);

    }

    /**
     * Login page
     * Route /user/login
     * @return response
     */
    public function getLogin()
    {
        if (Auth::check()) {
            return Redirect::action('HomeController@getTopPage')->with('success', 'Account actived successfully!');
        }
        initReturnUrl();
        $this->viewData['title'] = trans('messages.user.login_title');

        return View::make('user.login', $this->viewData);
    }

    /**
     * Authenticate with a username and password
     * Route /user/login
     * @return redirect
     */
    public function postLogin(Request $request)
    {
        $input = $request->except('_token');
        if (isset($input['return'])) {
            $request->session()->put('returnUrl', $input['return']);
        }

        $field = filter_var($input['username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $input[$field] = isset($input['username']) && $input['username'] != null ? $input['username'] : '';

        $validator = Validator::make($input, User::authRules($field), User::$messagesCreate);
        $validator->setAttributeNames([
            'username' => trans('labels.username_or_email'),
            'password' => trans('labels.password_2')
        ]);
        $loginResult = UserService::login($input, $field, $validator);
        if ($loginResult['success']) {
            Event::fire(LoginSuccessHandler::EVENT_NAME, Auth::user());
            $this->isAdmin();
        }
        return response()->json($loginResult);
    }

    /**
     * Logout user
     * Route /user/logout
     * @return redirect
     */
    public function getLogout()
    {
        SocialService::logoutSocial();
        RedisService::revokeUser($this->currentUser->id);
        Auth::logout();
        Event::fire(LogoutSuccessHandler::EVENT_NAME, $this->currentUser);
        Session::forget('isAdmin');
        return Redirect::to('/'. $this->lang);
    }
    
    /**
     * Show active user screen or active user
     * Route /user/active/:token
     * @return response
     */
    public function getConfirm($id = null, $activeToken = null, $newUser = false)
    {
        if (is_null($id) && is_null($activeToken)) {
            $this->viewData['title'] = trans('messages.user.confirm_title');
            return View::make('user.confirm', $this->viewData);
        }
        if (UserService::confirm($id, $activeToken, $newUser)) {
            if ($newUser) {
                $this->viewData['newUser'] = $newUser;
                return View::make('user.registration_success', $this->viewData);
            }
            return Redirect::action('UsersController@getLogin')
                ->with('success', 'Account actived successfully!');
        }
        return Redirect::action('UsersController@getConfirm')
            ->with('message_error', 'Unable to activate!');
    }

    /**
     * Active user
     * Route /user/active
     * @return redirect
     */
    public function postConfirm(Request $request)
    {
        $input = $request->only('email');
        $validator = Validator::make($input, User::$emailRule);
        $validator->setAttributeNames(['email' => trans('labels.email')]);
        if ($validator->fails()) {
            return Redirect::back()
                ->withInput($input)
                ->withErrors($validator);
        }
        if (UserService::sendConfirmEmail($input['email'])) {
            return Redirect::action('UsersController@getConfirm')
                ->with('message', trans('messages.user.email_confirm_sent'));
        }
        return Redirect::action('UsersController@getConfirm')
            ->with('message_error', trans('messages.user.invalid_confirm'));
    }

    /**
     * Update user information
     * Route /user/update
     * @return redirect
     */
    public function postUpdate(Request $request)
    {
        $input = $request->only('name', 'phone');
        $validator = Validator::make($input, User::$updateInfoRule);
        if ($validator->fails()) {
            return Redirect::back()
                ->withInput($input)
                ->withErrors($validator);
        }
        $id = Auth::id();
        $updateUserInfo = UserService::updateUserInfo($id, $input);
        return Redirect::back()
            ->with('message', $updateUserInfo['message']);
    }

    /**
     * Change password page
     * Route /user/change-password
     * @return response
     */
    public function getChangePassword()
    {
        $this->viewData['title'] = trans('titles.change_password');
        return View::make('user.change_password', $this->viewData);
    }

    /**
     * Update password of user
     * Route /user/change-password
     * @return redirect
     */
    public function postChangePassword(Request $request)
    {
        $input = $request->only('current_password', 'new_password', 'new_password_confirmation');
        $validator = Validator::make($input, User::$changePasswordRule);
        if ($validator->fails()) {
            return Redirect::back()
                ->withInput($input)
                ->withErrors($validator);
        }
        $id = Auth::id();
        $changePassword = UserService::changePassword($id, $input, true);
        return Redirect::back()
            ->with('message', $changePassword['message']);
    }

    /**
     * Default user page
     * Route /user/show
     * @return response
     */
    public function getShow(Request $request, $username)
    {
        $user = User::with('profile', 'skills', 'socials')->where('username', $username)->first();
        if ($request->ajax()) {
            $pageCount = $request->get('pageCount');
            $offset = $pageCount * UserService::POSTS_PER_PAGE;
            $publishedPosts = Post::with('categories', 'user')
                ->where('user_id', $user->id)
                ->whereNotNull('published_at')
                ->whereNotNull('encrypted_id')
                ->orderBy('published_at', 'desc');
            if (!Auth::check() || !$user->isCurrent()) {
                $publishedPosts = PostService::filterByGroupSecret($publishedPosts);
            }

            $publishedPosts = $publishedPosts->take(UserService::POSTS_PER_PAGE)->skip($offset)->get();

            $html = View::make('post._list_posts_user', ['posts' => $publishedPosts, 'lang' => $this->lang])->render();
            $seeMore = trans('labels.load_more');

            $hideSeeMore = false;
            if ($publishedPosts->count() < UserService::POSTS_PER_PAGE) {
                $hideSeeMore = true;
            }
            return Response::json(['html' => $html, 'msg' => $seeMore, 'hideSeeMore' => $hideSeeMore]);
        } else {

            if (!$user) {
                return Response::view('errors.usernotfound', $this->viewData, 404);
            }
            Notification::checkReferenceLink($request->all());
            $this->viewData['title'] = trans('titles.profile_page', ['name' => $user->name]);
            $this->viewData['description'] = isset($user->profile()->first()->occupation) ? $user->profile()->first()->occupation : '';
            $this->viewData['siteImage'] = user_img_url($user, 300);
            $this->viewData['user'] = $user;
            $this->viewData['categories'] = UserService::postTendency($user);
            $this->viewData['socialList'] = UserService::getSocialListLink($user);
            $this->viewData['profile'] = UserService::getProfile($user);
            $this->viewData['setting'] = UserService::getSetting($user);
            $this->viewData['langAddress'] = Auth::check() ?
                ($user->isCurrent() ? null : $this->currentUser->setting()->first()->lang) :
                LanguageService::getSystemLang();

            $publishedPosts = Post::with('categories', 'user')
                ->where('user_id', $user->id)
                ->whereNotNull('published_at')
                ->whereNotNull('encrypted_id')
                ->orderBy('published_at', 'desc');
            //get public series                    
            $publishedSeries = GroupSeries::with('group', 'user')
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc');
            if (!Auth::check() || !$user->isCurrent()) {
                $publishedPosts = PostService::filterByGroupSecret($publishedPosts);
                $publishedSeries = GroupSeriesService::filterByGroupSecret($publishedSeries);
            }
            $publishedPosts = $publishedPosts->take(UserService::POSTS_PER_PAGE)->get();
            $publishedSeries = $publishedSeries->take(UserService::POSTS_PER_PAGE)->get();
            //merge post and series;
            $postAndSeries = $publishedPosts->merge($publishedSeries);
            // sort by update_at descending`
            $postAndSeries = $postAndSeries->sortByDesc(function ($postAndSeries) {
                return $postAndSeries->created_at;
            });
            $this->viewData['publishedPosts'] = $postAndSeries;
            $hideSeeMore = false;
            if ($publishedPosts->count() < UserService::POSTS_PER_PAGE) {
                $hideSeeMore = true;
            }
            $this->viewData['hideSeeMore'] = $hideSeeMore;
            $this->viewData['lang'] = $this->lang;
            $skills = DB::table('users')
                ->join('user_skills', 'users.id', '=', 'user_skills.user_id')
                ->join('skills', 'skills.id', '=', 'user_skills.skill_id')
                ->join('skill_categories', 'skills.skill_category_id', '=', 'skill_categories.id')
                ->where('users.id', '=', $user->id)
                ->select('skill_categories.name as category', 'skills.name as skill', 'user_skills.year as year')
                ->get();
            $this->viewData['userSkills'] = $skills;
            return View::make('user.show', $this->viewData);
        }
    }

    public function getFollowing(Request $request, $username)
    {
        $user = User::with('profile', 'setting')->where('username', $username)->first();
        if ($request->ajax()) {
            $pageCount = $request->get('pageCount');
            $offset = $pageCount * UserService::FOLLOW_USER_PER_PAGE;
            $following = $user->following()->with('followed.profile', 'followed.avatar')
                ->orderBy('created_at', 'desc')
                ->take(UserService::FOLLOW_USER_PER_PAGE)
                ->skip($offset)
                ->get();
            $html = View::make('user._list_users_following', ['users' => $following, 'currentUser' => $this->currentUser])->render();
            $seeMore = trans('labels.load_more');
            $hideSeeMore = false;
            if ($following->count() < UserService::FOLLOW_USER_PER_PAGE) {
                $hideSeeMore = true;
            }
            return Response::json(['html' => $html, 'msg' => $seeMore, 'hideSeeMore' => $hideSeeMore]);
        } else {
            if (!$user) {
                return Response::view('errors.usernotfound', $this->viewData, 404);
            }

            $this->viewData['title'] = trans('titles.user_following', ['name' => $user->name]);
            $this->viewData['userNavigation'] = trans('messages.user.following_nav');
            $this->viewData['setting'] = UserService::getSetting($user);
            $this->viewData['user'] = $user;
            $this->viewData['profile'] = UserService::getProfile($user);
            $this->viewData['langAddress'] = Auth::check() ?
                ($user->isCurrent() ? null : $this->currentUser->setting()->first()->lang) :
                LanguageService::getSystemLang();

            $following = $user->following()->with('followed.profile', 'followed.avatar')
                ->orderBy('created_at', 'desc')
                ->take(UserService::FOLLOW_USER_PER_PAGE)
                ->get();
            $this->viewData['following'] = $following;
            $hideSeeMore = false;
            if ($following->count() < UserService::FOLLOW_USER_PER_PAGE) {
                $hideSeeMore = true;
            }
            $this->viewData['hideSeeMore'] = $hideSeeMore;
            return View::make('user.following', $this->viewData);
        }
    }

    public function getFollowers(Request $request, $username)
    {
        $user = User::with('setting', 'profile')->where('username', $username)->first();
        if ($request->ajax()) {
            $pageCount = $request->get('pageCount');
            $offset = $pageCount * UserService::FOLLOW_USER_PER_PAGE;
            $followers = $user->followers()->with('follower.profile', 'follower.avatar')
                ->orderBy('created_at', 'desc')
                ->take(UserService::FOLLOW_USER_PER_PAGE)
                ->skip($offset)
                ->get();
            $html = View::make('user._list_users_follower', ['users' => $followers, 'currentUser' => $this->currentUser])->render();
            $seeMore = trans('labels.load_more');
            $hideSeeMore = false;
            if ($followers->count() < UserService::FOLLOW_USER_PER_PAGE) {
                $hideSeeMore = true;
            }
            return Response::json(['html' => $html, 'msg' => $seeMore, 'hideSeeMore' => $hideSeeMore]);
        } else {
            if (!$user) {
                return Response::view('errors.usernotfound', $this->viewData, 404);
            }

            $this->viewData['title'] = trans('titles.user_followers', ['name' => $user->name]);
            $this->viewData['userNavigation'] = trans('messages.user.followers_nav');
            $this->viewData['setting'] = UserService::getSetting($user);
            $this->viewData['user'] = $user;
            $this->viewData['profile'] = UserService::getProfile($user);
            $this->viewData['langAddress'] = Auth::check() ?
                ($user->isCurrent() ? null : $this->currentUser->setting()->first()->lang) :
                LanguageService::getSystemLang();

            $followers = $user->followers()->with('follower.profile', 'follower.avatar')
                ->orderBy('created_at', 'desc')
                ->take(UserService::FOLLOW_USER_PER_PAGE)
                ->get();
            $this->viewData['followers'] = $followers;
            $hideSeeMore = false;
            if ($followers->count() < UserService::FOLLOW_USER_PER_PAGE) {
                $hideSeeMore = true;
            }
            $this->viewData['hideSeeMore'] = $hideSeeMore;
            return View::make('user.followers', $this->viewData);
        }
    }

    public function getFollowingCategories(Request $request, $username)
    {
        $user = User::with('profile', 'setting')->where('username', $username)->first();
        //get total row of category follow by other
        $countFollowingCategories = $user->followingCategories()->count();
        if ($request->ajax()) {
            $pageCount = $request->get('pageCount');
            $offset = $pageCount * CategoryService::CATEGORIES_PER_PAGE;
            $categories = $user->followingCategories()->take(CategoryService::CATEGORIES_PER_PAGE)->skip($offset)->get();
            $html = View::make('categories._list_categories', ['categories' => $categories, 'currentUser' => $this->currentUser])->render();
            $seeMore = trans('labels.load_more');
            $hideSeeMore = false;
            if ($countFollowingCategories <= ($offset + CategoryService::CATEGORIES_PER_PAGE)) {
                $hideSeeMore = true;
            }
            return Response::json(['html' => $html, 'msg' => $seeMore, 'hideSeeMore' => $hideSeeMore]);
        } else {
            if (!$user) {
                return Response::view('errors.usernotfound', $this->viewData, 404);
            }

            $this->viewData['title'] = trans('titles.user_following_categories', ['name' => $user->name]);
            $this->viewData['userNavigation'] = trans('messages.user.following_categories_nav');
            $this->viewData['setting'] = UserService::getSetting($user);
            $this->viewData['user'] = $user;
            $this->viewData['profile'] = UserService::getProfile($user);
            $this->viewData['langAddress'] = Auth::check() ?
                ($user->isCurrent() ? null : $this->currentUser->setting->lang) :
                LanguageService::getSystemLang();
            $this->viewData['categories'] = $user->followingCategories()->take(CategoryService::CATEGORIES_PER_PAGE)->get();
            $hideSeeMore = false;
            if ($countFollowingCategories <= CategoryService::CATEGORIES_PER_PAGE) {
                $hideSeeMore = true;
            }
            $this->viewData['hideSeeMore'] = $hideSeeMore;
            return View::make('user.following_categories', $this->viewData);
        }
    }

    public function getStock(Request $request, $username)
    {
        $user = User::where('username', $username)->first();
        if ($request->ajax()) {
            $pageCount = $request->get('pageCount');
            $offset = $pageCount * UserService::POSTS_PER_PAGE;

            $stockedPosts = Stock::where('user_id', $user->id)
                ->orderBy('stocks.created_at', 'desc')
                ->lists('post_id')
                ->toArray();

            $posts = Post::with('categories', 'user', 'user.profile')
                ->whereIn('id', $stockedPosts)
                ->orderByRaw(DB::raw('FIELD(id, ' . implode(',', $stockedPosts) . ')'));
            $posts = PostService::filterByGroupSecretNotInUser($posts, Auth::user());
            if (!Auth::check() || !$user->isCurrent()) {
                $posts = PostService::filterByGroupSecret($posts);
            }

            $posts = $posts->take(UserService::POSTS_PER_PAGE)
                ->skip($offset)
                ->get();

            $html = View::make('post._list_posts_user', ['posts' => $posts, 'lang' => $this->lang])->render();
            $seeMore = trans('labels.load_more');
            $hideSeeMore = false;
            if ($posts->count() < UserService::POSTS_PER_PAGE) {
                $hideSeeMore = true;
            }
            return Response::json(['html' => $html, 'msg' => $seeMore, 'hideSeeMore' => $hideSeeMore]);
        } else {
            if (!$user) {
                return Response::view('errors.usernotfound', $this->viewData, 404);
            }

            $this->viewData['title'] = trans('titles.user_clipped_posts', ['name' => $user->name]);
            $this->viewData['userNavigation'] = trans('messages.user.user_stocked_nav');
            $this->viewData['setting'] = UserService::getSetting($user);
            $this->viewData['user'] = $user;
            $this->viewData['profile'] = UserService::getProfile($user);
            $this->viewData['langAddress'] = Auth::check() ?
                ($user->isCurrent() ? null : $this->currentUser->setting->lang) :
                LanguageService::getSystemLang();

            $stockedPosts = Stock::where('user_id', $user->id)
                ->orderBy('stocks.created_at', 'desc')
                ->lists('post_id')
                ->toArray();

            $posts = Post::with('categories', 'user', 'user.profile')
                ->whereIn('id', $stockedPosts);

            if (!empty($stockedPosts)) {
                $posts = $posts->orderByRaw(DB::raw('FIELD(id, ' . implode(',', $stockedPosts) . ')'));
            }

            $posts = $posts->take(UserService::POSTS_PER_PAGE);

            $posts = PostService::filterByGroupSecretNotInUser($posts, Auth::user());

            if (!Auth::check() || !$user->isCurrent()) {
                $posts = PostService::filterByGroupSecret($posts);
            }

            $this->viewData['stockedPost'] = $posts->paginate(UserService::POSTS_PER_PAGE);
            $hideSeeMore = false;
            if ($posts->count() < UserService::POSTS_PER_PAGE) {
                $hideSeeMore = true;
            }
            $this->viewData['hideSeeMore'] = $hideSeeMore;
            return View::make('user.stock', $this->viewData);
        }
    }

    public function getDraft($username)
    {
        $user = User::with('profile')->where('username', $username)->first();
        if (!$user || !UserService::checkCurrentUser($this->currentUser, $username)) {
            return Response::view('errors.usernotfound', $this->viewData, 404);
        }

        $this->viewData['title'] = trans('titles.user_drafts', ['name' => $user->name]);
        $this->viewData['userNavigation'] = trans('messages.user.user_stocked_nav');
        $this->viewData['setting'] = UserService::getSetting($user);
        $this->viewData['user'] = $user;
        $this->viewData['profile'] = UserService::getProfile($user);
        $this->viewData['langAddress'] = Auth::check() ?
            ($user->isCurrent() ? null : $this->currentUser->setting->lang) :
            LanguageService::getSystemLang();

        $drafts = Post::with('categories', 'user')
            ->where('user_id', $user->id)
            ->whereNull('published_at')
            ->orderBy('updated_at', 'DESC')
            ->get();
        $this->viewData['draftPosts'] = $drafts;
        return View::make('user.draft', $this->viewData);
    }

    public function getView(Request $request)
    {
        if ($request->has('name')) {
            $name = $request->get('name');
            $users = UserService::searchByName($name);
        } else {
            $users = User::withTrashed()
                ->with('ban', 'setting')
                ->orderBy('created_at', 'desc')
                ->paginate(UserService::ADMIN_USERS_PER_PAGE);
        }

        $this->viewData['title'] = trans('messages.user.manage_title');
        $this->viewData['users'] = $users;
        $this->viewData['languages'] = Config::get('detect_language.code');

        return View::make('user.view', $this->viewData);
    }

    public function postDestroy($id)
    {
        list($message, $error) = UserService::delete($id);
        $response = [
            'message' => $message,
            'error' => $error,
        ];
        return Response::json($response, 200);
    }

    public function postBanUser(Request $request, $id)
    {
        $input = $request->all();
        $user = User::find((int)$input['user_id']);
        $input['admin_id'] = $this->currentUser->id;
        $result = UserService::banUser($input, $user, $id);
        return Response::json($result);

    }

    public function postUnBan($id)
    {
        $user = User::find($id);
        $message = trans('messages.error');
        if (!$user) {
            $message = trans('messages.user.not_exist', ['item' => $id]);
            $result = [$message, true];
            return Response::json($result, 200);
        }
        $ban = UserBan::where('user_id', $user->id)->first();
        if (!$ban || $ban->delete()) {
            $message = trans('messages.user.has_unban', ['item' => $id]);
            $result = [$message, false];
            return Response::json($result, 200);
        }
        $result = [$message, true];
        return Response::json($result, 200);
    }

    public function postActive($id, $activeToken)
    {
        $message = UserService::confirm($id, $activeToken);
        return Response::json($message, 200);
    }

    public function getTermsOfService($lang = 'en')
    {
        $this->viewData['title'] = trans('titles.terms_of_service');
        $this->viewData['lang'] = $lang;
        return View::make('user.terms_en', $this->viewData);
    }

    public function getStatistic(Request $request)
    {
        $weeks = empty($request->get('weeks')) ? UserService::FIRST_HALF : $request->get('weeks');
        $month = empty($request->get('month')) ? date('m') : $request->get('month');
        $year = empty($request->get('year')) ? date('Y') : $request->get('year');
        $filter = empty($request->get('filter')) ? UserService::FILTER_ALL : $request->get('filter');
        $filterOptions = UserService::getFilterOptions();
        $optionsTime = [
            'weeks' => $weeks,
            'month' => $month,
            'year' => $year,
            'daysInMonth' => cal_days_in_month(CAL_GREGORIAN, $month, $year),
        ];
        $this->viewData['statisticByWeek'] = UserService::statisticByWeek($weeks, $year, $filter);
        $this->viewData['statisticInMonth'] = UserService::statisticInMonth($month, $year, $filter);
        $this->viewData['statisticAll'] = UserService::statisticAll($filter);
        $this->viewData['weeksOptions'] = UserService::getWeeksOptions();
        $this->viewData['optionsTime'] = $optionsTime;
        $this->viewData['filter'] = $filter;
        $this->viewData['filterOptions'] = $filterOptions;
        $this->viewData['title'] = trans('messages.user.manage_title');

        return View::make('user.statistic', $this->viewData);
    }

    public function socialRegistration()
    {
        if (Auth::check()) {
            $this->viewData['returnUrl'] = URL::action('HomeController@getTopPage');
        }
        $this->viewData['isSetDefaultLang'] = 1;
        $this->viewData['title'] = trans('messages.user.login_title');
        return View::make('user.registration_success', $this->viewData);
    }

    public function getConfirmWorkEmail($id = null, $activeToken = null)
    {
        $this->viewData['title'] = trans('messages.user.confirm_title');
        $error = false;

        if (is_null($id) || is_null($activeToken) || !UserService::confirmWorkEmail($id, $activeToken)) {
            $error = true;
        }

        $this->viewData['error'] = $error;

        return View::make('user.confirm_work_email', $this->viewData);
    }

    public function changeUserDefaultPostLanguage(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->all();
            $result = array();
            $result['success'] = false;
            $user = User::find((int)$input['userId']);

            if ($user) {
                $updateSetting = $user->setting->update([
                    'default_post_language' => $input['language']
                ]);
                if ($updateSetting) {
                    $result['success'] = true;
                }
            }

            return Response::json($result, 200);
        }
    }

    public function resendActiveEmail(Request $request)
    {
        if ($request->ajax()) {
            $input = $request->all();
            $field = filter_var($input['username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            $result = [
                'message' => trans('messages.error'),
                'success' => false,
            ];

            if ($field == 'email') {
                try {
                    $lang = $this->lang;
                    if (isset($input['flug']) && $input['flug'] == 'email') {
                        $user = User::where('email', $input['username'])
                            ->whereNotNull('active_token')->first();
                    } else {
                        $user = User::with('setting')
                            ->where('users.work_email', $input['username'])
                            ->whereNotNull('users.active_work_email_token')->first();
                        if ($user->setting()->count() > 0) {
                            $systemLang = $user->setting()->get();
                            $lang = $systemLang[0]->lang;
                        }
                    }

                    if ($user) {
                        $workEmail = false;
                        if (isset($input['flug']) && $input['flug'] != 'email') {
                            $workEmail = true;
                        }
                        ProfileService::sendMailConfirm($user, $lang, $workEmail);
                        $result = [
                            'message' => trans('messages.user.email_confirm_sent'),
                            'success' => true,
                        ];
                    }
                } catch (Exception $e) {
                    $result = [
                        'message' => trans('messages.user.email_confirm_sent'),
                        'success' => false,
                    ];
                }
            }

            return Response::json($result, 200);
        }
    }
}
