@extends('layouts.admin')

@section('css')

{{ HTML::style(version('css_min/server_deploy.min.css')) }}

@stop

@section('main')

<div class="container-fluid">
    <div class="admin">
    @include('layouts.includes.sidebar_admin_setting_2')

    <div class="col-md-10 col-sm-8 right-admin">
        <div class="alert alert-success text-center" role="alert">
            {{ trans('messages.setting.manager') }}
        </div>
    </div>
    </div>
</div>

@stop


@section('script')

@stop