@extends('layouts.default')

@section('css')

{{ HTML::style(version('css_min/user_stock.min.css')) }}

@stop

@section('main')

@include('user.show_header')

@include('user._user_static')
<div class="container">
    <div class="row blog-post">
        @include('post._list_posts_user', ['posts' => $stockedPost])
    </div>
    @if (!$hideSeeMore)
        <div class="load-more fake-link">
            <a id="seeMorePost" data-message="{{ trans('messages.loading') }}">{{ trans('labels.load_more') }}</a>
        </div>
    @endif
</div>

@stop

@section('script')
    {{ HTML::script(version('js_min/user_common.min.js')) }}
    <script type="text/javascript">
        var username = '{{{ $user->username }}}';
        var userStocked = true;
    </script>
@stop