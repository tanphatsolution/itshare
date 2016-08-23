@extends('layouts.default')

@section('css')

{{ HTML::style(version('css_min/user_change_password.min.css')) }}

@stop

@section('main')

<div class="col-md-12 setting">
    @include('layouts.includes.sidebar_setting')

    <div class="role col-md-9 col-sm-8">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">{{ trans('messages.user.change_password_title') }}</div>
            </div>
            <div class="panel-body" >
                {{ Form::open(['action' => 'UsersController@postChangePassword', 'class' => 'form-horizontal', 'role' => 'form']) }}

                @include('elements.message_notify', ['errors' => $errors])

                <div class="form-group">
                    {{ Form::label('current_password', trans('messages.user.current_password'), ['class' => 'col-md-3 control-label']) }}
                    <div class="col-md-9">
                        {{ Form::password('current_password', ['class' => 'form-control', 'placeholder' => trans('messages.user.current_password_placeholder')]) }}
                    </div>
                </div>
                <div class="form-group">
                    {{ Form::label('new_password', trans('messages.user.new_password'), ['class' => 'col-md-3 control-label']) }}
                    <div class="col-md-9">
                        {{ Form::password('new_password', ['class' => 'form-control', 'placeholder' => trans('messages.user.new_password_placeholder')]) }}
                    </div>
                </div>
                <div class="form-group">
                    {{ Form::label('new_password_confirmation', trans('messages.user.new_password_confirmation'), ['class' => 'col-md-3 control-label']) }}
                    <div class="col-md-9">
                        {{ Form::password('new_password_confirmation', ['class' => 'form-control', 'placeholder' => trans('messages.user.new_password_confirmation_placeholder')]) }}
                    </div>
                </div>
                <div class="pull-right">
                    {{ Form::submit(trans('buttons.change'), ['class' => 'btn btn-success']) }}
                    <a class="btn btn-primary" href="{{ URL::action('SettingsController@getIndex') }}">{{ trans('buttons.cancel') }}</a>
                </div>
            {{ Form::close() }}
            </div>
        </div>
    </div>

</div>

@stop


@section('script')

@stop