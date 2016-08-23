<?php
namespace App\Services;

use App\Data\Blog\GroupPost;
use App\Data\Blog\GroupUser;
use App\Data\Blog\Image;
use App\Data\Blog\Post;
use App\Data\Blog\UserPostLanguage;
use App\Data\Blog\Wiki;
use App\Events\GroupAddMemberNotificationHandler;
use App\Events\GroupApprovePostNotificationHandler;
use App\Events\GroupPostNotificationHandler;
use Illuminate\Support\Facades\DB;
use App\Data\Blog\PostCategory;
use App\Data\Blog\GroupSetting;
use App\Data\Blog\Group;
use App\Data\Blog\GroupSeries;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Event;
use Config;

class GroupService
{
    const GROUP_CATEGORIES_LIMIT = 20;
    const GROUP_POST_UNAPPROVED_LIMIT = 5;
    const GROUP_CONTENT_LIMIT = 10;
    const GROUP_USER_LIMIT = 8;
    const GROUPS_SIDEBAR_LIMIT = 22;

    const GROUP_FILTER_ALL = 'all';
    const GROUP_FILTER_POST = 'post';
    const GROUP_FILTER_SERIES = 'series';
    const GROUP_FILTER_WIKI = 'wiki';

    CONST PER_PAGE = 12;

    CONST ACTIVE = 1;

    public static $pageCount;

    public static function create($input)
    {
        DB::beginTransaction();
        $formDataGroups = [
            'name' => $input['name'],
            'shortname' => isset($input['shortname']) && $input['shortname'] != null ? self::convertShortName($input['shortname']) : convert_to_short_name($input['name']) . time(),
            'description' => $input['description'],
            'url' => $input['url'],
            'cover_img' => !empty($input['cover_img']) ? self::uploadImg($input['cover_img'], 'cover') : null,
            'cover_img_crop_position' => $input['cover_img_crop_position'],
            'profile_img' => !empty($input['profile_img']) ? self::uploadImg($input['profile_img'], 'profile') : null,
            'profile_img_crop_position' => $input['profile_img_crop_position']
        ];

        if ($input['shortname'] == null) {
            $formDataGroups['is_shortname'] = 0;
        }

        $group = Group::create($formDataGroups);
        if ($group) {
            $cropCoverAndProfileImageFlag = false;

            if ((!is_null($input['cover_img_crop_position']) || !empty($input['cover_img_crop_position']))
                && !empty($group->cover_img)) {
                $group = self::makeCropImage($group, 'cover');
                $cropCoverAndProfileImageFlag = true;
            }

            if ((!is_null($input['profile_img_crop_position']) || !empty($input['profile_img_crop_position']))
                && !empty($group->profile_img)) {
                $group = self::makeCropImage($group, 'profile');
                $cropCoverAndProfileImageFlag = true;
            }

            if ($cropCoverAndProfileImageFlag) {
                $group->save();
            }

            $groupId = $group->id;
            $userId = Auth::check() ? Auth::user()->id : '';

            if ($input['add_post_flag'] == GroupSetting::ALL_CAN_POST_WITH_PERMISSION) {
                $input['add_post_flag'] = GroupSetting::ALL_CAN_POST;
                $input['approve_post_flag'] = GroupSetting::POST_NEED_APPROVE;
            } else {
                $input['approve_post_flag'] = GroupSetting::POST_NO_NEED_APPROVE;
            }

            $groupSetting = GroupSetting::create([
                'group_id' => $groupId,
                'privacy_flag' => $input['privacy_flag'],
                'add_member_flag' => $input['add_member_flag'],
                'add_post_flag' => $input['add_post_flag'],
                'edit_post_flag' => $input['edit_post_flag'],
                'approve_post_flag' => $input['approve_post_flag'],
                'edit_series_flag' => $input['edit_series_flag'],
            ]);

            //create admin + owner
            $error = false;
            $groupUser = GroupUser::create([
                'group_id' => $groupId,
                'user_id' => $userId,
                'role' => GroupUser::ROLE_OWNER,
                'status' => GroupUser::STATUS_MEMBER
            ]);
            if (!$groupUser) {
                $error = true;
            }

            //create member
            if (isset($input['membersId'])) {
                $members = [];
                foreach ($input['membersId'] as $memberId) {
                    if ($userId != $memberId) {
                        $groupUser = GroupUser::create([
                            'group_id' => $groupId,
                            'user_id' => $memberId,
                            'role' => GroupUser::ROLE_MEMBER,
                            'status' => GroupUser::STATUS_MEMBER
                        ]);
                        $members[] = $groupUser;
                        if (!$groupUser) {
                            $error = true;
                        }
                    }
                }
            }

            if ($groupSetting && !$error) {
                DB::commit();
                if (isset($members) && !empty($members)) {
                    foreach ($members as $member) {
                        Event::fire(GroupAddMemberNotificationHandler::EVENT_NAME, $member);
                    }
                }
            } else {
                DB::rollback();
                $group = null;
            }
        } else {
            DB::rollback();
            $group = null;
        }
        return $group;
    }

