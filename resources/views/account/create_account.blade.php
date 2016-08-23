@extends('layouts.admin')

@section('css')

{{ HTML::style(version('css_min/account_create.min.css')) }}

@stop

@section('main')

<div class="container-fluid">
    <div class="admin">
        @include('layouts.includes.sidebar_admin_setting_2')
        <div class="role col-md-10 col-sm-8 right-admin">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title">{{ trans('labels.import_file_csv') }}</div>
                </div>
                <div class="panel-body" >
                    @if (isset($messageErrors))
                        <div class="alert alert-danger col-md-10 col-md-offset-1 col-sm-12">
                            @foreach ($messageErrors as $messageError)
                                <p>{{{ $messageError }}}</p>
                            @endforeach
                        </div>
                    @endif
                    {{ Form::open(['action' => 'AccountController@postCreate', 'class' => 'form-horizontal col-md-10 col-md-offset-1 col-sm-12', 'role' => 'form', 'files' => true]) }}
                    <div class="form-group">
                        {{ Form::label('environment', trans('labels.file_csv'), ['class' => 'col-md-4 control-label']) }}
                        <div class="col-md-8">
                            {{ Form::file('csv_file', ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('environment', trans('labels.send_mail'), ['class' => 'col-md-4 control-label']) }}
                        <div class="col-md-8">
                            {{ Form::checkbox('send_mail', '1') }}
                        </div>
                    </div>
                    <div class="pull-right">
                        {{ Form::submit(trans('buttons.create'), ['class' => 'btn btn-success']) }}
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
            @if (isset($messages))
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="panel-title">{{ trans('labels.created_account') }}</div>
                    </div>
                    <div class="panel-body" >
                        <table class="table table-bordered">
                            <tr>
                                <td>{{ trans('labels.name') }}</td>
                                <td>{{ trans('labels.email') }}</td>
                                <td>{{ trans('labels.username') }}</td>
                                <td></td>
                            </tr>
                                @foreach ($messages as $key => $message)
                                    @if ($key % 2)
                                        <tr class="success">
                                            <td>{{ $message['name'] }}</td>
                                            <td>{{ $message['email'] }}</td>
                                            <td>{{ $message['username'] }}</td>
                                            <td>{{ trans('labels.create_account_success') }}</td>
                                        </tr>
                                    @else
                                        <tr class="info">
                                            <td>{{ $message['name'] }}</td>
                                            <td>{{ $message['email'] }}</td>
                                            <td>{{ $message['username'] }}</td>
                                            <td>{{ trans('labels.create_account_success') }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                        </table>
                    </div>
                </div>
             @endif
        </div>
    </div>
</div>

@stop


@section('script')

@stop