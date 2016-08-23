<?php namespace App\Events;

use App\Data\Blog\GroupUser;
use App\Data\Blog\Notification;
use App\Services\NotificationService;
use App\Services\RedisService;
use Auth;
use View;

class GroupAddMemberNotificationHandler
{
    const EVENT_NAME = 'groups.addMember';
    const ACTIVITY = 'addMember';

    public function handle(GroupUser $member)
    {

        $input = [
            'sender_id' => Auth::user()->id,
            'type' => Notification::TYPE_ADD_MEMBER_TO_GROUP,
            'recipient_id' => $member->user_id,
            'group_id' => $member->group_id,
            'content' => '',
        ];

        $result = Notification::createOrUpdate($input);
        if ($result !== null) {
            $notice = Notification::find($result);
            $content = [
                'url' => url_to_group($member->group),
                'view' => View::make('notification._group_add_member', ['notice' => $notice, 'group' => $member->group])->render(),
                'notifications_count' => NotificationService::updateNotificationsCount($member->user),
            ];
            $redis = new RedisService(json_encode($content));
            $redis->publish($member->user_id);
        }
    }
}