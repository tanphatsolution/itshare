@extends('layouts.admin')

@section('css')

{{ HTML::style(version('css_min/categories_edit.min.css')) }}

@stop

@section('main')
<div class="container-fluid">
    <div class="admin">
    @include('layouts.includes.sidebar_admin_setting_2')

    <div class="role col-md-10 col-sm-8 right-admin">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">{{ $title }}</div>
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
                @if (Session::has('warning'))
                    <div class="alert alert-warning">
                        <p>{{ Session::get('warning') }}</p>
                    </div>
                @endif
                {{ Form::model(
                    $category,
                    [
                        'action' => ['CategoriesController@update', $category->id],
                        'method'=>'PATCH', 'role'=> 'form',
                        'class' => 'form-horizontal',
                        'files' => true
                    ]
                )}}
                <div class="form-group">
                    <label for="name" class="control-label col-md-3">{{ trans('labels.category_name') }}</label>
                    <div class="col-md-6">
                        {{ Form::text('name', $category->name, ['class'=>'form-control']) }}
                    </div>
                </div>
                <div class="form-group">
                    <label for="short_name" class="control-label col-md-3">{{ trans('labels.category_shortname') }}</label>
                    <div class="col-md-6">
                        {{ Form::text('short_name', $category->shortName, ['class'=>'form-control']) }}
                    </div>
                </div>
                <div class="form-group">
                    <label for="name" class="control-label col-md-3">Image</label>
                    <div class="col-md-6">
                        {{ Form::file('image', null, ['class'=>'form-control']) }}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6 col-md-offset-3">
                        {{ Form::submit(trans('buttons.update') , ['class' => 'btn btn-info']) }}
                        <a class="btn btn-primary" href="{{ URL::action('CategoriesController@getView') }}">{{ trans('buttons.cancel') }}</a>
                    </div>
                </div>
                {{ Form::close()}}
            </div>
        </div>
    </div>
    </div>
</div>
@stop

@section('script')

@stop