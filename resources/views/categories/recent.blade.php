@extends('layouts.default')

@section('css')
    {{ HTML::style(version('css_min/categories_new.min.css')) }}
@stop

<div class="col-lg-12">
    <div class="container">
        <div class="box-title box-title-child">
            @include('categories._tab')
        </div>
        <div class="row blog-post">
            @foreach($posts as $post)
                @include('post._a_post')
            @endforeach
        </div>
        <div class="load-more hidden">
            <a class="" href="javascript:void(0)">Load More</a>
        </div>
    </div>
</div>

@section('script')
    {{ HTML::script(version('js_min/categories_new.min.js')) }}
@stop
