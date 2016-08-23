@extends('layouts.default')

@section('css')

{{ HTML::style(version('css_min/home_index.min.css')) }}

@stop

@section('jumbotron')

<div class="jumbotron">
    <div class="container">
        <h1>{{ trans('messages.home.techblog') }}</h1>
        <p class="text-slide">{{ trans('messages.home.about') }}</p>
        @if (!Auth::check())
            <p class="btn-join">
                <a class="btn btn-primary btn-lg" href="{{ action('UsersController@getRegister') }}" role="button">{{ trans('labels.signup') }}</a>
            </p>
        @else
            <p class="btn-join">
                <a class="btn btn-primary btn-lg" href="{{ action('PostsController@create') }}" role="button">+{{ trans('labels.post') }}</a>
            </p>
        @endif
    </div>
</div>

@stop

@section('main')

@if (count($posts))
<div class="container">
    <div class="box-title">
        <h2><a>{{ trans('labels.new_post') }}</a></h2>
        <div class="clear"></div>
    </div>
</div>

<div class="blog-post">
    @foreach($posts as $post)
        <div class="col-sm-6 col-md-4 col-lg-3">
            @include('post._a_post', ['post' => $post])
        </div>
    @endforeach
</div>
<div class="container">
    <div class="box-title-bottom">
        <a class="btn seemore" href="{{ action('PostsController@getIndex') }}">{{ trans('labels.load_more') }}</a>
        <div class="clear"></div>
    </div>
</div>
@endif

@stop


@section('script')

{{ HTML::script(version('js_min/home_index.min.js')) }}

@if(Session::has('feedbackCreatedMessage'))
    <script>alert('{{ Session::get("feedbackCreatedMessage") }}');</script>
@endif

@stop