    public static function update($group, $input = array())
    {
        $error = true;
        DB::beginTransaction();

        if (isset($input['add_post_flag']) && $input['add_post_flag'] == GroupSetting::ALL_CAN_POST_WITH_PERMISSION) {
            $input['add_post_flag'] = GroupSetting::ALL_CAN_POST;
            $input['approve_post_flag'] = GroupSetting::POST_NEED_APPROVE;
        } else {
            $input['approve_post_flag'] = GroupSetting::POST_NO_NEED_APPROVE;
        }

        if ($group->groupSetting()->get() != null) {
            $updateSetting = $group->groupSetting()->first()->update($input);
        } else {
            $updateSetting = false;
        }

        if ($updateSetting) {
            DB::commit();
        } else {
            DB::rollback();
        }

        DB::beginTransaction();
        $group->cover_img = !empty($input['cover_img']) ? self::uploadImg($input['cover_img'], 'cover')
            : $group->cover_img;
        $group->cover_img_crop_position = !is_null($input['cover_img_crop_position'])
            ? $input['cover_img_crop_position']
            : $group->cover_img_crop_position;

        if ((!is_null($input['cover_img_crop_position']) || !empty($input['cover_img_crop_position']))
            && !empty($group->cover_img)) {
            $group = self::makeCropImage($group, 'cover');
        }

        $group->profile_img = !empty($input['profile_img']) ? self::uploadImg($input['profile_img'], 'profile')
            : $group->profile_img;
        $group->profile_img_crop_position = !is_null($input['profile_img_crop_position'])
            ? $input['profile_img_crop_position']
            : $group->profile_img_crop_position;

        if ((!is_null($input['profile_img_crop_position']) || !empty($input['profile_img_crop_position']))
            && !empty($group->profile_img)) {
            $group = self::makeCropImage($group, 'profile');
        }

        if (empty($input['shortname']) || empty(str_replace('　', '', trim($input['shortname'])))) {
            $errors = array();
            $errors['empty_group_shortname'] = trans('messages.group.empty_group_shortname');
        }

        $group->shortname = self::convertShortName($input['shortname']);
        $group->is_shortname = $input['is_shortname'];


        $updateGroup = $group->save();
        if ($updateGroup) {
            DB::commit();
        } else {
            DB::rollback();
            $error = false;
        }
        return $error;
    }

    public static function validate($input)
    {
        $errors = [];
        if (empty($input['name']) || empty(str_replace('　', '', trim($input['name'])))) {
            $errors['empty_group_name'] = trans('messages.group.empty_group_name');
        }

        $groupShortName = Group::findByShortname(self::convertShortName($input['shortname']));
        if (isset($input['encryptedId'])) {
            $group = Group::findByEncryptedId($input['encryptedId']);
            if (($group->shortname != self::convertShortName($input['shortname'])) && $groupShortName) {
                $errors['duplicate_group_shortname'] = trans('messages.group.duplicate_group_shortname');
            }
        } elseif ($groupShortName) {
            $errors['duplicate_group_shortname'] = trans('messages.group.duplicate_group_shortname');
        }

        $imageRules = Image::getUploadRules();
        $imageValidation = Validator::make($input, $imageRules);
        if ($imageValidation->fails()) {
            $errors['error_thumb_img'] = trans('messages.group.error_thumb_img',
                ['size' => Config::get('image')['max_image_size'], 'type' => 'jpg, jpeg, png, gif']);
        }
        return $errors;
    }

