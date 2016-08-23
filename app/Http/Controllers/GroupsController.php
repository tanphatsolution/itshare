<?php namespace App\Http\Controllers;

use App\Data\Blog\GroupSeries;
use App\Data\Blog\Post;
use App\Events\GroupPostNotificationHandler;
use App\Services\GroupPostService;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Support\Facades\Response;
use App\Data\Blog\Group;
use App\Services\GroupService;
use App\Data\Blog\GroupPost;
use Illuminate\Support\Facades\Request;
use App\Services\GroupUserService;
use App\Data\Blog\GroupUser;
use Illuminate\Support\Facades\Event;
use App\Events\GroupApproveMemberNotificationHandler;
use App\Events\GroupAddMemberNotificationHandler;
use App\Events\LogJoinGroupByUserHandler;
use App\Data\Blog\Notification;
use App\Data\System\User;
use App\Data\Blog\GroupSetting;
use Illuminate\Support\Facades\Redirect;
use View;
use Input;
use Exception;
use Auth;
use DB;

class GroupsController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth', ['only' => ['create', 'update']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        if (Request::ajax()) {
            $pageCount = Input::get('pageCount');
            $groups = GroupService::getGroups($this->currentUser, $pageCount);
            $this->viewData['groups'] = $groups['groups'];
            $this->viewData['ajax'] = true;
            $html = View::make('groups._list_groups', $this->viewData)->render();
            return Response::json(['html' => $html, 'seeMore' => $groups['seeMore']]);
        }

        $groups = GroupService::getGroups($this->currentUser);
        $this->viewData['groups'] = $groups['groups'];
        $this->viewData['seeMore'] = $groups['seeMore'];

        $userJoinedGroups = GroupService::getAllGroupsOfCurrentUser();
        $this->viewData['userJoinedGroups'] = $userJoinedGroups;
        $seeMoreUserGroup = true;

        if ($userJoinedGroups->count() < GroupService::GROUPS_SIDEBAR_LIMIT) {
            $seeMoreUserGroup = false;
        }

        if (!is_null($userJoinedGroups)) {
            foreach ($userJoinedGroups as $key => $userJoinGroup) {
                $groupContentCount = GroupService::getGroupContentCount($userJoinGroup->id);
                $userJoinedGroups[$key]['countPost'] = $groupContentCount->total_posts + $groupContentCount->total_series + $groupContentCount->total_wiki;
                $userJoinedGroups[$key]['countUser'] = $userJoinGroup->groupUsers()->count();
            }
        }

        $this->viewData['title'] = trans('titles.group');
        $this->viewData['seeMoreUserGroup'] = $seeMoreUserGroup;
        $this->viewData['lang'] = $this->lang;
        return view('groups.index', $this->viewData);
    }


    /**
     * Show the form for creating a new resource.
     * @param  $copyUsersFromEncryptedIdGroup
     * @return Response
     */
    public function create($copyUsersFromEncryptedIdGroup = null)
    {
        if ($copyUsersFromEncryptedIdGroup != null) {
            $copyFromGroup = Group::where('encrypted_id', $copyUsersFromEncryptedIdGroup)->first();
            if ($copyFromGroup) {
                $groupUser = GroupUserService::getCurrentUserRole($copyFromGroup->id);
                if (!$groupUser->isMember()) {
                    return Response::view('errors.404', $this->viewData, 404);
                }
                $copyUserMembers = GroupUserService::getGroupMembers($copyFromGroup->id);
                $this->viewData['userMembers'] = $copyUserMembers;
            }
        }
        $this->viewData['lang'] = $this->lang;
        $this->viewData['title'] = trans('titles.group_create');
        return View::make('groups.create', $this->viewData);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return redirect
     */
    public function store()
    {
        $input = Input::all();
        $errors = GroupService::validate($input);
        if (empty($errors)) {
            $group = GroupService::create($input);
            if (is_null($group)) {
                $this->viewData['title'] = trans('titles.group_create');
                $errors['not_save'] = trans('messages.group.not_save');
                return Redirect::action('GroupsController@create', $this->viewData)->withErrors($errors);
            }

            return Redirect::action('GroupsController@show', [$group->shortname, GroupService::GROUP_FILTER_ALL]);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  string $short_name
     * @param  string $filter
     * @return view
     */
    public function show($short_name, $filter = 'all')
    {
        $group = Group::findByShortname($short_name);
        if (empty($group)) {
            return Response::view('errors.404', $this->viewData, 404);
        }
        $group_setting = $group->groupSetting()->first();
        if (!isset($group_setting->privacy_flag) && $group_setting->privacy_flag == GroupSetting::PRIVACY_PRIVATE && !$group->haveMemberIs($this->currentUser)) {
            return Response::view('errors.404', $this->viewData, 404);
        }

        if (!empty($group->description)) {
            $this->viewData['groupDescription'] = Markdown::convertToHtml(markdown_escape($group->description));
        }

        $title = trans('titles.group_detail_page', ['name' => $group->name]);

        $groupContents = GroupService::getGroupContents($group->id, $filter);
        $hideSeeMore = GroupService::$pageCount;

        $groupContentCount = GroupService::getGroupContentCount($group->id);
        $this->viewData['totalContentCount'] = $groupContentCount->total_posts + $groupContentCount->total_series + $groupContentCount->total_wiki;

        $this->prepareParamsForGroupLayout($group);

        return view(
            'groups.show',
            array_merge(
                $this->viewData, compact(
                    'groupContents',
                    'hideSeeMore',
                    'groupContentCount',
                    'filter',
                    'title'
                )
            )
        );
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $encryptedId
     * @return Response
     */
    public function edit($encryptedId)
    {
        if (!$encryptedId) {
            return Response::view('errors.404', $this->viewData, 404);
        }
        $group = Group::findByEncryptedId($encryptedId);
        if (!$group) {
            $group = Group::findByShortname($encryptedId);
            if (!$group) {
                return Response::view('errors.404', $this->viewData, 404);
            }
        }

        $groupUser = GroupUser::where('group_id', $group->id)
            ->where('user_id', $this->currentUser->id)
            ->first();

        $groupSetting = $group->groupSetting()->first();

        if (isset($groupSetting->privacy_flag) && $groupSetting->privacy_flag == GroupSetting::PRIVACY_PUBLIC) {
            $privacyGroup = trans('labels.groups.delete_group_public_message');
        } else if (isset($groupSetting->privacy_flag) && $groupSetting->privacy_flag == GroupSetting::PRIVACY_PROTECTED) {
            $privacyGroup = trans('labels.groups.delete_group_none_public_message');
        } else {
            $privacyGroup = trans('labels.groups.delete_group_secret_message');
        }

        if ((!is_null($groupUser) && !$groupUser->isAdmin()) || is_null($groupUser)) {
            return Response::view('errors.404', $this->viewData, 404);
        }

        if (!empty($group->description)) {
            $this->viewData['groupDescription'] = Markdown::convertToHtml(markdown_escape($group->description));
        }
        $this->viewData['userMembers'] = GroupUserService::getGroupMembers($group->id, null, true);
        $this->viewData['privacyGroup'] = $privacyGroup;
        $this->viewData['group'] = $group;
        $this->viewData['groupSetting'] = $groupSetting;
        $this->viewData['title'] = trans('titles.edit_group');
        $this->viewData['lang'] = $this->lang;

        return View::make('groups.edit', $this->viewData);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int $encryptedId
     * @return Response
     */
    public function update($encryptedId)
    {
        $input = Input::all();
        $errors = [];
        if (!$encryptedId) {
            return Response::view('errors.404', $this->viewData, 404);
        }
        $group = Group::findByEncryptedId($encryptedId);
        if (!$group) {
            $group = Group::findByShortname($encryptedId);
            if (!$group) {
                return Response::view('errors.404', $this->viewData, 404);
            }
        }
        $groupUser = GroupUser::where('group_id', $group->id)
            ->where('user_id', $this->currentUser->id)
            ->first();
        if (!$groupUser->isAdmin()) {
            return Response::view('errors.404', $this->viewData, 404);
        }

        $errorUpdateGroup = GroupService::update($group, $input);
        if (!$errorUpdateGroup) {
            $errors['not_update'] = trans('messages.group.not_update');
            return Redirect::action('GroupsController@edit', [$encryptedId])->withErrors($errors);
        }

        return Redirect::route('getGroupFillter', [$group->shortname, GroupService::GROUP_FILTER_ALL])
            ->with('success', trans('messages.group.save_success'));
    }

    public static function checkInput()
    {
        if (Request::ajax()) {
            $input = Input::all();
            $errors = GroupService::validate($input);
            $notice = '';
            $error = false;
            if (!empty($errors)) {
                $error = true;
                foreach ($errors as $message) {
                    $notice .= $message . ". \n";
                }
            }

            return Response::json([
                'error' => $error,
                'notice' => $notice,
            ], 200);
        }
    }

    public static function checkShortname()
    {
        if (Request::ajax()) {
            $input = Input::all();
            if (empty(Group::findByShortname(GroupService::convertShortName($input['shortname'])))) {
                return Response::json(true, 200);
            }
            return Response::json(false, 200);
        }
    }

    public static function addMember()
    {
        if (Request::ajax()) {
            $input = Input::all();
            $listOnlyFlag = Input::get('listOnlyFlag', false);

            DB::beginTransaction();

            try {
                $groupId = $input['groupId'];
                $userId = $input['userId'];

                $group = Group::where('id', $groupId)->first();
                $currentUserRole = GroupUserService::getUserRole($group->id, Auth::user()->id);

                if (!$currentUserRole->isMember()) {
                    return Response::json([
                        'html' => '',
                        'error' => true,
                        'message' => 'Not member of Group.'
                    ], 200);
                }

                $groupSettings = GroupService::getGroupSettings($group->id);
                $newUserStatus = GroupUser::STATUS_MEMBER;

                if ($groupSettings->isMemberCanAddMember()) {
                    if ($groupSettings->isMemberCanAddMemberWithPermission()
                        && $currentUserRole->isMember() && !$currentUserRole->isAdmin()
                    ) {
                        $newUserStatus = GroupUser::STATUS_WAITING_APPROVE;
                    }
                } else {
                    if (!$currentUserRole->isAdmin()) {
                        return Response::json([
                            'html' => '',
                            'error' => true,
                            'message' => trans('messages.group.not_admin_permission')
                        ], 200);
                    }
                }

                $member = GroupUser::firstOrCreate([
                    'group_id' => $groupId,
                    'user_id' => $userId,
                    'role' => GroupUser::ROLE_MEMBER,
                    'status' => $newUserStatus
                ]);

                $html = '';

                if (!$listOnlyFlag) {
                    $html = View::make('groups._group_list_members', ['userMembers' => GroupUserService::getGroupMembers($group->id, null, true)])->render();
                } else {
                    $totalGroupMembers = GroupUserService::countGroupMembers($group->id);

                    if ($totalGroupMembers <= GroupUserService::MEMBER_LIST_LIMIT) {
                        $userMembers = GroupUserService::getGroupMembers($group->id);

                        foreach ($userMembers as $userMember) {
                            $html .= View::make('groups._a_member_detail', ['member' => $userMember]);
                        }
                    }
                }

                DB::commit();

                if ($newUserStatus == GroupUser::STATUS_MEMBER) {
                    Event::fire(GroupAddMemberNotificationHandler::EVENT_NAME, $member);
                } elseif ($newUserStatus == GroupUser::STATUS_WAITING_APPROVE) {
                    Event::fire(GroupApproveMemberNotificationHandler::EVENT_NAME, $member);
                }

                return Response::json([
                    'html' => $html,
                    'error' => false,
                    'message' => $newUserStatus == GroupUser::STATUS_WAITING_APPROVE ? trans('messages.group.waiting_admin_permission') : ''
                ], 200);
            } catch (Exception $e) {
                DB::rollback();

                return Response::json([
                    'html' => '',
                    'error' => true,
                    'message' => $e->getMessage()
                ], 200);
            }
        }
    }

    public static function removeMember()
    {
        if (Request::ajax()) {
            $input = Input::all();
            $result = [];
            $groupId = (int)$input['groupId'];
            $group = Group::find($groupId);
            DB::beginTransaction();
            try {
                $groupUser = GroupUser::where('group_users.group_id', $group->id)
                    ->where('group_users.user_id', $input['userId'])
                    ->first();

                $groupSetting = GroupSetting::where('group_id', $group->id)->first();

                if ($groupUser->role == GroupUser::ROLE_MEMBER) {
                    $leaveGroupFlag = true;
                } else {
                    $groupAdmins = GroupUser::where('group_id', $group->id)
                        ->whereBetween('role', [GroupUser::ROLE_ADMIN, GroupUser::ROLE_OWNER])
                        ->get();
                    $leaveGroupFlag = $groupAdmins->count() > 1 ? true : false;
                }

                if ($leaveGroupFlag) {
                    if ($groupSetting->privacy_flag == GroupSetting::PRIVACY_PUBLIC) {
                        $groupUser->delete();
                    } else {
                        if ((int)$groupUser->role !== GroupUser::ROLE_OWNER) {
                            $groupAdmin = GroupUser::where('group_id', $group->id)
                                ->where('role', GroupUser::ROLE_OWNER)
                                ->first();
                        }

                        if (!isset($groupAdmin) && empty($groupAdmin)) {
                            $groupAdmin = GroupUser::where('group_id', $group->id)
                                ->where('role', GroupUser::ROLE_ADMIN)
                                ->where('user_id', '<>', $input['userId'])
                                ->first();
                        }

                        $groupUserPosts = GroupPost::join('posts', 'posts.id', '=', 'group_posts.post_id')
                            ->where('group_posts.group_id', $group->id)
                            ->where('posts.user_id', $input['userId'])
                            ->get();

                        $groupUserSeries = GroupSeries::where('group_id', $group->id)
                            ->where('user_id', $input['userId'])
                            ->get();

                        foreach ($groupUserPosts as $groupUserPost) {
                            /*
                             * @var \Illuminate\Database\Eloquent\Model $post
                             */
                            $post = Post::find($groupUserPost->id);
                            $post->user_id = $groupAdmin->user_id;
                            $post->save();
                        }

                        foreach ($groupUserSeries as $groupUserSeri) {
                            $groupUserSeri->user_id = $groupAdmin->user_id;
                            $groupUserSeri->save();
                        }

                        $groupUser->delete();
                    }
                }
                DB::commit();
                if ($leaveGroupFlag) {
                    $result['data']['isPublic'] = !empty($group->groupSetting()->first()) ? $group->groupSetting()->first()->isPublic() : true;
                    $result['message'] = trans('labels.groups.leave_group_success_message');
                } else {
                    $result['message'] = trans('labels.groups.leave_group_fail_message');
                    $result['error'] = true;
                }
            } catch (Exception $e) {
                DB::rollBack();
                $result['message'] = $e->getMessage();
                $result['error'] = true;
                return Response::json($result);
            }
            return Response::json($result);
        }
    }

    public static function getUsersList()
    {
        if (Request::ajax()) {
            $input = Input::all();
            $userInGroup = GroupUser::where('group_id', $input['groupId'])->lists('user_id');

            $keyword = $input['username'];
            $usersArray = User::where(function ($query) use ($keyword) {
                return $query->where('username', 'like', '%' . $keyword . '%')
                    ->orWhere('email', 'like', '%' . $keyword . '%')
                    ->orWhere('name', 'like', '%' . $keyword . '%');
            })
                ->whereNotIn('id', $userInGroup)
                ->get();
            $users = [];
            foreach ($usersArray as $user) {
                $users[] = ['label' => htmlentities($user->name), 'id' => $user->id, 'avatar' => user_img_url($user, 100)];
            }

            return Response::json($users);
        }
    }

    public static function editGroupByClick()
    {
        if (Request::ajax()) {
            $input = Input::all();
            $error = false;
            $dataField = $input['name'];
            $groupId = (int)$input['pk'];
            $notice = '';
            $content = '';
            $isShortname = true;
            DB::beginTransaction();
            if ($dataField == 'group-name') {
                if (empty(trim($input['value'])) || empty(str_replace('　', '', trim($input['value'])))) {
                    $error = true;
                    $notice = trans('messages.group.empty_group_name');
                } else {
                    $group = Group::where('id', $groupId)->update(['name' => $input['value']]);
                }
                $content = $input['value'];
            } elseif ($dataField == 'description') {
                $group = Group::where('id', $groupId)->update(['description' => $input['value']]);
            } elseif ($dataField == 'url') {
                $group = Group::where('id', $groupId)->update(['url' => $input['value']]);
            } elseif ($dataField == 'group-shortname') {
                if (trim($input['value']) != null) {
                    if (!preg_match('/^[_\-.a-zA-Z0-9]+$/', str_replace('　', '', trim($input['value'])))) {
                        $error = true;
                        $notice = trans('messages.group.invalid_group_shortname');
                    } elseif (!empty(Group::findByShortnameNotInId(GroupService::convertShortName($input['value']), $groupId))) {
                        $error = true;
                        $notice = trans('messages.group.duplicate_group_shortname');
                    }
                    $content = GroupService::convertShortName($input['value']);
                } else {
                    $isShortname = false;
                    $content = Group::find($groupId)->shortname;
                }
            }
            if (isset($group) && $group) {
                if ($dataField == 'description') {
                    $content = Markdown::convertToHtml(markdown_escape($input['value'])) . '<img src="' . asset('img/icon-edit-group.png') . '" alt="edit" class="edit-description">';
                }
                DB::commit();
            } else {
                DB::rollback();
                $error = true;
            }

            return Response::json(['error' => $error, 'notice' => $notice, 'content' => $content, 'isShortname' => $isShortname], 200);
        }
    }

    public static function changeRole()
    {
        if (Request::ajax()) {
            $input = Input::all();
            $error = false;

            DB::beginTransaction();
            $member = GroupUser::where('group_id', $input['groupId'])
                ->where('user_id', $input['userId'])
                ->update(['role' => $input['role']]);
            if ($member) {
                DB::commit();
            } else {
                DB::rollback();
                $error = true;
            }

            return Response::json(['error' => $error], 200);
        }
    }

    public static function checkGroupPrivacy()
    {
        if (Request::ajax()) {
            $input = Input::all();
            $error = true;
            $groupSetting = GroupSetting::where('group_id', $input['groupId'])->first();
            $groupPrivacy = '';
            if ($groupSetting) {
                $error = false;
                switch ($groupSetting->privacy_flag) {
                    case GroupSetting::PRIVACY_PUBLIC:
                        $groupPrivacy = 'public';
                        break;
                    case GroupSetting::PRIVACY_PROTECTED:
                        $groupPrivacy = 'protected';
                        break;
                    case GroupSetting::PRIVACY_PRIVATE:
                        $groupPrivacy = 'private';
                        break;
                    default:
                        $groupPrivacy = 'public';
                        break;
                }
            }
            return Response::json([
                'error' => $error,
                'privacy' => $groupPrivacy
            ], 200);
        }
    }

    public function getRemoteGroupContents()
    {
        if (Request::ajax()) {
            $groupId = Input::get('groupId', 0);
            $pageCount = Input::get('pageCount', 0);
            $filter = Input::get('filter', GroupService::GROUP_FILTER_ALL);

            $group = Group::where('id', $groupId)->first();
            $groupContents = GroupService::getGroupContents($groupId, $filter, $pageCount);

            $seeMore = trans('labels.load_more');
            $hideSeeMore = $groupContents->count() < GroupService::GROUP_CONTENT_LIMIT;

            $html = View::make('groups._a_contents', ['groupContents' => $groupContents, 'group' => $group, 'lang' => $this->lang])->render();
            return Response::json(['views' => $html, 'msg' => $seeMore, 'hideSeeMore' => $hideSeeMore]);
        }

        $this->viewData['title'] = trans('categories.error.404');
        return Response::view('errors.404', $this->viewData, 404);
    }

    public function approvePost()
    {
        $type = 'approve';
        return $this->denyOrApprovePost($type);
    }

    public function denyPost()
    {
        $type = 'deny';
        return $this->denyOrApprovePost($type);
    }

    /**
     * @param string $type
     */
    public function denyOrApprovePost($type)
    {
        $errorResponse = [
            'error' => true,
            'data' => [],
            'message' => trans('messages.group.error'),
        ];

        if (Request::ajax()) {
            $groupEncryptedId = Input::get('groupEncryptedId', null);
            $postEncryptedId = Input::get('postEncryptedId', null);

            // Check Group
            $group = GroupUserService::checkAdminPermissionByEncryptedId($groupEncryptedId);

            if (empty($group)) {
                $errorResponse['message'] = trans('messages.group.not_exist_group');
                return Response::json($errorResponse);
            }

            // Check Post
            $post = Post::findByEncryptedId($postEncryptedId);

            if (empty($post)) {
                $errorResponse['message'] = trans('messages.group.not_exist_post');
                return Response::json($errorResponse);
            }

            // Wrap update in transaction
            DB::beginTransaction();
            try {
                if ($type == 'deny') {
                    GroupPostService::deny($group->id, $post->id);
                } else {
                    GroupPostService::approve($group->id, $post->id);
                    $groupPost = GroupPost::where('post_id', $post->id)
                        ->where('group_id', $group->id)
                        ->first();
                    Event::fire(GroupPostNotificationHandler::EVENT_NAME, $groupPost);
                }

                DB::commit();

                $unapprovedPosts = GroupService::getUnapprovedPosts($group->id);

                return Response::json([
                    'error' => false,
                    'data' => View::make('groups._a_unapproved_post', [
                        'unapprovedPosts' => $unapprovedPosts,
                        'group' => $group,
                        'isAdmin' => true
                    ])->render(),
                    'message' => ''
                ]);
            } catch (Exception $e) {
                DB::rollBack();

                $errorResponse['message'] = $e->getMessage();
                return Response::json($errorResponse);
            }
        }

        $this->viewData['title'] = trans('categories.error.404');
        return Response::view('errors.404', $this->viewData, 404);
    }

    public function getGroupMembers()
    {
        if (Request::ajax()) {
            $userMembers = GroupUserService::getGroupMembers(Input::get('groupId', 0), null, true);
            if ($userMembers) {
                $modal = View::make('modals.group_users', ['userMembers' => $userMembers])->render();

                return Response::json(['result' => true, 'modal' => $modal], 200);
            }

            return Response::json(['result' => false], 200);
        }
    }

    public function join()
    {
        if (Request::ajax()) {
            $errorResponse = [
                'error' => true,
                'data' => [],
                'message' => trans('messages.error')
            ];

            try {
                $groupId = Input::get('groupId', 0);
                $joinFlag = Input::get('joinFlag', null);
                $userId = $this->currentUser->id;

                DB::beginTransaction();

                $groupUserRequest = GroupUserService::getUserRole($groupId, $userId);

                if ($joinFlag) {
                    // Check request before
                    if (empty($groupUserRequest)) {
                        $groupUserRequest = GroupUser::firstOrCreate([
                            'group_id' => $groupId,
                            'user_id' => $userId,
                            'status' => GroupUser::STATUS_WAITING_APPROVE
                        ]);
                        $message = trans('messages.group.request_made_success');

                        Event::fire(GroupApproveMemberNotificationHandler::EVENT_NAME, $groupUserRequest);
                        Event::fire(LogJoinGroupByUserHandler::EVENT_NAME, $groupUserRequest);
                    } else {
                        return Response::json($errorResponse);
                    }
                } else {
                    if (!empty($groupUserRequest) && $groupUserRequest->isWaiting()) {
                        $groupUserRequest->delete();
                        Notification::where('sender_id', $groupUserRequest->user_id)
                            ->where('group_id', $groupId)
                            ->delete();

                        $message = trans('messages.group.cancel_request_success');
                    } else {
                        return Response::json($errorResponse);
                    }
                }

                DB::commit();

                return Response::json([
                    'error' => false,
                    'data' => $groupUserRequest,
                    'message' => $message
                ]);
            } catch (Exception $e) {
                DB::rollBack();

                $errorResponse['message'] = $e->getMessage();
                return Response::json($errorResponse);
            }
        }

        $this->viewData['title'] = trans('categories.error.404');
        return Response::view('errors.404', $this->viewData, 404);
    }

    public function approveUser()
    {
        $errorResponse = [
            'error' => true,
            'data' => [],
            'message' => trans('messages.error')
        ];

        if (Request::ajax()) {
            // Wrap update in transaction
            DB::beginTransaction();
            try {
                $groupId = Input::get('groupId', null);
                $userId = Input::get('userId', null);
                $approveFlag = Input::get('approveFlag', null);

                // Check Group
                $group = GroupUserService::checkAdminPermissionById($groupId);

                if (empty($group)) {
                    $errorResponse['message'] = trans('messages.notification.no_permission');
                    return Response::json($errorResponse);
                }

                // Approve User
                GroupUserService::approve($group->id, $userId, $approveFlag);

                $unapprovedUsers = GroupUserService::getUnapprovedUsers($group->id);

                DB::commit();

                if ($approveFlag) {
                    $member = GroupUser::where('user_id', $userId)
                        ->where('group_id', $groupId)->first();
                    Event::fire(GroupAddMemberNotificationHandler::EVENT_NAME, $member);
                }

                return Response::json([
                    'error' => false,
                    'data' => View::make('groups._a_unapproved_user', [
                        'unapprovedUsers' => $unapprovedUsers,
                        'group' => $group
                    ])->render(),
                    'message' => ''
                ]);
            } catch (Exception $e) {
                DB::rollBack();

                $errorResponse['message'] = $e->getMessage();
                return Response::json($errorResponse);
            }

        }

        $this->viewData['title'] = trans('categories.error.404');
        return Response::view('errors.404', $this->viewData, 404);
    }

    /*
     * @var \Illuminate\Database\Eloquent\Model $post
     */
    public function leaveGroup($groupEncryptedId)
    {
        $result = [
            'error' => false,
            'data' => '',
            'message' => ''
        ];

        if (Request::ajax()) {
            $group = Group::findByEncryptedId($groupEncryptedId);
            DB::beginTransaction();
            try {
                $groupUser = GroupUser::where('group_users.group_id', $group->id)
                    ->where('group_users.user_id', $this->currentUser->id)
                    ->first();
                $groupSetting = GroupSetting::where('group_id', $group->id)->first();
                if ($groupUser->role == GroupUser::ROLE_MEMBER) {
                    $leaveGroupFlag = true;
                } else {
                    $groupAdmins = GroupUser::where('group_id', $group->id)
                        ->whereBetween('role', [GroupUser::ROLE_ADMIN, GroupUser::ROLE_OWNER])
                        ->get();
                    $leaveGroupFlag = $groupAdmins->count() > 1 ? true : false;
                }
                if ($leaveGroupFlag) {
                    if ($groupSetting->privacy_flag == GroupSetting::PRIVACY_PUBLIC) {
                        $groupUser->delete();
                    } else {
                        if ((int)$groupUser->role !== GroupUser::ROLE_OWNER) {
                            $groupAdmin = GroupUser::where('group_id', $group->id)
                                ->where('role', GroupUser::ROLE_OWNER)
                                ->first();
                        }
                        if (!isset($groupAdmin) && empty($groupAdmin)) {
                            $groupAdmin = GroupUser::where('group_id', $group->id)
                                ->where('role', GroupUser::ROLE_ADMIN)
                                ->where('user_id', '<>', $this->currentUser->id)
                                ->first();
                        }
                        $groupUserPosts = GroupPost::join('posts', 'posts.id', '=', 'group_posts.post_id')
                            ->where('group_posts.group_id', $group->id)
                            ->where('posts.user_id', $this->currentUser->id)
                            ->get();
                        $groupUserSeries = GroupSeries::where('group_id', $group->id)
                            ->where('user_id', $this->currentUser->id)
                            ->get();
                        foreach ($groupUserPosts as $groupUserPost) {
                            /*
                             * @var \Illuminate\Database\Eloquent\Model $post
                             */
                            $post = Post::find($groupUserPost->id);
                            $post->user_id = $groupAdmin->user_id;
                            $post->save();
                        }
                        foreach ($groupUserSeries as $groupUserSeri) {
                            $groupUserSeri->user_id = $groupAdmin->user_id;
                            $groupUserSeri->save();
                        }
                        $groupUser->delete();
                    }
                }
                DB::commit();
                if ($leaveGroupFlag) {
                    $result['data']['isPublic'] = $group->groupSetting()->first()->isPublic() ? true : false;
                    $result['message'] = trans('labels.groups.leave_group_success_message');
                } else {
                    $result['message'] = trans('labels.groups.leave_group_fail_message');
                    $result['error'] = true;
                }
            } catch (Exception $e) {
                DB::rollBack();
                $result['message'] = $e->getMessage();
                $result['error'] = true;
                return Response::json($result);
            }
        }
        return Response::json($result);
    }

    public function deleteGroup($groupEncryptedId)
    {
        $result = [
            'error' => false,
            'data' => '',
            'message' => ''
        ];

        if (Request::ajax()) {
            $group = Group::findByEncryptedId($groupEncryptedId);
            DB::beginTransaction();
            try {
                $groupSetting = GroupSetting::where('group_id', $group->id)->first();
                if ($groupSetting->privacy_flag == GroupSetting::PRIVACY_PROTECTED || $groupSetting->privacy_flag == GroupSetting::PRIVACY_PRIVATE) {
                    $groupPosts = GroupPost::where('group_id', $group->id)->get();
                    foreach ($groupPosts as $groupPost) {
                        Post::where('id', $groupPost->post_id)->delete();
                    }
                }
                GroupSetting::where('group_id', $group->id)->delete();
                GroupPost::where('group_id', $group->id)->delete();
                GroupSeries::where('group_id', $group->id)->delete();
                GroupUser::where('group_id', $group->id)->delete();
                Group::where('id', $group->id)->delete();
                DB::commit();
                $result['message'] = trans('labels.groups.delete_group_success_message');
            } catch (Exception $e) {
                DB::rollBack();
                $result['message'] = $e->getMessage();
                $result['error'] = true;
                return Response::json($result);
            }
        }
        return Response::json($result);
    }

    public function getMemberGroupInSeries()
    {
        if (Request::ajax()) {
            $groupId = Input::get('groupId');
            $groupUsers = GroupUserService::getGroupMembers($groupId);
            if ($groupUsers) {
                $groupUserView = View::make('groups._popup_list_members', ['groupUsers' => $groupUsers])->render();
                return Response::json(['result' => true, 'modal' => $groupUserView], 200);
            } else {
                return Response::json(['result' => false, 'message' => 'Find Group User error!!!'], 200);
            }
        }
    }

    public function followUsers()
    {
        if (!$this->currentUser) {
            return Response::view('errors.404', $this->viewData, 404);
        }
        if (Request::ajax()) {
            $pageCount = Input::get('pageCount');
            $groups = GroupService::getFollowingUsers($this->currentUser, $pageCount);
            $this->viewData['groups'] = $groups['groups'];
            $this->viewData['followerUsers'] = !empty($groups['followerUsers']) ?
                $groups['followerUsers']->toArray() : array();
            $this->viewData['follow'] = true;
            $this->viewData['ajax'] = true;
            $html = View::make('groups._list_groups', $this->viewData)->render();
            return Response::json(['html' => $html, 'seeMore' => $groups['seeMore']]);
        }
        $groups = GroupService::getFollowingUsers($this->currentUser);
        $this->viewData['groups'] = $groups['groups'];
        $this->viewData['followerUsers'] = !empty($groups['followerUsers']) ?
            $groups['followerUsers']->toArray() : array();
        $this->viewData['follow'] = true;
        $this->viewData['seeMore'] = $groups['seeMore'];

        $userJoinedGroups = GroupService::getAllGroupsOfCurrentUser();
        $this->viewData['userJoinedGroups'] = $userJoinedGroups;
        $seeMoreUserGroup = true;
        if ($userJoinedGroups->count() < GroupService::GROUPS_SIDEBAR_LIMIT) {
            $seeMoreUserGroup = false;
        }
        $this->viewData['seeMoreUserGroup'] = $seeMoreUserGroup;

        return View::make('groups.index', $this->viewData);
    }

    public function search($encryptedId)
    {
        if (Request::ajax()) {
            $groupId = Input::get('groupId');
            $pageCount = Input::get('pageCount');
            $keywords = Input::get('keywords');

            $group = Group::where('id', $groupId)->first();
            $groupContents = GroupService::getGroupsContentBy($keywords, $groupId, $pageCount);

            $seeMore = trans('labels.load_more');
            $hideSeeMore = $groupContents->count() < GroupService::GROUP_CONTENT_LIMIT;

            $html = View::make('groups._a_contents', [
                'groupContents' => $groupContents,
                'group' => $group,
                'lang' => $this->lang
            ])->render();
            return Response::json(['views' => $html, 'msg' => $seeMore, 'hideSeeMore' => $hideSeeMore]);
        } else {
            $group = Group::findByEncryptedId($encryptedId);
            $keywords = Input::get('keywords');

            if (empty($group)) {
                return Response::view('errors.404', $this->viewData, 404);
            }

            if ($group->groupSetting()->first()->privacy_flag == GroupSetting::PRIVACY_PRIVATE &&
                !$group->haveMemberIs($this->currentUser)
            ) {
                return Response::view('errors.404', $this->viewData, 404);
            }
            $this->viewData['title'] = trans('titles.group_search', ['keywords' => $keywords]);
            $this->viewData['keywords'] = $keywords;

            $groupId = $group->id;
            $groupContents = GroupService::getGroupsContentBy($keywords, $groupId);
            $hideSeeMore = $groupContents->count() < GroupService::GROUP_CONTENT_LIMIT;

            $this->prepareParamsForGroupLayout($group);

            $this->viewData['groupContents'] = $groupContents;
            $this->viewData['hideSeeMore'] = $hideSeeMore;

            return View::make('groups.search', $this->viewData);
        }
    }

    public static function getUsersListWhenCreate()
    {
        if (Request::ajax()) {
            $input = Input::all();
            $suggestName = $input['add_member'];
            $input['membersId'][] = Auth::user()->id;
            $existMembersId = isset($input['membersId']) ? $input['membersId'] : [];

            $usersArray = User::where(function ($query) use ($suggestName) {
                return $query->where('username', 'like', '%' . $suggestName . '%')
                    ->orWhere('name', 'like', '%' . $suggestName . '%');
            })
                ->whereNotIn('id', $existMembersId)
                ->lists('name', 'id');

            $users = [];
            foreach ($usersArray as $id => $name) {
                $users[] = ['label' => $name, 'id' => $id];
            }

            return Response::json($users);
        }
    }

    public static function addMemberWhenCreate()
    {
        if (Request::ajax()) {

            $userId = (int)Input::get('userId');
            $error = false;
            $html = '';

            $user = User::find($userId);
            if (!$user) {
                $error = true;
            } else {
                $html = View::make('groups._group_create_a_member', ['user' => $user])->render();
            }

            return Response::json([
                'error' => $error,
                'html' => $html,
            ], 200);
        }
    }

    public function getAllUserGroups()
    {
        if (Request::ajax()) {
            $pageCount = Input::get('pageCount');
            $seeMore = true;

            $userJoinedGroups = GroupService::getAllGroupsOfCurrentUser($pageCount);
            $html = View::make('groups._user_joined_groups',
                [
                    'userJoinedGroups' => $userJoinedGroups,
                    'lang' => $this->lang
                ])->render();

            if ($userJoinedGroups->count() < GroupService::GROUPS_SIDEBAR_LIMIT) {
                $seeMore = false;
            }

            return Response::json([
                'html' => $html,
                'seeMoreUserGroup' => $seeMore,
            ], 200);
        }
    }

    public static function uploadImage($type)
    {
        $image = Input::file('image');
        $imgUpload = GroupService::uploadImg($image, $type);
        return Response::json([
            'status' => 'success',
            'url' => $imgUpload,
        ], 200);
    }

    public function approveUserAndPostFromNotify()
    {
        if (Request::ajax()) {

            $errorResponse = [
                'error' => true,
                'message' => trans('messages.group.request_processed')
            ];

            $type = Input::get('type', null);
            $requestId = (int)Input::get('requestId', null);

            $approveFlag = ($type == 'accept') ? true : false;

            DB::beginTransaction();

            try {

                $notification = Notification::find($requestId);
                if (!$notification) {
                    $errorResponse['message'] = trans('messages.notification.no_notify');
                    return Response::json($errorResponse);
                }
                $groupId = $notification->group_id;
                $userId = $notification->sender_id;
                $postId = $notification->post_id;
                $notifyType = $notification->type;
                // Check Group
                $group = GroupUserService::checkAdminPermissionById($groupId);

                if (empty($group)) {
                    $errorResponse['message'] = trans('messages.notification.no_permission');
                    /*
                     * @var \Illuminate\Database\Eloquent\Model $notification
                     */
                    $notification->delete();
                    DB::commit();

                    return Response::json($errorResponse);
                }

                // Approve User
                if (($postId == 0) && ($notifyType == Notification::TYPE_APPROVE_MEMBER_IN_GROUP)) {
                    GroupUserService::approve($group->id, $userId, $approveFlag);
                    $errorResponse['message'] = $approveFlag ? trans('messages.notification.request_acceppted') :
                        trans('messages.notification.request_deny');
                    Notification::where('sender_id', $userId)
                        ->where('type', Notification::TYPE_APPROVE_MEMBER_IN_GROUP)
                        ->where('group_id', $groupId)
                        ->delete();
                } elseif (($postId != 0) && ($notifyType == Notification::TYPE_APPROVE_POST_IN_GROUP)) {
                    // Approve post
                    if ($approveFlag) {
                        GroupPostService::approve($group->id, $postId);
                        $groupPost = GroupPost::where('post_id', $postId)
                            ->where('group_id', $group->id)
                            ->first();
                        Event::fire(GroupPostNotificationHandler::EVENT_NAME, $groupPost);
                    } else {
                        GroupPostService::deny($group->id, $postId);
                    }

                    $errorResponse['message'] = $approveFlag ? trans('messages.notification.post_approved') :
                        trans('messages.notification.post_deny');
                    Notification::where('sender_id', $userId)
                        ->where('type', Notification::TYPE_APPROVE_POST_IN_GROUP)
                        ->where('group_id', $groupId)
                        ->delete();
                } else {
                    /*
                     * @var \Illuminate\Database\Eloquent\Model $notification
                     */
                    $notification->delete();
                }

                DB::commit();

                if ($approveFlag && ($postId == 0) && ($notifyType == Notification::TYPE_APPROVE_MEMBER_IN_GROUP)) {
                    $member = GroupUser::where('user_id', $userId)
                        ->where('group_id', $groupId)
                        ->first();
                    Event::fire(GroupAddMemberNotificationHandler::EVENT_NAME, $member);
                }

                return Response::json($errorResponse);

            } catch (Exception $e) {
                DB::rollBack();
                return Response::json($errorResponse);
            }
        }
    }

    /**
     * @param $groupId
     * Count Content of Group
     * @return int
     */
    protected function getCountContentGroup($groupId)
    {
        $total = 0;
        if ((int)$groupId) {
            $total = GroupService::getGroupContentCount($groupId)->total_posts +
                GroupService::getGroupContentCount($groupId)->total_series +
                GroupService::getGroupContentCount($groupId)->total_wiki;
        }
        return $total;
    }

    public static function generateSlug()
    {
        if (Request::ajax()) {
            $input = Input::all();
            if (isset($input['name']) && !empty($input['name'])) {
                $slug = uniquePostSlug($input['name'], 'vi');
                return Response::json(['name' => $slug . '-' . strtolower(str_random(5))]);
            }
            return Response::json($input);
        }
    }
}
