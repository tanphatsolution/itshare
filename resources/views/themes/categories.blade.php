@extends('layouts.default')

@section('css')
{{ HTML::style(version('css_min/themes_categories.min.css')) }}
@stop

@section('main')

<div class="box-header-category">
    <div class="category-follow-span-theme"><a href="{{ URL::to("theme/$lang/".$themeShortName) }}">{{ $themeName }}</a></div><br/>
    <div class="container">
        @if (isset($category) && !empty($category['theme_languages']))
            <span class="category-follow-span">{{ $category['theme_languages'][0]['name'] }}</span>
            <div class="btn-tag-follow theme-create-post">
                <a class="btn-post" href="{{ URL::to('posts/create/' . $category['id']) }}">{{ trans('labels.write_post') }}</a>
                <div class="clear-both">&nbsp;</div>
            </div>
        @endif
        <div class="btn-post-follow">
            {{ isset($total) ? $total : 0 }}
            {{ \App\Services\HelperService::myPluralizer(trans('categories.post'), (isset($total) ? $total : 0), $lang) }}
            <span class="text-delimiter">|</span>
            {{ \App\Services\HelperService::myPluralizer(trans('categories.member_posted', ['member' => $author]), $author, $lang) }}
        </div>
    </div>
</div>

<div class="container">
    <div class="box-title box-title-child">
        @include('categories._tab_theme', ['category' => $category, 'tab' => $tab])
    </div>
    <div class="row blog-post">
        @foreach($posts as $post)
            @include('post._a_post', ['post' => $post, 'inList' => true])
        @endforeach
    </div>
    <div class="load-more {{ ($hideSeeMore) ? 'hidden' : '' }}">
        <a class="" href="javascript:void(0)">{{ trans('labels.load_more') }}</a>
    </div>
</div>

@stop

@section('script')
    <script type="text/javascript">
        var categoryName = "{{ $category['short_name'] }}";
        var subjectCategoryName = "{{ $category['theme_subject_short_name'] }}";
        var pageCount = 1;
    </script>

    {{ HTML::script(version('js_min/themes_categories.min.js')) }}
@stop
