@extends('layouts.default')

@section('css')

{{ HTML::style(version('css_min/oauth_authorization_login.min.css')) }}

@stop

@section('main')

<div class="user-active col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
    <div class="panel panel-info" >
        <div class="panel-heading">
            <div class="panel-title">{{ trans('messages.work_email.confirm_work_email') }}</div>
        </div>
        <div class="panel-body" >
            @if (isset($error) && $error)
                <div class='alert alert-danger'>
                    {{ trans('messages.work_email.not_active') }}
                </div>
            @else
                <div class="alert alert-success">
                    {{ trans('messages.work_email.actived') }}
                </div>
            @endif

        </div>
    </div>
</div>

@stop
