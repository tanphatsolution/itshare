@extends('emails.layouts.default')

@section('main')
{{ isset($lang) ? App::setLocale($lang) : App::setLocale('en') }}
<h1 style="font-size:20px;color:rgb(40,40,40)">{{ trans('messages.user.email_password_header') }}</h1>
<div>{{ trans('messages.user.email_password_greeting', ['name' => $user->name]) }}<br>
</div>
<div>
    {{ trans('messages.user.email_password_content', ['updatedAt' => $updatedAt]) }}
</div>
@stop