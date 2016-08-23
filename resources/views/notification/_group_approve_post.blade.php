{{ trans('messages.notification.post_group_need_approval', [
    'sender' => user_img_tag($notice->sender, 40) . bold($notice->sender->username),
    'group' => bold(HTML::entities(str_limit($group->name, $limit = 30, $end = '...'))),
]) }}
