@extends('layouts.default')

@section('css')

{{ HTML::style(version('css_min/categoryrequest_create.min.css')) }}

@stop

@section('main')

<div class="col-md-12 setting">
    @include('layouts.includes.navbar_admin_setting')

    <div class="role col-md-12 col-sm-8">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">{{ trans('labels.request_category') }}</div>
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
                {{ Form::open(['action' => 'CategoryRequestsController@store', 'class' => 'form-horizontal', 'role' => 'form', 'files' => true]) }}
                <div class="form-group">
                    <label for="name" class="col-md-2 control-label">{{ trans('labels.category_name') }}</label>
                    <div class="col-md-6">
                        {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => trans('messages.category.name_example')]) }}
                    </div>
                </div>
                <div class="form-group">
                <label for="short_name" class="col-md-2 control-label">{{ trans('labels.category_shortname') }}</label>
                    <div class="col-md-6">
                        {{ Form::text('short_name', null, ['class' => 'form-control', 'placeholder' => trans('messages.category.short_name_example')]) }}
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

@stop


@section('script')
    {{ HTML::script(version('js_min/categoryrequest_create.min.js')) }}
@stop
