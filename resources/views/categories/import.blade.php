@extends('layouts.admin')

@section('css')
{{ HTML::style(version('css_min/categories_export_import.min.css')) }}
@stop

@section('main')

<div class="container-fluid">
    <div class="admin">
    @include('layouts.includes.sidebar_admin_setting_2')

    <div class="role col-md-10 col-sm-8 right-admin">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">{{ trans('labels.import_category') }}</div>
            </div>
            <div class="panel-body" >
                @if ($errors->has())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $message)
                            <p>{{ $message }}</p>
                        @endforeach
                    </div>
                @endif
                @if (Session::has('message'))
                    <div class="alert alert-success">
                        <p>{{ Session::get('message') }}</p>
                    </div>
                @endif
                {{ Form::open(['action' => 'CategoriesController@postImport',
                    'class' => 'form-horizontal col-md-10 col-md-offset-1 col-sm-12',
                    'role' => 'form', 'files' => true]) }}
                    <div class="form-group">
                        {{ Form::label('csv_file', trans('labels.file_csv'), ['class' => 'col-md-4 control-label']) }}
                        {{ Form::file('csv_file',
                            null,
                            null,
                            ['class' => 'form-control'])
                        }}
                    </div>
                    <div class="form-group">
                        {{ Form::label('', '', ['class' => 'col-md-4 control-label']) }}
                        {{ Form::submit(trans('buttons.import'), ['class' => 'btn btn-success']) }}
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    </div>
</div>

@stop


@section('script')
    {{ HTML::script(version('js_min/categories_export_import.min.js')) }}
@stop
