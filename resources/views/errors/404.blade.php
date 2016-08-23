@extends('layouts.error')

@section('css')
    {{ HTML::style('css_min/errors_404.min.css') }}
@stop
@section('main')
    {{ HTML::style('css_min/errors_404.min.css') }}
    <div class="notfound">
        <div class="container">
            <div class="row">
                <div class="col-lg-3"></div>
                <div class="col-lg-6">
                    <p class="notice-miss">{{ trans('errors.notice') }}</p>
                    <a class="btn-gohome" href="{{ route('getTopPage') }}">{{ trans('errors.go_home') }} <img src="{{ asset('img/btn-gohome.png') }}"></a>
                    {{ Form::open(['action' => 'SearchsController@postIndex', 'role' => 'search', 'id' => 'header-search-form']) }}
                    <div class="input-group">
                        <input type="text" id="autocomplete" placeholder="{{ trans('labels.search') }}" name="keyword" class="form-control">
                        <span class="input-group-btn">
                          <button class="btn btn-default" type="submit"><img src="{{ asset('img/btn-search-404.png') }}"></button>
                        </span>
                    </div>
                    {{ Form::close() }}
                </div>
                <div class="col-lg-3"></div>
            </div>
        </div>
    </div>
@stop
