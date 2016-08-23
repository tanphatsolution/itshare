@extends('layouts.default')

@section('css')

{{ HTML::style(version('css_min/search_index.min.css')) }}

@stop

@section('main')

<div class="container category-list list-follow">

    @if (Session::has('message'))
        <div class="alert alert-danger">
            <p>{{ Session::get('message') }}</p>
        </div>
    @endif
    @if (isset($results) && empty($results->total()) && !empty($keyword))
        <div class="alert alert-warning" role="alert">{{ trans('messages.search.no_result', ['keyword' => htmlentities($keyword)]) }}</div>
    @elseif (isset($results))
        <div class="number-result">{{ Lang::choice('messages.search.found', $results->total(), ['total' => $results->total(), 'keyword' => htmlentities($keyword)]) }}</div>
        <div class="row blog-post post-list">
            @include($template['name'], [$template['variable'] => $results])
        </div>
        <div class="row">
            <div class="col-lg-12">
                {{ $results->render() }}
            </div>
        </div>
    @endif
</div>

@stop

@section('script')

{{ HTML::script(version('js_min/search_index.min.js')) }}

@stop
