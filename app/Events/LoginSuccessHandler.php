<?php namespace App\Events;

use Carbon\Carbon;
use App\Data\Blog\Activity;
use App\Data\System\User;

class LoginSuccessHandler
{
    CONST EVENT_NAME = 'login.success';

    public function handle(User $user)
    {
        $message = $user->username . trans('logs.logged') . Carbon::now();
        $activity = array(
            'message' => $message,
            'trackable_type' => Activity::ACTIVITY_LOGIN_SUCCESS,
            'user_id' => $user->id
        );
        Activity::create($activity);
    }
}
