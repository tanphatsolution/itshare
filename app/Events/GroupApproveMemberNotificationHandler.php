<?php namespace App\Events;

use App\Data\Blog\GroupUser;
use App\Data\System\User;
use App\Data\Blog\Notification;
use App\Services\RedisService;
use App\Services\NotificationService;
use View;

class GroupApproveMemberNotificationHandler
{
    const EVENT_NAME = 'groups.approveMember';
    const ACTIVITY = 'approveMember';

    public function handle(GroupUser $member)
    {

        $groupAdminsId = GroupUser::where('group_id', $member->group_id)
                            ->where('status', GroupUser::STATUS_MEMBER)
                            ->where('role', '!=', GroupUser::ROLE_MEMBER)
                            ->lists('user_id');
        $groupAdmins = User::whereIn('id', $groupAdminsId)->get();
        
        foreach ($groupAdmins as $groupAdmin) {

            $input = [
                'sender_id' => $member->user_id,
                'type' => Notification::TYPE_APPROVE_MEMBER_IN_GROUP,
                'recipient_id' => $groupAdmin->id,
                'group_id' => $member->group_id,
                'content' => '',
                'status' => Notification::STATUS_UNREAD,
            ];

            $notice = Notification::create($input);
            if ($notice !== null) {
                $content = [
                    'url' => url_to_group($member->group),
                    'view' => View::make('notification._group_approve_member', ['notice' => $notice, 'group' => $member->group])->render(),
                    'notifications_count' => NotificationService::updateNotificationsCount($groupAdmin),
                ];
                $redis = new RedisService(json_encode($content));
                $redis->publish($groupAdmin->id);
            }
        }
    }
}
