@extends('layouts.admin')

@section('css')

{{ HTML::style(version('css_min/feedback_show.min.css')) }}

@stop

@section('main')

<div class="container-fluid">
    <div class="admin">
        @include('layouts.includes.sidebar_admin_setting_2')

        <div class="col-md-10 col-sm-8 right-admin">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title">{{ trans('feedbacks.show.feedback_content') }}</div>
                </div>
                <div class="panel-body" >
                    <div class="col-md-12 col-sm-12 feedback">
                        {{ Form::open(['action' => 'FeedbacksController@postReply', 'method' => 'POST', 'class' => 'form-horizontal', 'role' => 'form']) }}
                        {{ Form::hidden('id', $id) }}
                        <div class="form-group">
                            {{ Form::label('title', 'Title') }}
                            {{ Form::text('title', $data['title'], ['class' =>'form-control', 'readonly']) }}
                        </div>

                        <div class="form-group">
                            {{ Form::label('feedback', 'Message') }}
                            {{ Form::textarea('feedback', $data['message'], ['class' => 'form-control text-area', 'resize' => 'none', 'readonly']) }}
                        </div>

                        <div class="form-group">
                            <div class="col-md-5 col-sm-5 col-xs-5 pdl-0">
                            {{ Form::label('name', 'User') }}
                            {{ Form::text('name', $data['name'], ['class' =>'form-control', 'readonly']) }}
                            </div>
                            <div class="col-md-5 col-sm-5 col-xs-5">
                            {{ Form::label('email', 'Email') }}
                            {{ Form::text('email', $data['email'], ['class' =>'form-control', 'readonly']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            {{ Form::label('reply', 'Reply') }}
                            {{ Form::textarea('reply', null, ['class' => 'form-control resize-none']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::checkbox('isFinished', 1, false, ['id' => 'isFinished']) }}
                            {{ Form::label('isFinished', trans('feedbacks.show.is_finished')) }}
                        </div>

                        <div class="form-group pull-right">
                            {{ Form::submit(trans('feedbacks.show.send'),['class' => 'btn btn-success', 'name' => 'send'])  }}
                            {{ Form::submit(trans('feedbacks.show.preview'),['class' => 'btn btn-info', 'name' => 'preview'])  }}
                            <a class="btn btn-primary" href="{{ URL::previous() }}">{{ trans('buttons.cancel') }}</a>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop


@section('script')

    @if(Session::has('msg'))
    <script>alert('{{ Session::get("msg") }}');</script>
    @endif

@stop
