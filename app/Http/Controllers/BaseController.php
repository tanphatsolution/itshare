<?php namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Cache;
use View;
use Redirect;
use Config;
use App;
use Auth;
use Request;
use Session;
use App\Facades\Authority;
use App\Data\Blog\Category;
use App\Data\Blog\GroupSetting;
use App\Services\LanguageService;
use App\Services\MonthlyThemeService;
use App\Services\RedisService;
use App\Services\GroupService;
use App\Services\GroupUserService;
use App\Data\Faq\Question;

class BaseController extends Controller
{

    protected $viewData;

    /**
     * @var App\Data\System\User
     */
    protected $currentUser;

    /**
     * @var App\Data\Blog\Setting
     */
    protected $lang;

    protected $categories;
    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout()
    {
        if (!is_null($this->layout)) {
            $this->layout = View::make($this->layout);
        }
    }

    public function __construct()
    {
        $this->currentUser = Auth::user();
        $isSetDefaultLang = 1;

        if (!empty($this->currentUser)) {
            if (!empty($this->currentUser->ban()->select(['id'])->first())) {
                return Redirect::action('UsersController@getLogout');
            }

            $userSetting = $this->currentUser->setting()->first();
            if ($userSetting->post_language_setting_flag == 0 || $userSetting->toppage_language_setting_flag == 0) {
                $isSetDefaultLang = 0;
            }
            $userJoinGroups = $this->getGroupUserJoin($this->currentUser);
        } else {
            Auth::logout();
            $userJoinGroups = null;
        }

        $this->lang = !empty($userSetting) && isset($userSetting->lang) ? $userSetting->lang : LanguageService::getSystemLang();
        App::setLocale($this->lang);

        $categories = $this->getPostCategories();
        $this->viewData = [
            'currentUser' => $this->currentUser,
            'userJoinGroups' => $userJoinGroups,
            'notificationsCount' => RedisService::getNotificationsCount($this->currentUser),
            'lang' => $this->lang,
            'appName' => Config::get('app.app_name'),
            'currentMonthThemeSubject' => MonthlyThemeService::getCurrentMonthThemeSubject(),
            'fixedHeaderOnScroll' => true,
            'isSetDefaultLang' => $isSetDefaultLang,
        ];

        if (Request::segment(1) == 'faq') {
            $this->getQuestionData();
        } else {
            $this->categories = $categories;
        }
    }

    public function prepareParamsForGroupLayout($group)
    {
        // Categories in Group
        $groupCategories = GroupService::getAllGroupCategories($group->id);

        // Unapproved Post in Group
        $unapprovedPosts = GroupService::getUnapprovedPosts($group->id);

        // Current user role in Group
        $groupUser = GroupUserService::getCurrentUserRole($group->id);

        // Members in Group
        $userMembers = GroupUserService::getGroupMembers($group->id);

        // Group Setting
        $groupSettings = GroupService::getGroupSettings($group->id);

        // Group Unapproved Users
        $unapprovedUsers = GroupUserService::getUnapprovedUsers($group->id);

        if ($groupSettings->privacy_flag == GroupSetting::PRIVACY_PUBLIC) {
            $privacyGroup = trans('labels.groups.delete_group_public_message');
            $leaveGroupMessage = trans('labels.groups.leave_group_message');
            $privacy = trans('labels.groups.public');
        } else if ($groupSettings->privacy_flag == GroupSetting::PRIVACY_PROTECTED) {
            $privacyGroup = trans('labels.groups.delete_group_none_public_message');
            $leaveGroupMessage = trans('labels.groups.leave_group_private_message');
            $privacy = trans('labels.groups.closed');
        } else {
            $privacyGroup = trans('labels.groups.delete_group_secret_message');
            $leaveGroupMessage = trans('labels.groups.leave_group_private_message');
            $privacy = trans('labels.groups.secret');
        }

        $this->viewData = array_merge($this->viewData, compact(
                'group',
                'groupCategories',
                'unapprovedPosts',
                'unapprovedUsers',
                'groupUser',
                'userMembers',
                'groupSettings',
                'privacyGroup',
                'privacy',
                'leaveGroupMessage'
            )
        );
    }

    /**
     * get post categories
     * @return mixed
     */
    protected function getPostCategories()
    {
        $cache_key = Config::get('app.app_name', 'viblo') . '_post_Categories';
        $limit = !empty($this->currentUser) ? 15 : 28;
        if (Cache::has($cache_key)) {
            return Cache::get($cache_key);
        }
        $categories = Category::filterLanguageInCategory($limit)->get();
        Cache::put($cache_key, $categories, 20);
        return $categories;
    }

    /**
     * get data of q&a pages
     *
     */
    protected function getQuestionData()
    {
        $this->viewData['latest_question'] = Question::getLatestQuestion(Question::LIMIT_LATEST_QUESTION);
        $this->viewData['users_ranking'] = App\Services\UserRankingService::getUserRanking();
    }

    protected function getGroupUserJoin($user)
    {
        $cache_key = 'user_' . $user->id . '_join_group';
        if (Cache::has($cache_key)) {
            return Cache::get($cache_key);
        }
        $groups = $user->groups()->select(['group_users.created_at'])->get();
        Cache::put($cache_key, $groups, 60);
        return $groups;
    }

    protected function isAdmin()
    {
        Session::put('isAdmin', Authority::hasRole('admin'));
    }
}