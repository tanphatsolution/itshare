<?php namespace App\Events;

use App\Data\Blog\UserRelationships;
use App\Data\Blog\Notification;
use App\Services\NotificationService;
use App\Services\RedisService;
use View;

class UserFollowNotificationHandler
{
    const EVENT_NAME = 'users.follow';
    const ACTIVITY = 'follow';

    public function handle(UserRelationships $userRelationships)
    {
        if (!$userRelationships->followed->setting->receive_follow_notification) {
            return;
        }
        $input = [
            'sender_id' => $userRelationships->follower_id,
            'type' => Notification::TYPE_FOLLOW,
            'recipient_id' => $userRelationships->followed_id,
        ];
        $result = Notification::createOrUpdate($input);
        if ($result !== null) {
            $content = [
                'url' => url_to_user($userRelationships->follower, array()),
                'view' => View::make('notification._user_follow', ['userRelationships' => $userRelationships])->render(),
                'notifications_count' => NotificationService::updateNotificationsCount($userRelationships->followed),
            ];
            $redis = new RedisService(json_encode($content));
            $redis->publish($userRelationships->followed_id);
        }
    }
}