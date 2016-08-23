@extends('layouts.default')

@section('css')

{{ HTML::style(version('css_min/user_update.min.css')) }}

@stop

@section('main')

<div class="col-md-12 setting">
    @include('layouts.includes.sidebar_setting')

    <div class="role col-md-9 col-sm-8">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">{{ trans('messages.user.update_title') }}</div>
            </div>
            <div class="panel-body" >
            {{ Form::model($currentUser, ['action' => 'UsersController@postUpdate', 'class' => 'form-horizontal', 'role' => 'form']) }}
                @include('elements.message_notify', ['errors' => $errors])

                <div class="form-group">
                    {{ Form::label('name', trans('messages.user.name'), ['class' => 'col-md-2 control-label']) }}
                    <div class="col-md-10">
                        {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => trans('messages.user.name_placeholder')]) }}
                    </div>
                </div>
                <div class="form-group">
                    {{ Form::label('username', trans('messages.user.username'), ['class' => 'col-md-2 control-label']) }}
                    <div class="col-md-10">
                        {{ Form::text('username', null, ['class' => 'form-control disabled', 'placeholder' => trans('messages.user.username_placeholder'), 'disabled']) }}
                    </div>
                </div>
                <div class="form-group">
                    {{ Form::label('email', trans('messages.user.email'), ['class' => 'col-md-2 control-label']) }}
                    <div class="col-md-10">
                        {{ Form::text('email', null, ['class' => 'form-control disabled', 'placeholder' => trans('messages.user.email_placeholder'), 'disabled']) }}
                    </div>
                </div>
                <div class="form-group">
                    {{ Form::label('phone', trans('messages.user.phone'), ['class' => 'col-md-2 control-label']) }}
                    <div class="col-md-10">
                        {{ Form::text('phone', null, ['class' => 'form-control', 'placeholder' => trans('messages.user.phone_placeholder')]) }}
                    </div>
                </div>
                <div class="pull-right">
                    {{ Form::submit(trans('buttons.update'), ['class' => 'btn btn-success']) }}
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