{{ trans('messages.notification.has_stocked', [
    'username' => user_img_tag($stock->user, 40) . bold($stock->user->username),
    'post' => bold(HTML::entities(str_limit($stock->post->title, $limit = 30, $end = '...'))),
]) }}