    public static function uploadImg($img, $typeImg = 'cover')
    {
        $config = \Config::get('image');
        if ($typeImg == 'cover') {
            $destinationPath = $config['group_image']['cover_upload_dir'];
        } else {
            $destinationPath = $config['group_image']['profile_upload_dir'];
        }
        $extension = $img->getClientOriginalExtension();
        $imageName = sha1(time() . time() . mt_rand()) . '.' . $extension;
        $image = '';
        if (!is_null($img)) {
            $image = $destinationPath . '/' . $imageName;
            $img->move(public_path() . '/' . $destinationPath, $imageName);
        }
        return !empty($image) ? $image : null;
    }

    public static function getGroupsCanPostOf($user)
    {
        $groupsIdUser = $user->groupUsers()->where('status', GroupUser::STATUS_MEMBER)->lists('group_id');

        $groupsIdUserIsAdminOrOwner = $user->groupUsers()->where('role', '!=', GroupUser::ROLE_MEMBER)->lists('group_id');

        $groupsIdUserIsMemberCanPost = GroupSetting::whereNotIn('group_id', $groupsIdUserIsAdminOrOwner)
            ->whereIn('group_id', $groupsIdUser)
            ->where('add_post_flag', GroupSetting::ALL_CAN_POST)
            ->lists('group_id');

        $groups = Group::whereIn('id', $groupsIdUserIsAdminOrOwner)
                        ->orWhereIn('id', $groupsIdUserIsMemberCanPost)
                        ->lists('name', 'id');
        $result = [];
        foreach ($groups as $id => $name) {
            $result[$id] = str_limit($name, $limit = 35);
        }
        return $result;
    }

    public static function groupPostPrivacyOptions()
    {
        return [
            GroupPost::GROUP_POST_PUBLIC => trans('labels.groups.post_public'),
            GroupPost::GROUP_POST_PRIVATE => trans('labels.groups.post_private'),
        ];
    }

    public static function groupPostMaker($input)
    {
        $input['group_id'] = !isset($input['group_id']) ? $input['group_id_hidden'] : $input['group_id'];

        if ($input['group_id'] == 0) {
            self::deleteGroupPost($input);
        } else {
            $groupPost = GroupPost::where('post_id', $input['post_id'])->first();
            $group = Group::where('id', $input['group_id'])->first();

            if (!isset($input['privacy_flag']) || empty($input['privacy_flag'])) {
                switch ($group->groupSetting()->first()->privacy_flag) {
                    case GroupSetting::PRIVACY_PUBLIC:
                        $input['privacy_flag'] = GroupPost::GROUP_POST_PUBLIC;
                        break;
                    case GroupSetting::PRIVACY_PROTECTED:
                        $input['privacy_flag'] = GroupPost::GROUP_POST_PRIVATE;
                        break;
                    case GroupSetting::PRIVACY_PRIVATE:
                        $input['privacy_flag'] = GroupPost::GROUP_POST_PRIVATE;
                        break;
                    default:
                        $input['privacy_flag'] = GroupPost::GROUP_POST_PUBLIC;
                        break;
                }
            }
            $approvedMember = ($group->groupSetting()->first()->approve_post_flag == GroupSetting::POST_NO_NEED_APPROVE)
                ? GroupPost::GROUP_POST_APPROVED : GroupPost::GROUP_POST_NOT_APPROVE;
            $approved = (Auth::user()->isAdminOf($group)) ? GroupPost::GROUP_POST_APPROVED : $approvedMember;
            $input['approved'] = $approved;

            if ($groupPost) {
                if ($groupPost->group_id == $input['group_id']) {
                    $input['approved'] = $groupPost->approved;
                }
                self::updateGroupPost($groupPost, $input);
            } else {
                self::createGroupPost($input);
            }
        }
    }

