@extends('layouts.admin')

@section('css')

{{ HTML::style(version('css_min/oauth_create.min.css')) }}

@stop

@section('main')

<div class="container-fluid">
    <div class="admin">
        @include('layouts.includes.sidebar_admin_setting_2')

        <div class="role col-md-10 col-sm-8 right-admin">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title">{{ trans('labels.client.edit') }}</div>
                </div>
                <div class="panel-body" >
                    @if ($errors->has())
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $message)
                                <p>{{ $message }}</p>
                            @endforeach
                        </div>
                    @endif
                    @if (Session::has('success'))
                        <div class="alert alert-success">
                            <p>{{ Session::get('success') }}</p>
                        </div>
                    @endif
                    {{ Form::open(['action' => ['OAuthController@updateApp'], 'method' => 'PATCH', 'class' => 'form-horizontal']) }}
                        {{ Form::hidden('client_id', $oauthClient->id) }}
                        <div class="form-group">
                            <label for="client_id" class="control-label col-md-3">{{ trans('labels.client.id') }}</label>
                            <div class="col-md-6">
                                {{ Form::text('client_id', $oauthClient->id, ['class' => 'form-control', 'disabled' => 'disabled']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="client_name" class="control-label col-md-3">{{ trans('labels.client.name') }}</label>
                            <div class="col-md-6">
                                {{ Form::text('client_name', $oauthClient->name, ['class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="client_secret" class="control-label col-md-3">{{ trans('labels.client.secret') }}</label>
                            <div class="col-md-6">
                                {{ Form::text('client_secret', $oauthClient->secret, ['class' => 'form-control', 'disabled' => 'disabled']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="redirect_uri" class="control-label col-md-3">{{ trans('labels.client.redirect_uri') }}</label>
                            <div class="col-md-6">
                                {{ Form::text('redirect_uri', $oauthClient->oauthClientEndpoint()->redirect_uri, ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-3">
                                {{ Form::submit(trans('buttons.update') , ['class' => 'btn btn-info']) }}
                                <a class="btn btn-primary" href="{{ URL::action('OAuthController@getApps') }}">{{ trans('buttons.cancel') }}</a>
                            </div>
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
@stop
