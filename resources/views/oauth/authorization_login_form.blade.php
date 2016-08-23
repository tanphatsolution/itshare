@include('layouts.includes.head')
{{ HTML::style('css_min/oauth_authorization_login.min.css') }}

<div class="user-login col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2" style="margin-top:20px">
    <div class="panel panel-info" >
        <div class="panel-heading">
            <div class="panel-title">
                {{ trans('messages.user.login_viblo_account') }}
            </div>
        </div>
        <div class="panel-body" >
            @if ($errors->has())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $message)
                        <p>{{ $message }}</p>
                    @endforeach                    
                </div>
            @endif

            {{ Form::open(['action' => 'OAuthController@postLogin', 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'POST']) }}
                {{ Form::hidden('redirect_uri', $redirect_uri) }}
                {{ Form::hidden('response_type', $response_type) }}
                {{ Form::hidden('client_id', $client_id) }}
                <div class="input-group mg-bottom-15">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                    {{ Form::text('username', null, ['class' => 'form-control', 'placeholder' => 'Username or Email']) }}
                </div>
                <div class="input-group mg-bottom-5">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                    {{ Form::password('password', ['class' => 'form-control', 'placeholder' => 'Enter Password']) }}
                </div>
                <div class="input-group mg-bottom-15">
                    {{ Form::submit('Login', ['class' => 'btn btn-success']) }}
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