    public static function createGroupPost($input)
    {
        DB::beginTransaction();
        $groupPost = GroupPost::create([
            'group_id' => $input['group_id'],
            'post_id' => $input['post_id'],
            'privacy_flag' => $input['privacy_flag'],
            'approved' => $input['approved'],
        ]);
        if ($groupPost) {
            DB::commit();
            Event::fire(GroupApprovePostNotificationHandler::EVENT_NAME, $groupPost);
            Event::fire(GroupPostNotificationHandler::EVENT_NAME, $groupPost);
        } else {
            DB::rollback();
        }
        return $groupPost;
    }

    public static function updateGroupPost($groupPost, $input)
    {
        $groupIdOrigin = $groupPost->group_id;
        DB::beginTransaction();
        $updateGroupPost = $groupPost->update([
            'group_id' => $input['group_id'],
            'privacy_flag' => $input['privacy_flag'],
            'approved' => $input['approved'],
        ]);
        if ($updateGroupPost) {
            DB::commit();
            Event::fire(GroupApprovePostNotificationHandler::EVENT_NAME, $groupPost);
            if ($groupIdOrigin != $groupPost->group_id) {
                Event::fire(GroupPostNotificationHandler::EVENT_NAME, $groupPost);
            }
        } else {
            DB::rollback();
        }
        return $updateGroupPost;
    }

    public static function deleteGroupPost($input)
    {
        $groupPost = GroupPost::where('post_id', $input['post_id'])->first();
        $deleteGroupPost = null;
        if ($groupPost) {
            DB::beginTransaction();
            $deleteGroupPost = $groupPost->delete();
            if ($deleteGroupPost) {
                DB::commit();
            } else {
                DB::rollback();
            }
        }
        return $deleteGroupPost;
    }

    public static function getGroups($user, $pageCount = 0)
    {
        $groups = Group::filter_by_active(self::ACTIVE);
        $groupsSecretId = GroupSetting::where('privacy_flag', GroupSetting::PRIVACY_PRIVATE)->lists('group_id');
        if (!empty($user)) {
            $groupUsers = GroupUser::where('user_id', $user->id)
                ->where('status', GroupUser::STATUS_MEMBER)
                ->lists('group_id');
            $groups = $groups->with('groupSeries', 'groupUsers.user', 'group_post_by_approved', 'current_user_group')
                ->whereNotIn('id', $groupsSecretId)
                ->whereNotIn('id', $groupUsers);

        } else {
            $groups = $groups->with('groupSeries', 'groupUsers.user', 'group_post_by_approved')
                ->whereNotIn('id', $groupsSecretId);
        }
        $groups = $groups->orderBy('created_at', 'desc');
        $seeMore = $groups->count() > self::PER_PAGE ? ceil($groups->count() / self::PER_PAGE) : 0;
        if ($pageCount > 0) {
            $offset = $pageCount * self::PER_PAGE;
            $groups = $groups->take(self::PER_PAGE)->offset($offset)->get();
        } else {
            $groups = $groups->take(self::PER_PAGE)->get();
        }
        $result = array();
        $result['groups'] = $groups;
        $result['seeMore'] = $seeMore;
        return $result;
    }

    public static function getFollowingUsers($currentUser, $pageCount = 0)
    {
        $groups = Group::filter_by_active(self::ACTIVE);
        $groupsSecretId = GroupSetting::where('privacy_flag', GroupSetting::PRIVACY_PRIVATE)->lists('group_id');
        $followerUsers = UserService::follower($currentUser)->lists('followed_id');

        $userJoinGroups = GroupUser::where('user_id', $currentUser->id)
            ->where('status', GroupUser::STATUS_MEMBER)
            ->lists('group_id');

        $groupUsers = GroupUser::whereIn('user_id', $followerUsers)
            ->where('status', GroupUser::STATUS_MEMBER)
            ->whereNotIn('group_id', $userJoinGroups)
            ->lists('group_id')
            ->toArray();

        $groups = $groups->with('groupSeries', 'groupUsers.user', 'group_post_by_approved', 'current_user_group')
            ->whereNotIn('id', $groupsSecretId)
            ->whereIn('id', array_unique($groupUsers))
            ->orderBy('created_at', 'desc');

        $seeMore = $groups->count() > self::PER_PAGE ? ceil($groups->count() / self::PER_PAGE) : 0;

        if ($pageCount > 0) {
            $offset = $pageCount * self::PER_PAGE;
            $groups = $groups->take(self::PER_PAGE)->offset($offset)->get();
        } else {
            $groups = $groups->take(self::PER_PAGE)->get();
        }
        $result = array();
        $result['groups'] = $groups;
        $result['seeMore'] = $seeMore;
        $result['followerUsers'] = $followerUsers;
        return $result;
    }

