@extends('layouts.default')

@section('css')

{{ HTML::style('css_min/errors_user.min.css') }}

@stop

@section('main')

<div class="col-md-12 user">
    <div class="col-sm-8 col-sm-offset-2 col-md-3 col-md-offset-4 alert alert-warning" role="alert"><h4>{{ trans('messages.user.user_not_found') }}</h4></div>
</div>

@stop


@section('script')

@stop