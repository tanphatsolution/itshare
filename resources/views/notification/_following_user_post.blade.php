{{ trans('messages.notification.following_post', [
    'username' => user_img_tag($post->user, 40) . bold($post->user->username),
    'post' => bold(HTML::entities(str_limit($post->title, $limit = 30, $end = '...'))),
]) }}
