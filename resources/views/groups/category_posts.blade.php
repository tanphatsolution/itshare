@extends('layouts.group')

@section('group-css')
{{ HTML::style(version('css_min/groups_category_posts.min.css')) }}
@stop

@section('group-main')

    <div class="box-header-category">
        <span class="category-follow-span">{{{ $category->name }}}</span>
    </div>

    <div class="blog-post theme-post">
        @foreach($posts as $post)
            @include('groups._a_category_post', ['post' => $post, 'inList' => true])
        @endforeach
    </div>

    <div class="load-more {{ ($hideSeeMore) ? 'hidden' : '' }}">
        <span class="fake-a">{{ trans('labels.load_more') }}</span>
    </div>

@stop

@section('group-script')
    <script type="text/javascript">
        var groupEncryptedId = "{{ $group->encrypted_id }}";
        var categoryName = "{{ $category->short_name }}";
        var pageCount = 1;
    </script>

    {{ HTML::script(version('js_min/groups/groups_category_posts.min.js')) }}
@stop
