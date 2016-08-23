@extends('emails.layouts.default')

@section('main')
{{ isset($lang) ? App::setLocale($lang) : App::setLocale('en') }}
<h1 style="font-size:20px;color:rgb(40,40,40)">{{ Config::get('app.app_name') }}!</h1>
<div>Hi <b>{{ $username}}</b>!</div>
<div>
    <div style="padding:30px">
        {{ trans('messages.notification.mail_subject_notify') }}
        <div class="alert alert-success" role="alert">
            @foreach ($notifications as $notification)
                <div class="alert alert-success" role="alert">
                    {{ HelperService::notifyMessage($notification->type, $notification) }}
                </div>
            @endforeach
        </div>
        <div class="row">
            {{ trans('messages.notification.mail_no_received') }}
            {{ link_to_action('SettingsController@getNotification', 'Notifications Settings') }}
        </div>
    </div>
</div>

@stop