@extends('layouts.default')

@section('css')

{{ HTML::style(version('css_min/categories_show.min.css')) }}

@stop

@section('main')
<div class="box-header-category">
    <div class="container">
        <span class="category-follow-span">{{{ $category->name }}}</span>
        <div class="btn-tag-follow">
            @if (Auth::check())
                @if ($category->isFollowingBy($currentUser))
                    @include('categories._unfollow')
                @else
                    @include('categories._follow')
                @endif
            @endif
            <div class="clear-both"></div>
        </div>
        <div class="btn-post-follow">
            {{ isset($category->categories_count) ? $category->categories_count : 0 }} {{ App\Services\HelperService::myPluralizer(trans('categories.post'), isset($category->categories_count) ? $category->categories_count : 0, $lang) }}
            <span class="text-delimiter">|</span>
            <span id="category-follow-count-{{ $category->id }}" class="text-counter">{{ $category->followers->count() }}</span> {{ App\Services\HelperService::myPluralizer(trans('categories.follower'), $category->followers->count(), $lang) }}
        </div>
    </div>
</div>

<div class="container">
    <div class="box-title box-title-child">
        @include('categories._tab', ['tab' => $tab])
    </div>
    <div class="row blog-post">
        @foreach($posts as $post)
            @include('post._a_post', ['post' => $post])
        @endforeach
    </div>
    <div class="load-more {{ $hasMore ? '' : 'hidden' }}">
        <a class="" href="javascript:void(0)">{{ trans('labels.load_more') }}</a>
    </div>
</div>
@stop

@section('script')
    {{ HTML::script(version('js_min/categories_new.min.js')) }}
    <script type="text/javascript">
        var category = '{{ $category->shortName }}';
    </script>
@stop
