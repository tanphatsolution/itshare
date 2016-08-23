@extends('layouts.default')

@section('css')

{{ HTML::style(version('css_min/user_followers.min.css')) }}

@stop

@section('main')

@include('user.show_header')
@include('user._user_static')
<div class="container category-list list-follow">
    <div class="row user-follow">
        @include('user._list_users_follower', ['users' => $followers])
    </div>
    @if (!$hideSeeMore)
        <div class="load-more">
            <a id="see-more-user" data-message="{{ trans('messages.loading') }}">{{ trans('labels.load_more') }}</a>
        </div>
    @endif
</div>

@stop

@section('script')
    {{ HTML::script(version('js_min/user_common.min.js')) }}
    <script type="text/javascript">
        var username = '{{{ $user->username }}}';
        var followers = true;
    </script>
@stop