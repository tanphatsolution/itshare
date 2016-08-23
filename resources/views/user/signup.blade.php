@extends('layouts.default')

@section('css')

{{ HTML::style(version('css_min/oauth_authorization_login.min.css')) }}

@stop

@section('main')

<div class="user-signup col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
    <div class="panel panel-info">
        <div class="panel-heading">
            <div class="panel-title">{{ trans('messages.user.signup_title') }}</div>
            <div class="panel-link"><a href="{{ URL::action('UsersController@getLogin') }}">{{ trans('labels.login') }}</a></div>
        </div>
        <div class="panel-body" >
            {{ Form::open(['action' => 'UsersController@postSignup', 'class' => 'form-horizontal', 'id' => 'signup-form', 'role' => 'form']) }}
                <div id="message_error"></div>
                <div class="form-group">
                    {{ Form::label('name', trans('labels.name'), ['class' => 'col-md-4 control-label']) }}
                    <div class="col-md-8">
                        {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => trans('labels.name_2')]) }}
                    </div>
                </div>
                <div class="form-group">
                    {{ Form::label('username', trans('labels.username'), ['class' => 'col-md-4 control-label']) }}
                    <div class="col-md-8">
                        {{ Form::text('username', null, ['class' => 'form-control', 'placeholder' => trans('labels.username')]) }}
                    </div>
                </div>
                <div class="form-group">
                    {{ Form::label('email', trans('labels.email'), ['class' => 'col-md-4 control-label']) }}
                    <div class="col-md-8">
                        {{ Form::text('email', null, ['class' => 'form-control', 'placeholder' => trans('labels.email_2')]) }}
                    </div>
                </div>
                <div class="form-group">
                    {{ Form::label('password', trans('labels.password_2'), ['class' => 'col-md-4 control-label']) }}
                    <div class="col-md-8">
                        {{ Form::password('password', ['class' => 'form-control', 'placeholder' => trans('labels.password')]) }}
                    </div>
                </div>
                <div class="form-group">
                    {{ Form::label('password_confirmation', trans('labels.confirm_pwd'), ['class' => 'col-md-4 control-label']) }}
                    <div class="col-md-8">
                        {{ Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => trans('labels.confirm_pwd')]) }}
                    </div>
                </div>
                <div class="form-group">
                    <label for="checkbox" class="col-md-4 control-label"></label>
                    <div class="col-md-8">
                        <input type="checkbox" id="tos-check"> {{trans('labels.agree_tos')}} <a href ='/terms' class="btn btn-link" target="_blank">{{trans('labels.see_more')}}</a>
                    </div>
                </div>
                <div class="form-group">
                    <!-- Button -->
                    <div class="col-md-offset-4 col-md-8">
                        {{ Html::decode(Form::submit(trans('messages.user.signup_title'), ['class' => 'btn btn-info', 'id' => 'signup-submit-btn', 'onclick' => "javascript:ga('send', 'event', 'button', 'click', 'register', 1);"])) }}
                    </div>
                </div>
                <div class="form-group form-divider">
                    <div class="mg-top-15 alternative-method">
                        <a class="btn btn-social btn-facebook mg-left-15" href="{{ URL::action('SocialsController@getFacebook') }}">
                            <i class="fa fa-facebook"></i> {{ trans('socials.sign_up_with_facebook') }}
                        </a>
                        <a class="btn btn-social btn-google-plus mg-left-15" href="{{ URL::action('SocialsController@getGoogle') }}">
                            <i class="fa fa-google-plus"></i> {{ trans('socials.sign_up_with_google') }}
                        </a>
                    </div>
                    <div class="mg-top-15 alternative-method">
                        <a class="btn btn-social btn-github mg-left-15" href="{{ URL::action('SocialsController@getGithub') }}">
                            <i class="fa fa-github"></i> {{ trans('socials.sign_up_with_github') }}
                        </a>
                    </div>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

@stop

@section('script')
    {{ HTML::script(version('js_min/user_login.min.js')) }}
@stop
