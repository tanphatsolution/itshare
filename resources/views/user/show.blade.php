@extends('layouts.default')

@section('css')

{{ HTML::style(version('css_min/user_show.min.css')) }}

@stop

@section('main')
@include('user.show_header')
<div class="container box-statistical">
    <div class="col-lg-12 content content-skill">
        <div class="skill">
            <div class="tendency-of-posts">
                <h4>{{ trans('labels.skill') }}</h4>
                @if (count($userSkills))
                    @include('post._list_user_skills', [$userSkills, 'lang' => $langAddress])
                @else
                    <div class="text-warning">{{ trans('labels.no_skill') }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@include('user._user_static')
<div class="container">
    <div class="row blog-post">

        @include('post._list_posts_user', ['posts' => $publishedPosts])
    </div>
    @if (!$hideSeeMore)
        <div class="load-more fake-link">
            <a id="seeMorePost" data-message="{{ trans('messages.loading') }}">{{ trans('labels.load_more') }}</a>
        </div>
    @endif
</div>

@stop

@section('script')
    {{ HTML::script(version('js_min/user_show.min.js'), ['defer' => 'defer']) }}
    <script type="text/javascript">
        var categories = {{ json_encode($categories) }};
        var skills = {{ \App\Services\HelperService::getSkillBarchart($userSkills) }};
        var username = '{{{ $user->username }}}';
        var showPosts = true;
        $(function() {
            drawPieChart('#piechart', categories);
            drawBarChart('#barchart', skills);
        });
    </script>
@stop
