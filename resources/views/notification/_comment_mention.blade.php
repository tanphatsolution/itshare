{{ trans('messages.notification.has_mentioned_in_a_comment', [
    'username' => user_img_tag($comment->user, 40) . bold($comment->user->username),
    'post' => bold(HTML::entities($comment->post->title)),
]) }}
