<?php namespace App\Events;

use App\Data\Blog\Notification;
use App\Data\Blog\Post;
use App\Data\Blog\Report;
use App\Data\Blog\Role;
use App\Data\Blog\UserRole;
use App\Data\System\User;
use App\Services\NotificationService;
use App\Services\RedisService;
use URL;
use View;

class ReportNotificationHandler
{
    const EVENT_NAME = 'report.handle';
    const ACTIVITY = 'report';

    public function handle(Report $report)
    {
        $postId = $report->post_id;
        $post = Post::find($postId);
        $senderId = $report->user_id;

        $adminsId = UserRole::where('role_id', Role::ADMIN)
                            ->lists('user_id');
        $admins = User::whereIn('id', $adminsId)
                        ->whereNotIn('id', [$senderId])
                        ->get();

        $type = [
            Report::TYPE_SPAM => trans('messages.report.type_spam'),
            Report::TYPE_ILLEGAL_CONTENT => trans('messages.report.type_illegal_content'),
            Report::TYPE_HARASSMENT => trans('messages.report.type_harassment'),
        ];

        foreach ($admins as $admin) {

            $input = [
                'sender_id' => $senderId,
                'type' => Notification::TYPE_REPORT_POST,
                'recipient_id' => $admin->id,
                'post_id' => $postId,
                'content' => '',
            ];

            $notice = Notification::create($input);

            if ($notice) {
                $content = [
                    'url' => URL::action('ReportsController@index'),
                    'view' => View::make('notification._report_notice',
                                [
                                    'notice' => $notice,
                                    'post' => $post,
                                    'type' => $type[$report->type],
                                ])->render(),
                    'notifications_count' => NotificationService::updateNotificationsCount($admin),
                ];
                $redis = new RedisService(json_encode($content));
                $redis->publish($admin->id);
            }
        }
    }
}
