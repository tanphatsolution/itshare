@extends('layouts.admin')

@section('css')

{{ HTML::style(version('css_min/server_deploy.min.css')) }}

@stop

@section('main')

<div class="container-fluid">
    <div class="admin">
    @include('layouts.includes.sidebar_admin_setting_2')

    <div class="role col-md-10 col-sm-8 right-admin">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">{{ trans('labels.deploy_application') }}</div>
            </div>
            <div class="panel-body" >
                @if (Session::has('message'))
                    <div class="alert alert-danger col-md-10 col-md-offset-1 col-sm-12">
                        @if (Session::has('message'))
                            @if (is_array(Session::get('message')))
                            <p>{{ implode(Session::get('message'), '<br>') }}</p>
                            @else
                            <p>{{ Session::get('message') }}</p>
                            @endif
                        @endif
                    </div>
                @endif
                {{ Form::open(['action' => 'ServerController@postDeploy', 'class' => 'form-horizontal col-md-10 col-md-offset-1 col-sm-12', 'role' => 'form']) }}
                <div class="form-group">
                    {{ Form::label('environment', trans('messages.server.deploy_environment'), ['class' => 'col-md-4 control-label']) }}
                    <div class="col-md-8">
                        {{ Form::select('environment', ['class' => 'form-control']) }}
                    </div>
                </div>
                <div class="form-group">
                    {{ Form::label('branch', trans('messages.server.deploy_branch'), ['class' => 'col-md-4 control-label']) }}
                    <div class="col-md-8">
                        {{ Form::select('branch', App\Services\ServerService::getAllBranchs(), App\Services\ServerService::getDefaultBranchs(), ['class' => 'form-control']) }}
                    </div>
                </div>
                <div class="form-group">
                    {{ Form::label('checkout', trans('messages.server.deploy_checkout_only'), ['class' => 'col-md-4 control-label']) }}
                    <div class="col-md-8 pdt-7">
                        {{ Form::checkbox('checkout', '1', TRUE) }}
                    </div>
                </div>
                <div class="form-group">
                    {{ Form::label('composer_update', trans('messages.server.composer_update'), ['class' => 'col-md-4 control-label']) }}
                    <div class="col-md-8 pdt-7">
                        {{ Form::checkbox('composer_update', '1', FALSE) }}
                    </div>
                </div>
                <div class="form-group">
                    {{ Form::label('migrate', trans('messages.server.deploy_migrate'), ['class' => 'col-md-4 control-label']) }}
                    <div class="col-md-8 pdt-7">
                        <label>
                            {{ Form::radio('migrate', App\Services\ServerService::MIGRATE) }}
                            {{ trans('messages.server.deploy_migrate_normal') }}
                        </label>
                        <label>
                            {{ Form::radio('migrate', App\Services\ServerService::MIGRATE_SEED) }}
                            {{ trans('messages.server.deploy_migrate_seed') }}
                        </label>
                        <label>
                            {{ Form::radio('migrate', App\Services\ServerService::MIGRATE_REFRESH_SEED) }}
                            {{ trans('messages.server.deploy_migrate_refresh') }}
                        </label>
                        <label>
                            {{ Form::radio('migrate', App\Services\ServerService::MIGRATE_NOTHING, TRUE) }}
                            {{ trans('messages.server.deploy_no_migrate') }}
                        </label>
                    </div>
                </div>
                <div class="pull-right">
                    {{ Form::submit(trans('buttons.perform'), ['class' => 'btn btn-success']) }}
                    <a class="btn btn-primary" href="{{ URL::action('SettingsController@getIndex') }}">{{ trans('buttons.cancel') }}</a>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    </div>
</div>

@stop


@section('script')

@stop