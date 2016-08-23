@extends('layouts.default')

@section('css')

{{ HTML::style(version('css_min/oauth_authorization_login.min.css')) }}

@stop

@section('main')

<div class="user-login col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
    <div class="panel panel-info" >
        <div class="panel-heading">
            <div class="panel-title">{{ trans('labels.modal.login') }}</div>
            <div class="panel-link"><a href="{{ URL::action('PasswordController@postRemind') }}">{{ trans('labels.forgot_password') }}</a></div>
        </div>
        <div class="panel-body" >
            <div id="message_error"></div>

            @include('elements.message_notify', ['errors' => $errors, 'id' => 'alert-message'])

            @if (Session::has('success'))
                <div class="alert alert-success" role="alert">
                    <p>{{ Session::get('success') }}</p>
                </div>
            @endif

            {{ Form::open(['action' => 'UsersController@postLogin', 'class' => 'form-horizontal', 'id' => 'login-form', 'role' => 'form']) }}
                <div class="input-group mg-bottom-15">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                    {{ Form::text('username', null, ['class' => 'form-control', 'placeholder' => trans('labels.username_or_email')]) }}
                </div>
                <div class="input-group mg-bottom-5">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                    {{ Form::password('password', ['class' => 'form-control', 'placeholder' => trans('labels.password')]) }}
                </div>
                <div class="input-group mg-bottom-15">
                    <div class="checkbox">
                        <label>
                            {{ Form::checkbox('remember', 1, true) }}
                            <span>{{ trans('labels.remember_me') }}</span>
                        </label>
                    </div>
                </div>
                <div class="input-group mg-bottom-15">
                    {{ Form::submit(trans('labels.login'), ['class' => 'btn btn-success']) }}
                    <div class="mg-top-15 alternative-method">
                        <span>
                            {{ trans('labels.dont_have_account') }}
                        </span>
                        <a href="{{ URL::action('UsersController@getRegister') }}">{{ trans('messages.user.signup_here') }}</a>
                    </div>
                    <div class="mg-top-15 alternative-method">
                        <span>
                            {{ trans('labels.resend_activation_email') }}
                        </span>
                        <a href="{{ URL::action('UsersController@getConfirm') }}">{{ trans('labels.active_here') }}</a>
                    </div>
                </div>
                <div class="form-group form-divider">
                    <div class="mg-top-15 alternative-method">
                        <a class="btn btn-social btn-facebook mg-left-15" href="{{ URL::action('SocialsController@getFacebook') }}">
                            <i class="fa fa-facebook"></i> {{ trans('socials.login_with_facebook') }}
                        </a>
                        <a class="btn btn-social btn-google-plus mg-left-15" href="{{ URL::action('SocialsController@getGoogle') }}">
                            <i class="fa fa-google-plus"></i> {{ trans('socials.login_with_google') }}
                        </a>
                    </div>
                    <div class="mg-top-15 alternative-method">
                        <a class="btn btn-social btn-github mg-left-15" href="{{ URL::action('SocialsController@getGithub') }}">
                            <i class="fa fa-github"></i> {{ trans('socials.login_with_github') }}
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@stop


@section('script')
    {{ HTML::script(version('js_min/user_login.min.js')) }}
@stop
