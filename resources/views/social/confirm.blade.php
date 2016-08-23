@extends('layouts.default')

@section('css')

{{ HTML::style(version('css_min/oauth_authorization_login.min.css')) }}

@stop

@section('main')

<div class="user-signup col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
    <div class="panel panel-info">
        <div class="panel-heading">
            <div class="panel-title">{{ trans('socials.connect_to', ['socialName' => $socialName, 'socialLink' => $socialLink]) }}</div>
        </div>
        <div class="panel-body" >
            {{ Form::open(['action' => 'SocialsController@postConfirm', 'class' => 'form-horizontal', 'role' => 'form']) }}
                {{ Form::hidden('id', $user->id) }}
                {{ Form::hidden('type', $type) }}
                {{ Form::hidden('email', $user->email) }}

                @include('elements.message_notify', ['errors' => $errors])

                <div class="form-group">
                    {{ Form::label('username', trans('messages.user.username'), ['class' => 'col-md-4 control-label']) }}
                    <div class="col-md-8">
                        {{{ $user->username }}}
                    </div>
                </div>
                <div class="form-group">
                    {{ Form::label('email', trans('messages.user.email'), ['class' => 'col-md-4 control-label']) }}
                    <div class="col-md-8">
                        {{ $user->email }}
                    </div>
                </div>
                <div class="form-group">
                    {{ Form::label('name', trans('messages.user.name'), ['class' => 'col-md-4 control-label']) }}
                    <div class="col-md-8">
                        {{ Form::text('name', $socialName , ['class' => 'form-control', 'placeholder' => trans('messages.user.name_placeholder')]) }}
                    </div>
                </div>
                <div class="form-group">
                    {{ Form::label('password', trans('messages.user.password'), ['class' => 'col-md-4 control-label']) }}
                    <div class="col-md-8">
                        {{ Form::password('password', ['class' => 'form-control', 'placeholder' => trans('messages.user.password_placeholder')]) }}
                    </div>
                </div>
                <div class="form-group">
                    <!-- Button -->
                    <div class="col-md-offset-4 col-md-8">
                        {{ Html::decode(Form::submit(trans('socials.connect'), ['class' => 'btn btn-info', 'onclick' => "javascript:ga('send', 'event', 'button', 'click', 'register_". $type . "', 1);"])) }}
                    </div>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>

@stop


@section('script')

@stop
