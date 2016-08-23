<?php namespace App\Events;

use App\Data\Blog\GroupPost;
use App\Data\Blog\GroupUser;
use App\Data\Blog\Notification;
use App\Data\Blog\Post;
use App\Data\System\User;

use View;

class GroupApprovePostNotificationHandler
{
    const EVENT_NAME = 'groups.approvePost';
    const ACTIVITY = 'approvePost';

    public function handle(GroupPost $groupPost)
    {

        if ($groupPost->approved == GroupPost::GROUP_POST_APPROVED) {
            return;
        }

        $postId = $groupPost->post_id;
        $post = Post::find($postId);
        $senderId = $post->user_id;
        $groupId = $groupPost->group_id;
        $group = $groupPost->group;

        $groupAdminsId = GroupUser::where('group_id', $groupPost->group_id)
                            ->where('status', GroupUser::STATUS_MEMBER)
                            ->where('role', '!=', GroupUser::ROLE_MEMBER)
                            ->lists('user_id');
        $groupAdmins = User::whereIn('id', $groupAdminsId)->get();
        
        foreach ($groupAdmins as $groupAdmin) {

            $input = [
                'sender_id' => $senderId,
                'type' => Notification::TYPE_APPROVE_POST_IN_GROUP,
                'recipient_id' => $groupAdmin->id,
                'post_id' => $postId,
                'group_id' => $groupId,
                'content' => '',
            ];

            $notice = Notification::create($input);

            if ($notice) {
                $content = [
                    'url' => url_to_group($group),
                    'view' => View::make('notification._group_approve_post', ['notice' => $notice, 'group' => $group])->render(),
                    'notifications_count' => \App\Services\NotificationService::updateNotificationsCount($groupAdmin),
                ];
                $redis = new \App\Services\RedisService(json_encode($content));
                $redis->publish($groupAdmin->id);
            }
        }
    }
}