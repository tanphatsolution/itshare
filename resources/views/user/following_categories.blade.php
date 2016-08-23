@extends('layouts.default')

@section('css')

{{ HTML::style(version('css_min/following_cagtegories.min.css')) }}

@stop

@section('main')
@include('user.show_header')
@include('user._user_static')
<div class="container following-categories category-list">
    <div class="row category-container">
        @include('categories._list_categories', ['categories' => $categories])
    </div>
    @if (!$hideSeeMore)
        <div class="load-more fake-link">
            <a id="see-more-category" data-message="{{ trans('messages.loading') }}">
                {{ trans('labels.load_more') }}
            </a>
        </div>
    @endif
</div>
@stop

@section('script')
    {{ HTML::script(version('js_min/following_cagtegories.min.js')) }}
    <script type="text/javascript">
        var username = '{{{ $user->username }}}';
        var showCategories = true;
    </script>
@stop