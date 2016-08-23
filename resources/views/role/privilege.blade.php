@extends('layouts.admin')

@section('css')

{{ HTML::style(version('css_min/role_privilege.min.css')) }}

@stop

@section('main')

<div class="container-fluid">
    <div class="admin">
    @include('layouts.includes.sidebar_admin_setting_2')

    <div class="role col-md-10 col-sm-8 right-admin">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">{{ trans('messages.role.privilege_title') }}</div>
            </div>
            <div class="panel-body" >
                {{ Form::open(['action' => 'RoleController@postPrivilege', 'class' => 'form-horizontal', 'role' => 'form']) }}
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th></th>
                                <th class="center" colspan="{{ $numberOfRoles }}">{{ trans('labels.roles') }}</th>
                            </tr>
                        </thead>
                          <tr>
                            <th>{{ trans('labels.permission') }}</th>
                            @foreach ($roles as $role)
                            <th class="center">{{ $role['title'] }}</th>
                            @endforeach
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($permissions as $key => $permission)
                            @if (isset($permissionsGroup[$key]))
                            <tr class="info">
                                <td colspan="{{ ($numberOfRoles + 1) }}">{{ ucfirst($permission['resource']) }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td><i class="fa fa-angle-right"></i> {{ ucfirst($permission['action']) }}</td>
                                @foreach ($roles as $role)
                                <td class="center table-checkbox">
                                    <label>
                                        {{
                                            Form::checkbox(
                                                sprintf($inputName, $role['id'], $permission['id']),
                                                '1',
                                                $role['permission'][$permission['id']]['checked']
                                            )
                                        }}
                                    </label>
                                </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="pull-right">
                    {{ Form::submit('Update', ['class' => 'btn btn-success']) }}
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