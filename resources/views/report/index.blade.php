@extends('layouts.admin')

@section('css')

{{ HTML::style(version('css_min/report_index.min.css')) }}

@stop

@section('main')

<div class="container-fluid">
    <div class="admin">
    @include('layouts.includes.sidebar_admin_setting_2')

    <div class="role col-md-10 col-sm-8 right-admin">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">{{ trans('messages.report.index_title') }}</div>
            </div>
            <div class="panel-body" >
                @if (Session::has('message'))
                    <div class="alert {{ Session::get('type') }}">
                        <p>{{ Session::get('message') }}</p>
                    </div>
                @endif
                <div class="alert hide">
                    <p id='ajax_msg'></p>
                </div>
                {{ Form::open(['action' => 'ReportsController@process', 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form', 'id' => 'report-form']) }}
                    <div class="form-group">
                        {{ Form::label('status', trans('messages.report.filter'), ['class' => 'col-sm-2 control-label']) }}
                        <div class="col-sm-3">
                            {{ Form::select('status', \App\Services\ReportService::getAllStatuses(), $status, ['class' => 'form-control', 'id' => 'filter']); }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('batch', trans('messages.report.batch_proccess'), ['class' => 'col-sm-2 control-label']) }}
                        <div class="col-sm-3">
                            {{ Form::select('batch', App\Services\ReportService::getAllBatchs(), NULL, ['class' => 'form-control', 'id' => 'batch']); }}
                        </div>
                    </div>
                    @if ($reports->count() > 0 )
                        <div id="reportContainer" class="table-responsive">
                            @include('report.table', ['reports' => $reports])
                        </div>
                    @else
                        <div id="reportContainer" class="alert alert-warning" role="alert">
                            {{ trans('messages.report.no_report') }}
                        </div>
                    @endif
                    <div class="pull-left">
                        {{ $reports->render() }}
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
    <script type="text/javascript">
        var filterUrl = '{{ URL::action('ReportsController@index') }}' + '/?status=';
    </script>
    {{ HTML::script(version('js_min/report_index.min.js')) }}
@stop
