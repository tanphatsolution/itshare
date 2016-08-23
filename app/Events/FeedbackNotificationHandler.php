<?php namespace App\Events;

use App\Data\Blog\Feedback;
use App\Data\Blog\Notification;
use App\Data\Blog\Role;
use App\Data\Blog\UserRole;
use App\Data\System\User;
use App\Services\NotificationService;
use App\Services\RedisService;
use URL;
use View;

class FeedbackNotificationHandler
{
    const EVENT_NAME = 'feedback.handle';
    const ACTIVITY = 'feedback';

    public function handle(Feedback $feedback)
    {
        $senderId = is_null($feedback->user_id) ? 0 : $feedback->user_id;

        $adminsId = UserRole::where('role_id', Role::ADMIN)
                            ->lists('user_id');
        $admins = User::whereIn('id', $adminsId)
                        ->whereNotIn('id', [$senderId])
                        ->get();

        foreach ($admins as $admin) {

            $input = [
                'sender_id' => $senderId,
                'type' => Notification::TYPE_FEEDBACK,
                'recipient_id' => $admin->id,
                'content' => '',
            ];

            $notice = Notification::create($input);

            if ($notice) {
                $content = [
                    'url' => URL::action('FeedbacksController@index'),
                    'view' => View::make('notification._feedback_notice',
                                [
                                    'notice' => $notice,
                                ])->render(),
                    'notifications_count' => NotificationService::updateNotificationsCount($admin),
                ];
                $redis = new RedisService(json_encode($content));
                $redis->publish($admin->id);
            }
        }
    }
}
