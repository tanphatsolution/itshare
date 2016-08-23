{{ trans('messages.notification.has_a_feedback', [
    'username' => ($notice->sender_id != 0) ?
                    user_img_tag($notice->sender, 40) . bold($notice->sender->username) :
                    trans('labels.not_registered_user'),
]) }}
