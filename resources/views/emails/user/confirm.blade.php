@extends('emails.layouts.default')

@section('main')
    {{ isset($lang) ? App::setLocale($lang) : App::setLocale('en') }}
    <h1 style="font-size:20px;color:rgb(40,40,40)">{{ trans('messages.send_mail.title_1', ['appName' => Config::get('app.app_name')]) }}</h1>
    <div>{{ trans('messages.send_mail.header', ['appName' => Config::get('app.app_name')]) }}</div>
    <div>
        <div style="padding:30px">
            <a title="{{ trans('messages.user.confirm_email') }}"
                style="font-size:14px;font-weight:100;font-family:Helvetica,Arial,sans-serif;text-transform:uppercase;text-align:center;
                letter-spacing:1px;text-decoration:none;line-height:62px;display:block;width:300px;min-height:60px;color:black;
                background:#e1df19;margin:0px auto"
                href="{{ URL::action('UsersController@getConfirm', [$id, $activeToken, true]) }}"
                target="_blank">
                {{ trans('messages.send_mail.confirm') }}
            </a>
        </div>
    </div>
    @if (isset($password) && !empty($password) && isset($username) && !empty($username))
        <div>
            {{ trans('messages.send_mail.your_username', ['username' => $username]) }}
        </div>
        <div>
            {{ trans('messages.send_mail.your_password', ['password' => $password]) }}
        </div>
    @endif
@stop
