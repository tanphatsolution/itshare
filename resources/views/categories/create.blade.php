@extends('layouts.admin')

@section('css')

{{ HTML::style(version('css_min/categories_create.min.css')) }}

@stop

@section('main')

<div class="container-fluid">
    <div class="admin">
    @include('layouts.includes.sidebar_admin_setting_2')

    <div class="role col-md-10 col-sm-8 right-admin">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">{{ trans('labels.create_category') }}</div>
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
                {{ Form::open(['action' => 'CategoriesController@store', 'class' => 'form-horizontal', 'role' => 'form', 'files' => true]) }}
                <div class="form-group">
                    <label for="name" class="col-md-2 control-label">{{ trans('labels.category_name') }}</label>
                    <div class="col-md-6">
                        {{Form::text('name', null, ['class' => 'form-control', 'placeholder' => trans('messages.category.enter_name')]) }}
                    </div>
                </div>
                <div class="form-group">
                <label for="short_name" class="col-md-2 control-label">{{ trans('labels.category_shortname') }}</label>
                    <div class="col-md-6">
                        {{Form::text('short_name', null, ['class' => 'form-control']) }}
                    </div>
                </div>
                <div class="form-group">
                    <label for="image" class="col-md-2 control-label">{{ trans('labels.images') }}</label>
                    <div class="col-md-6">
                        {{Form::file('image', ['id' => 'category-image', 'data-message' => trans('messages.image.invalid_file_size'), 'data-size' => Config::get('image')['max_image_size']]) }}
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-offset-2 col-md-10">
                        {{ Form::submit(trans('buttons.post.create'), ['class' => 'btn btn-success',]) }}
                    </div>
                </div>
            {{ Form::close() }}
            </div>
        </div>
    </div>
    </div>
</div>

@stop


@section('script')
    {{ HTML::script(version('js_min/categories_create.min.js')) }}
@stop
