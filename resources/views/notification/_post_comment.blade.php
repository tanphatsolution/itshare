{{ trans('messages.notification.has_posted_a_comment', [
    'username' => user_img_tag($comment->user, 40) . bold($comment->user->username),
    'post' => bold(HTML::entities(str_limit($comment->post->title, $limit = 30, $end = '...'))),
]) }}