    /**
     * Get categries of posts in group
     * @param int $groupId
     * @return array
     */
    public static function getAllGroupCategories($groupId)
    {
        $categories = PostCategory::with('category')
                        ->select('post_categories.*', DB::raw('count(group_posts.group_id) as postsNumber'))
                        ->join('group_posts', 'group_posts.post_id', '=', 'post_categories.post_id')
                        ->where('group_posts.group_id', $groupId)
                        ->where('group_posts.approved', GroupPost::GROUP_POST_APPROVED)
                        ->groupBy('post_categories.category_id');
        $currentUserLanguages = UserPostLanguage::getCurrentUserLanguages();
        if (isset($currentUserLanguages[0]) && $currentUserLanguages[0] != UserPostLanguage::SETTING_ALL_LANGUAGES) {
            $categories->leftJoin('posts', 'post_categories.post_id', '=', 'posts.id')
                       ->whereIn('posts.language_code', $currentUserLanguages);
        }
        return $categories->get();
    }

    public static function getUnapprovedPosts($groupId)
    {
        $groupDraftPostIds = self::getGroupDraftPostIds($groupId);

        $unapprovedPosts = GroupPost::with('post')
            ->where('group_id', $groupId)
            ->whereNotNull('post_id')
            ->whereNull('group_series_id')
            ->whereNotIn('post_id', $groupDraftPostIds)
            ->where('approved', GroupPost::UNAPPROVED)
            ->orderBy('created_at', 'desc')
            ->get();

        return $unapprovedPosts;
    }

    public static function getGroupContents($groupId, $filter, $pageCount = 0)
    {
        $offset = $pageCount * self::GROUP_CONTENT_LIMIT;
        $groupDraftPostIds = self::getPostLanguage($groupId);
        $postWikiIds = Wiki::where('group_id', $groupId)->lists('post_id');

        $groupContentQuery = GroupPost::with('post', 'series', 'post.categories', 'post.user', 'post.user.avatar')
            ->where('group_id', $groupId);

        self::_optionFilterGroupContent($filter, $groupContentQuery, $groupDraftPostIds, $postWikiIds);

        $groupPosts = $groupContentQuery->orderBy('group_posts.created_at', 'desc');
        self::$pageCount = $groupPosts->count() <= GroupService::GROUP_CONTENT_LIMIT ? true : false;
        $groupContents = $groupPosts->take(self::GROUP_CONTENT_LIMIT)
            ->skip($offset)
            ->get();
        return $groupContents;
    }

