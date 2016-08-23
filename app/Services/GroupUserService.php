<?php namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Data\Blog\GroupUser;
use DB;

class GroupUserService
{
    const MEMBER_LIST_LIMIT = 12;

    public static $memberRoleNameMap = [
        GroupUser::ROLE_MEMBER => 'Member',
        GroupUser::ROLE_ADMIN => 'Admin',
        GroupUser::ROLE_OWNER => 'Admin'
    ];

    public static function checkAdminPermissionByEncryptedId($encryptedId)
    {
        $groupUser = GroupUser::leftJoin('groups', 'group_users.group_id', '=', 'groups.id')
            ->where('groups.encrypted_id', $encryptedId)
            ->where('group_users.user_id', Auth::user()->id)
            ->where('group_users.status', GroupUser::STATUS_MEMBER)
            ->where(function($query) {
                $query->where('group_users.role', GroupUser::ROLE_ADMIN)
                    ->orWhere('group_users.role', GroupUser::ROLE_OWNER);
            })
            ->select('groups.*')
            ->get();

        return $groupUser->isEmpty() ? [] : $groupUser->first();
    }

    public static function getCurrentUserRole($groupId)
    {
        if (Auth::check()) {
            return GroupUser::where('user_id', Auth::user()->id)
                ->where('group_id', $groupId)
                ->first();
        }

        return null;
    }

    public static function getUserRole($groupId, $userId)
    {
        return GroupUser::where('user_id', $userId)
            ->where('group_id', $groupId)
            ->first();
    }

    public static function getGroupMembers($groupId, $limit = self::MEMBER_LIST_LIMIT, $getAllFlag = false)
    {
        $deletedUserIds = UserService::getDeletedUsers()->lists('id');
        $query = GroupUser::with('user')
            ->where('group_id', $groupId)
            ->where('status', GroupUser::STATUS_MEMBER)
            ->whereNotIn('user_id', $deletedUserIds)
            ->orderBy('role', 'desc')
            ->orderBy('status', 'desc');

        if (!$getAllFlag) {
            $query->take($limit);
        }

        return $query->get();
    }

    public static function countGroupMembers($groupId)
    {
        return GroupUser::where('group_id', $groupId)
            ->where('status', GroupUser::STATUS_MEMBER)
            ->count();
    }

    public static function getUnapprovedUsers($groupId)
    {
        $unapprovedUsers = GroupUser::with('User')
            ->where('group_id', $groupId)
            ->where('status', GroupUser::STATUS_WAITING_APPROVE)
            ->get();

        return $unapprovedUsers;
    }

    public static function checkAdminPermissionById($groupId)
    {
        $groupUser = GroupUser::leftJoin('groups', 'group_users.group_id', '=', 'groups.id')
            ->where('group_users.group_id', $groupId)
            ->where('group_users.user_id', Auth::user()->id)
            ->where('group_users.status', GroupUser::STATUS_MEMBER)
            ->where(function($query) {
                $query->where('group_users.role', GroupUser::ROLE_ADMIN)
                    ->orWhere('group_users.role', GroupUser::ROLE_OWNER);
            })
            ->select('groups.*')
            ->get();

        return $groupUser->isEmpty() ? [] : $groupUser->first();
    }

    public static function approve($groupId, $userId, $approveFlag)
    {
        if ($approveFlag) {
            return GroupUser::where('group_id', $groupId)
                ->where('user_id', $userId)
                ->update(['status' => GroupUser::STATUS_MEMBER]);
        } else {
            return GroupUser::where('group_id', $groupId)
                ->where('user_id', $userId)
                ->delete();
        }
    }

    public static function getUserFollowInGroups($groupId, $userIds = array())
    {
        $deletedUserIds = UserService::getDeletedUsers()->lists('id');
        $userIdString = $userIds != null ? implode(',', $userIds) : '';
        
        return GroupUser::where('group_id', $groupId)
            ->with('user.avatar')
            ->where('status', GroupUser::STATUS_MEMBER)
            ->whereNotIn('user_id', $deletedUserIds)
            ->orderByRaw(DB::raw('FIELD(user_id, ' . $userIdString . ')'))->get();
    }
}