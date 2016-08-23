@extends('layouts.default')

@section('css')

{{ HTML::style(version('css_min/feedback_create.min.css')) }}
{{ HTML::style(CaptchaUrls::LayoutStylesheetUrl()) }}

@stop

@section('main')

<div class="feedback col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
    <div class="panel panel-info" >
        <div class="panel-heading">
            <div class="panel-title">{{ trans('feedbacks.create.contact_us')}}</div>
            <div class="panel-link">{{ trans('feedbacks.create.greeting')}}</div>
        </div>
        <div class="panel-body" >
            @if ($errors->has() || Session::has('message'))
                <div class="alert alert-danger">
                    @foreach($errors->all() as $message)
                        <p>{{ $message }}</p>
                    @endforeach
                    @if (Session::has('message'))
                        <p>{{ Session::get('message') }}</p>
                    @endif
                </div>
            @endif
            {{ Form::open(['action' => 'FeedbacksController@store','class' => 'form']) }}
            <div class="form-group required">
                {{ Form::label('title', trans('feedbacks.create.title')) }}
                {{ Form::text('title', null, ['class' =>'form-control']) }}
            </div>

            <div class="form-group">
                {{ Form::label('message', trans('feedbacks.create.message')) }}
                {{ Form::textarea('message', null, ['class' => 'form-control resize-none']) }}
            </div>

            <div class="form-group">
                {{ Form::label('email', trans('feedbacks.create.email')) }}
                {{ Form::text('email', isset($currentUser) ? $currentUser->email : '', ['class' =>'form-control']) }}
            </div>

            <div class="form-group">
                {{ Form::label('name', trans('feedbacks.create.name')) }}
                {{ Form::text('name', isset($currentUser) ? $currentUser->name : '', ['class' =>'form-control']) }}
            </div>

            {{ $captchaHtml }}
            {{ Form::label('CaptchaCode', 'Retype the characters from the picture') }}
            {{ Form::text('CaptchaCode', null, array('id' => 'CaptchaCode')) }}

            <div class="form-group pull-right">
                <button class="btn btn-success" type="submit">
                    <i class="fa fa-paper-plane"></i> {{ trans('feedbacks.create.submit') }}
                </button>
            </div>
            {{ Form::close()  }}
        </div>
    </div>
</div>

@stop


@section('script')
    {{ HTML::script(version('js_min/feedback_create.min.js')) }}
@stop
