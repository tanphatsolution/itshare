@extends('layouts.default')

@section('css')
    {{ HTML::style('css/faq/home/faq-page.css') }}
    {{ HTML::style('css/faq/home/group.css') }}
    {{ HTML::style('css/faq/home/group-detail.css') }}
@stop

@section('main')
    <div class="faq">
        <div class="post-detail row">
            <div class="col-md-9 col-lg-9 post-left">
                <div class="wrap-questions">
                    <div class="wrap-nav-tabs">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#">Latest</a></li>
                            <li><a href="#">Active</a></li>
                            <li><a href="#">Unanswered</a></li>
                            <li><a href="#">Unsolved</a></li>
                            <li><a href="#">Solved</a></li>
                            <li><a href="#">Top Clips</a></li>
                            <li><a href="#">Top Views</a></li>
                        </ul>
                        <div class="nav-tabs-dropdown">
                            <a class="nav-tabs-dropdown-lv1">
                                <span>Latest</span>
                            </a>
                            <div class="nav-tabs-dropdown-lv2" style="display: none;">
                                <ul class="root">
                                    <li><a href="#">Active</a></li>
                                    <li><a href="#">Unanswered</a></li>
                                    <li><a href="#">Unsolved</a></li>
                                    <li><a href="#">Solved</a></li>
                                    <li><a href="#">Top Clips</a></li>
                                    <li><a href="#">Top Views</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <a href="#" class="btn-view-all">View 4 new questions</a>
                    <ul class="faq-list">
                        @include('faq.home.list_question')
                    </ul>
                    <a onclick="home.onClickLoadMore()" class="btn-load-more"
                       id="load_more_question">{{ Lang::get('labels.load_more') }}</a>
                </div>
            </div>
            <div class="col-md-3 col-lg-3 post-right">
                <a href="{{ route('faq.questions.create') }}">
                    <button class="btn-question">
                        <img src="{{asset('img/btn-header-post.png')}}" class="img-01">
                        <img src="{{asset('img/btn-header-post2.png')}}" class="img-02">
                        {{ Lang::get('labels.question.create_a_question') }}
                    </button>
                </a>
                <div class="module response-time">
                    <span class="title">Average response time</span>
                    <span class="time">1h 30m</span>
                </div>
                @include('faq.sidebarleft.latest')
                @include('faq.sidebarleft.ranking')
                @include('faq.sidebarleft.category')
            </div>
        </div>
    </div>
@endsection

@section('script')
    {{ HTML::script('js/faq/home.js') }}
    {{ HTML::script('js/relationships.js') }}
    <script>
        var loading = {{ json_encode(['loading' => Lang::get('messages.loading'), 'loaded' => Lang::get('labels.load_more')]) }};
        var home = new home(loading);
    </script>
@endsection