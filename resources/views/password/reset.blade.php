@extends('layouts.default')

@section('css')
    {{ HTML::style(version('css_min/oauth_authorization_login.min.css')) }}
@stop

@section('main')
    <div class="user-remind-password col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
        <div class="panel panel-info" >
            <div class="panel-heading">
                <div class="panel-title">{{ trans('labels.password_reset') }}</div>
                <div class="panel-link"><a href="{{ URL::action('UsersController@getLogin') }}">{{ trans('labels.login') }}</a></div>
            </div>
            <div class="panel-body" >
                @if ($errors->has())
                    <div class="alert alert-danger">
                        @if ($errors->any())
                            {{ implode('', $errors->all('<p>:message</p>')) }}
                        @endif
                    </div>
                @endif
                @if (Session::has('message'))
                    <div class="alert alert-success">
                        @if (Session::has('message'))
                            <p>{{ Session::get('message') }}</p>
                        @endif
                    </div>
                @endif
                {{ Form::open(['action' => 'PasswordController@postReset', 'class' => 'form-horizontal', 'role' => 'form']) }}
                    <input type="hidden" name="token" id="token-check" value="{{ $token }}">
                    
                    <div class="form-group">
                        {{ Form::label('email', trans('labels.email'), ['class' => 'col-md-4 control-label']) }}
                        <div class="col-md-8">
                            {{ Form::text('email', null, ['class' => 'form-control', 'placeholder' => trans('labels.place_holder_enter_email')]) }}
                        </div>
                    </div>
                    
                    <div class="form-group">
                        {{ Form::label('password', trans('labels.password_2'), ['class' => 'col-md-4 control-label']) }}
                        <div class="col-md-8">
                            {{ Form::password('password', ['class' => 'form-control', 'placeholder' => trans('labels.password_2')]) }}
                        </div>
                    </div>
                    
                    <div class="form-group">
                        {{ Form::label('password_confirmation', trans('labels.confirm_pwd'), ['class' => 'col-md-4 control-label']) }}
                        <div class="col-md-8">
                            {{ Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => trans('labels.confirm_pwd')]) }}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-offset-4 col-md-8">
                            {{ Form::submit(trans('labels.btn_reset_password'), ['class' => 'btn btn-primary']) }}
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        var status = document.getElementById('token-check').value;
        if (status == 'done') {
            setInterval(function() {
                window.location = '/';
            }, 2000);
        }
    </script>
@stop
