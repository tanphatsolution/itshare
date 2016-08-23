@extends('layouts.admin')

@section('css')

{{ HTML::style(version('css_min/role_change.min.css')) }}

@stop

@section('main')
<div class="container-fluid">
    <div class="admin">
    @include('layouts.includes.sidebar_admin_setting_2')

    <div class="role col-md-10 col-sm-8 right-admin">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">{{ trans('messages.role.change_title') }}</div>
            </div>
            <div class="panel-body" >
                {{ Form::open(['action' => 'RoleController@getChange', 'class' => 'form-inline', 'role' => 'search', 'method' => 'GET']) }}
                    <div class="input-group col-sm-10 col-sm-offset-1">
                        {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => trans('messages.role.search_hint')])}}
                        <div class="input-group-btn">
                        <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                        </div>
                    </div>
                {{ Form::close() }}
                @if (Session::has('message'))
                    <div class="alert mg-top-15 {{ Session::get('type') }}">
                        <p>{{ Session::get('message') }}</p>
                    </div>
                @endif
                {{ Form::open(['action' => 'RoleController@postChange', 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form', 'id' => 'change-role-form']) }}
                    @if ($users->total())
                        {{ Form::hidden('user_id', null) }}
                        {{ Form::hidden('role_id', null) }}
                        <div class="table-responsive mg-top-15">
                            <table class="table table-bordered">
                                <thead>
                                  <tr>
                                    <th>{{ trans('messages.role.user_id') }}</th>
                                    <th>{{ trans('messages.role.user_name') }}</th>
                                    <th>{{ trans('messages.role.user_username') }}</th>
                                    <th>{{ trans('messages.role.user_role') }}</th>
                                  </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{{ $user->name }}}</td>
                                        <td>{{{ $user->username }}}</td>
                                        <td>{{
                                                Form::select('type', App\Services\RoleService::getAllRoles(), $user->role->roleId, [
                                                    'class' => 'form-control action-change-role',
                                                    'data-message' => trans('messages.role.confirm_change', ['username' => $user->username]),
                                                    'data-yes' => trans('buttons.yes'),
                                                    'data-no' => trans('buttons.no'),
                                                    'data-user-id' => $user->id,
                                                ])
                                            }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-warning mg-top-15" role="alert">
                            {{ trans('messages.role.no_user', ['name' => $name])}}
                        </div>
                    @endif
                    <div class="pull-left">
                        {{ $users->appends(['name' => $name])->render() }}
                    </div>
                {{ Form::close() }}
                <div class="pull-right">
                    <a class="btn btn-primary" href="{{ URL::previous() }}">{{ trans('buttons.back') }}</a>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>

@stop


@section('script')

{{ HTML::script(version('js_min/role_change.min.js')) }}

@stop
