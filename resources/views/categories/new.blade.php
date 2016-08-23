@extends('layouts.default')

@section('css')

{{ HTML::style(version('css_min/categories_new.min.css')) }}

@stop

@section('main')
<div class="col-md-12 col-sm-12 category-show">
    @include('categories.show_header')
    <div class="row">
        <div class="col-md-9">
            <div class="row col-md-offset-0">
                <div class="panel list-group-item active">{{ trans('categories.category_new_posts', ['name' => $category->name]) }}</div>
                @foreach ($posts as $post)
                    <div class="panel panel-default">
                        <div class="panel-body">
                            {{ link_to_post($post) }}
                            @if ($currentUser)
                            <div class='pull-right'>
                                <span id='stock' post_id="{{ $post->id }}" user_id="{{ $currentUser->id }}" class="glyphicon glyphicon-folder-close stock" aria-hidden="true"></span>
                            </div>
                            @endif
                        </div>
                        <div class="panel-footer">
                            @include('categories._category_badges', ['categories' => $post->categories])
                        </div>
                    </div>
                @endforeach
                {{  $posts->render() }}
            </div>
        </div>
        @include('categories.show_right')
    </div>
</div>
@stop

@section('script')
    {{ HTML::script(version('js_min/categories_new.min.js')) }}
@stop