    public static function getGroupContentCount($groupId, $hasWiki = true)
    {
        $groupDraftPostIds = self::getPostLanguage($groupId);
        $postWikiIds = Wiki::where('group_id', $groupId)->lists('post_id');
        $groupContentCount = GroupPost::where('group_id', $groupId)
            ->where(function($query) use ($groupDraftPostIds, $postWikiIds) {
                return $query->where(function($firstQuery) {
                    return $firstQuery->whereNull('post_id')
                        ->whereNotNull('group_series_id');
                    })
                    ->orWhere(function($secondQuery) use ($groupDraftPostIds, $postWikiIds) {
                        return $secondQuery->whereNotNull('post_id')
                            ->whereNull('group_series_id')
                            ->whereNotIn('post_id', $groupDraftPostIds)
                            ->whereNotIn('post_id', $postWikiIds);
                    });
            })
            ->where('approved', GroupPost::APPROVED)
            ->select(DB::raw('
                COUNT(IF(ISNULL(post_id) AND NOT(ISNULL(group_series_id)), 1, NULL)) AS total_series,
                COUNT(IF(NOT(ISNULL(post_id)) AND ISNULL(group_series_id), 1, NULL)) AS total_posts
            '));
        $groupContentCount = $groupContentCount->get();
        if ($hasWiki) {
            $groupContentCount[0]->total_wiki = Wiki::where('group_id', $groupId)
                ->whereNotIn('post_id', $groupDraftPostIds)
                ->count();
        }

        return $groupContentCount[0];
    }

    public static function getGroupSettings($groupId)
    {
        return GroupSetting::where('group_id', $groupId)->first();
    }

    public static function getGroupDraftPostIds($groupId)
    {
        return GroupPost::leftJoin('posts', 'group_posts.post_id', '=', 'posts.id')
            ->where('group_posts.group_id', $groupId)
            ->whereNull('posts.published_at')
            ->whereNull('posts.deleted_at')
            ->whereNotNull('posts.encrypted_id')
            ->lists('group_posts.post_id');
    }

    public static function limit($groups)
    {
        return $groups->limit(self::GROUP_USER_LIMIT)->get();
    }

    public static function getGroupsContentBy($keywords, $groupId, $pageCount = 0)
    {
        $offset = $pageCount * self::GROUP_CONTENT_LIMIT;

        $postsIdInGroup = GroupPost::where('group_id', $groupId)
            ->whereNotNull('post_id')
            ->whereNull('group_series_id')
            ->where('approved', GroupPost::APPROVED)
            ->lists('post_id');
        $postsIdFound = Post::whereIn('id', $postsIdInGroup)
            ->whereNotNull('published_at')
            ->whereNotNull('encrypted_id')
            ->where(function ($query) use ($keywords) {
                $query->where('title', 'LIKE', '%' . $keywords . '%')
                    ->orWhere('content', 'LIKE', '%' . $keywords . '%');
            })->lists('id');

        $seriesIdInGroup = GroupPost::where('group_id', $groupId)
            ->whereNull('post_id')
            ->whereNotNull('group_series_id')
            ->where('approved', GroupPost::APPROVED)
            ->lists('group_series_id');
        $seriesIdFound = GroupSeries::whereIn('id', $seriesIdInGroup)
            ->where(function ($query) use ($keywords) {
                $query->where('name', 'LIKE', '%' . $keywords . '%')
                    ->orWhere('description', 'LIKE', '%' . $keywords . '%');
            })->lists('id');

        $groupContents = GroupPost::with('post', 'series', 'post.categories')
            ->where(function ($query) use ($postsIdFound, $seriesIdFound) {
                $query->whereIn('post_id', $postsIdFound)
                    ->orWhereIn('group_series_id', $seriesIdFound);
            })
            ->orderBy('created_at', 'desc')
            ->take(self::GROUP_CONTENT_LIMIT)
            ->skip($offset)
            ->get();

        return $groupContents;
    }

    public static function getAllGroupsOfCurrentUser($pageCount = 0)
    {
        $offset = $pageCount * self::GROUPS_SIDEBAR_LIMIT;

        $userJoinedGroups = [];

        if (Auth::check()) {
            $userJoinedGroups = GroupUser::where('user_id', Auth::user()->id)
                ->where('status', GroupUser::STATUS_MEMBER)
                ->lists('group_id');
        }

        $group = Group::whereIn('id', $userJoinedGroups)
            ->with('groupUsers', 'group_post_by_approved')
            ->where('active', GroupService::ACTIVE)
            ->orderBy('created_at', 'DESC')
            ->take(self::GROUPS_SIDEBAR_LIMIT)
            ->skip($offset)
            ->get();

        return $group;
    }

    public static function getPostLanguage($groupId)
    {
        $groupPostDraftIds = GroupPost::leftJoin('posts', 'group_posts.post_id', '=', 'posts.id')
            ->where('group_posts.group_id', $groupId)
            ->whereNull('posts.deleted_at')
            ->whereNotNull('posts.id')
            ->whereNull('posts.published_at')
            ->pluck('group_posts.post_id');

        $userLanguages = UserPostLanguage::getCurrentUserLanguages();

        $groupPostNotInFilterLanguageIds = [];

        if ($userLanguages[0] !== UserPostLanguage::SETTING_ALL_LANGUAGES) {
            $groupPostNotInFilterLanguageIds = GroupPost::leftJoin('posts', 'group_posts.post_id', '=', 'posts.id')
                ->where('group_posts.group_id', $groupId)
                ->whereNull('posts.deleted_at')
                ->whereNotNull('posts.id')
                ->whereNotIn('posts.language_code', $userLanguages)
                ->pluck('group_posts.post_id');
        }

        if (!empty($groupPostDraftIds) && !empty($groupPostNotInFilterLanguageIds)) {
            return array_merge($groupPostDraftIds, $groupPostNotInFilterLanguageIds);
        } else if (!empty($groupPostDraftIds) && empty($groupPostNotInFilterLanguageIds)) {
            return $groupPostDraftIds;
        } else if (empty($groupPostDraftIds) && !empty($groupPostNotInFilterLanguageIds)) {
            return $groupPostNotInFilterLanguageIds;
        } else {
            return [];
        }
    }

    public static function makeCropImage($group, $type) {
        $imgField = $type . '_img';
        $imgCropField = $imgField . '_crop';
        $imgCropPositionField = $imgCropField . '_position';

        if (!empty($group[$imgField]) && !empty($group[$imgCropPositionField])) {
            $imgCropPositionObj = json_decode($group[$imgCropPositionField]);

            if (isset($imgCropPositionObj->offset) && isset($imgCropPositionObj->zoom)) {
                $imgCropPath = ImageService::crop(
                        $group[$imgField],
                        $imgCropPositionObj->zoom,
                        [$imgCropPositionObj->offset->x, $imgCropPositionObj->offset->y],
                        ImageService::CROP_WIDTH,
                        ImageService::CROP_HEIGHT,
                        'profile'
                    );

                if (!empty($imgCropPath)) {
                    $group[$imgCropField] = $imgCropPath;
                }
            }
        }

        return $group;
    }

    public static function convertShortName($shortName)
    {
        $shortName = vn_to_latin($shortName, true);
        $keep = ['-', '_', '.'];
        $shortName = remove_special_characters($shortName, $keep);
        return $shortName;
    }

    protected static function _optionFilterGroupContent($filter, $groupContentQuery, $groupDraftPostIds, $postWikiIds)
    {
        switch ($filter) {
            case self::GROUP_FILTER_ALL:
                $groupContentQuery->where(function($query) use ($groupDraftPostIds) {
                    return $query->where(function($firstQuery) {
                        return $firstQuery->whereNull('post_id')
                            ->whereNotNull('group_series_id');
                        })
                        ->orWhere(function($secondQuery) use ($groupDraftPostIds) {
                            return $secondQuery->whereNotNull('post_id')
                                ->whereNull('group_series_id')
                                ->whereNotIn('post_id', $groupDraftPostIds);
                        });
                })
                ->where('approved', GroupPost::APPROVED);
                break;

            case self::GROUP_FILTER_POST:
                $groupContentQuery->whereNotNull('post_id')
                    ->whereNull('group_series_id')
                    ->whereNotIn('post_id', $groupDraftPostIds)
                    ->whereNotIn('post_id', $postWikiIds)
                    ->where('approved', GroupPost::APPROVED);
                break;

            case self::GROUP_FILTER_SERIES:
                $groupContentQuery->whereNull('post_id')
                    ->whereNotNull('group_series_id')
                    ->where('approved', GroupPost::APPROVED);
                break;

            case self::GROUP_FILTER_WIKI:
                $groupContentQuery->whereNull('group_series_id')
                                ->whereNotIn('post_id', $groupDraftPostIds)
                                ->whereIn('post_id', $postWikiIds);
                break;
            default:
                $groupContentQuery->where(function($query) {
                        return $query->whereNull('post_id')
                            ->whereNotNull('group_series_id');
                    })
                    ->orWhere(function($query) {
                        return $query->whereNotNull('post_id')
                            ->whereNull('group_series_id');
                    })
                    ->where('approved', GroupPost::APPROVED);
                break;
        }
    }
}