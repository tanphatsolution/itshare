@extends('layouts.default')

@section('css')

{{ HTML::style(version('css_min/oauth_authorization_login.min.css')) }}

@stop

@section('main')

<div class="user-active col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
    <div class="panel panel-info" >
        <div class="panel-heading">
            <div class="panel-title">{{ trans('labels.confirm_account') }}</div>
            <div class="panel-link"><a href="{{ URL::action('UsersController@getLogin') }}">{{ trans('labels.modal.login') }}</a></div>
        </div>
        <div class="panel-body" >
            @include('elements.message_notify', ['errors' => $errors])

            {{ Form::open(['action' => 'UsersController@postConfirm', 'class' => 'form-horizontal', 'role' => 'form']) }}
                <div class="form-group">
                    {{ Form::label('email', 'Email', ['class' => 'col-md-3 control-label']) }}
                    <div class="col-md-9">
                        {{ Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Enter Your Email']) }}
                    </div>
                </div>
                <div class="form-group">
                    <!-- Button -->
                    <div class="col-md-offset-3 col-md-9">
                        {{ Form::submit('Send Confirm Email', ['class' => 'btn btn-primary']) }}
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@stop