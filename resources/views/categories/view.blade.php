@extends('layouts.admin')

@section('css')

{{ HTML::style(version('css_min/categories_view.min.css')) }}

@stop

@section('main')

<div class="container-fluid">
    <div class="admin">
    @include('layouts.includes.sidebar_admin_setting_2')

    <div class="role col-md-10 col-sm-8 right-admin">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">{{ trans('messages.category.manage_title') }}</div>
            </div>
            <div class="panel-body" >
                <div class="alert">
                    {{ Form::open(['action' => 'CategoriesController@getView', 'class' => 'form-inline', 'role' => 'search', 'method' => 'GET']) }}
                        <div class="input-group col-sm-8">
                            {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => trans('messages.category.search_hint')])}}
                            <div class="input-group-btn">
                            <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                            </div>
                        </div>
                        <div class="input-group col-sm-2">
                        {{ HTML::linkAction('CategoriesController@getView', trans('messages.category.show_all'), null, ['class' => 'btn btn-info']) }}
                        </div>
                    {{ Form::close() }}
                </div>
                <table class="table table-hover table-responsive">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ trans('labels.category_name') }}</th>
                            <th>{{ trans('labels.category_shortname') }}</th>
                            <th>{{ trans('labels.images') }}</th>
                            <th>{{ trans('labels.posts') }}</th>
                            <th>{{ trans('labels.followers') }}</th>
                            <th>{{ trans('labels.status') }}</th>
                            <th>{{ trans('labels.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($categories) >0)
                            @foreach ($categories as $category)
                                <tr>
                                    <td>{{ $category->id}}</td>
                                    <td><a href="{{ url_to_category($category->shortName) }}" class="btn btn-link">{{{ $category->name }}}</a></td>
                                    <td>{{ $category->shortName}}</td>
                                    <td>{{ HTML::image($category->getImage(), $category->name, ['class' => 'img-preview'])}}</td>
                                    <td>{{ $category->posts_count}}</td>
                                    <td>{{ $category->followers_count}}</td>
                                    <td>
                                        @if(is_null($category->deletedAt))
                                            @if ($category->filtered)
                                                <label class="label label-warning">{{ trans('labels.filtered') }}</label>
                                            @else
                                                <label class="label label-info">{{ trans('labels.active') }}</label>
                                            @endif
                                        @else
                                            <label class="label label-danger">{{ trans('labels.deleted') }}</label>
                                        @endif
                                    </td>
                                    <td>
                                        @if(is_null($category->deletedAt))
                                            {{ HTML::linkAction('CategoriesController@edit', trans('buttons.edit'), [$category->id], ['class' => 'btn btn-link']) }}
                                            @if ($category->filtered)
                                                <a data-id="{{ $category->id }}" data-message="{{{ trans('messages.category.confirm_unfilter', ['name' => $category->name]) }}}" class="btn btn-link action-unfilter"> {{ trans('buttons.unfilter') }}</a>
                                            @else
                                                <a data-id="{{ $category->id }}" data-message="{{{ trans('messages.category.confirm_filter', ['name' => $category->name]) }}}" class="btn btn-link action-filter"> {{ trans('buttons.filter') }}</a>
                                            @endif
                                            <a data-id="{{ $category->id }}" data-message="{{{ trans('messages.category.confirm_delete', ['name' => $category->name]) }}}" class="btn btn-link action-delete"> {{ trans('buttons.delete') }}</a>
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
    {{ HTML::script(version('js_min/categories_view.min.js')) }}
@stop
