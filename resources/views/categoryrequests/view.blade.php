@extends('layouts.admin')

@section('css')

{{ HTML::style(version('css_min/categoryrequest_view.min.css')) }}

@stop

@section('main')

<div class="container-fluid">
    <div class="admin">
    @include('layouts.includes.sidebar_admin_setting_2')
    <div class="role col-md-10 col-sm-8 right-admin">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">{{ trans('messages.category.request_list') }}</div>
            </div>
            <div class="panel-body" >
                <div class="alert">
                    {{ Form::open(['action' => 'CategoryRequestsController@getView', 'class' => 'form-inline', 'role' => 'search', 'method' => 'GET']) }}
                        <div class="input-group col-sm-8">
                            {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => trans('messages.category.search_hint')])}}
                            <div class="input-group-btn">
                            <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                            </div>
                        </div>
                        <div class="input-group col-sm-2">
                        {{ link_to_action('CategoryRequestsController@getView', trans('messages.category.show_all'), null, ['class' => 'btn btn-info']) }}
                        </div>
                    {{ Form::close() }}
                </div>
                <table class="table table-hover table-responsive">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ trans('labels.category_name') }}</th>
                            <th>{{ trans('labels.category_shortname') }}</th>
                            <th>{{ trans('labels.status') }}</th>
                            <th>{{ trans('labels.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($categories) >0)
                            @foreach ($categories as $category)
                                <tr>
                                    <td>{{ $category->id }}</td>
                                    <td><a href="{{ url_to_category($category->shortName) }}" class="btn btn-link">{{{ $category->name }}}</a></td>
                                    <td>{{ $category->shortName }}</td>
                                    <td>
                                        @if(is_null($category->deletedAt))
                                        	@if($category->status == true)
                                        		<label class="label label-success">{{ trans('labels.accepted') }}</label>
                                        	@elseif(!is_null($category->status))
                                            	<label class="label label-warning">{{ trans('labels.rejected') }}</label>
                                            @else
                                                <label class="label label-info">{{ trans('labels.active') }}</label>
                                            @endif
                                        @else
                                            <label class="label label-danger">{{ trans('labels.deleted') }}</label>
                                        @endif
                                    </td>
                                    <td>
                                        @if(is_null($category->deletedAt))
                                        	@if($category->status)
                                    			<a data-id="{{ $category->id }}" data-message="{{{ trans('messages.category.confirm_delete', ['name' => $category->name]) }}}" class="btn btn-link action-delete"> {{ trans('buttons.delete') }}</a>
                                        	@elseif(!is_null($category->status))
                                                <a data-id="{{ $category->id }}" data-message="{{ trans('messages.category.confirm_restore', ['name' => $category->name]) }}}" class="btn btn-link action-restore"> {{ trans('buttons.restore') }}</a>
                                            @else
		                                        {{ link_to_action('CategoryRequestsController@edit', trans('buttons.edit'), [$category->id], ['class' => 'btn btn-link']) }}
		                                        <a data-id="{{ $category->id }}" data-message="{{{ trans('messages.category.confirm_delete', ['name' => $category->name]) }}}" class="btn btn-link action-delete"> {{ trans('buttons.delete') }}</a>
		                                        <a data-id="{{ $category->id }}" data-message="{{{ trans('messages.category.confirm_accept', ['name' => $category->name]) }}}" class="btn btn-link action-accept"> {{ trans('buttons.accept') }}</a>
                                                <a data-id="{{ $category->id }}" data-message="{{{ trans('messages.category.confirm_reject', ['name' => $category->name]) }}}" class="btn btn-link action-reject"> {{ trans('buttons.reject') }}</a>
	                                    	@endif
	                                    @else
	                                        <a data-id="{{ $category->id }}" data-message="{{{ trans('messages.category.confirm_restore', ['name' => $category->name]) }}}" class="btn btn-link action-restore"> {{ trans('buttons.restore') }}</a>
	                                    @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <p class="text-info">{{ trans('messages.no_record') }}</p>
                        @endif
                    </tbody>
                </table>
                {{ $categories->appends(['name' => isset($_GET['name']) ? $_GET['name'] : null])->render(); }}
            </div>
        </div>
    </div>
    </div>
</div>

@stop


@section('script')
    {{ HTML::script(version('js_min/categoryrequest_view.min.js')) }}
@stop
