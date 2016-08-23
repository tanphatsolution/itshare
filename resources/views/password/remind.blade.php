@extends('layouts.default')

@section('css')

{{ HTML::style(version('css_min/oauth_authorization_login.min.css')) }}

@stop

@section('main')

<div class="user-remind-password col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
    <div class="panel panel-info" >
        <div class="panel-heading">
            <div class="panel-title">{{ trans('labels.password_reminder') }}</div>
            <div class="panel-link"><a href="{{ URL::action('UsersController@getLogin') }}">{{ trans('labels.modal.login') }}</a></div>
        </div>
        <div class="panel-body" >
            @if ($errors->has())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $message)
                        <p>{{ $message }}</p>
                    @endforeach
                </div>
            @endif
            @if (Session::has('message'))
                <div class="alert alert-success">
                    @if (Session::has('message'))
                        <p>{{ Session::get('message') }}</p>
                    @endif
                </div>
            @endif
            {{ Form::open(['action' => 'PasswordController@postRemind', 'class' => 'form-horizontal', 'role' => 'form']) }}
                <div class="form-group">
                    {{ Form::label('email', trans('labels.email'), ['class' => 'col-md-3 control-label']) }}
                    <div class="col-md-9">
                        {{ Form::text('email', null, ['class' => 'form-control', 'placeholder' => trans('labels.place_holder_enter_email')]) }}
                    </div>
                </div>
                <div class="form-group">
                    <!-- Button -->
                    <div class="col-md-offset-3 col-md-9">
                        {{ Form::submit(trans('labels.send_reminder'), ['class' => 'btn btn-primary']) }}
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@stop