@extends('layouts.default')

@section('css')

{{ HTML::style(version('css_min/userskills_create.min.css')) }}

@stop

@section('main')

<div class="col-md-12 setting">
    @include('layouts.includes.sidebar_setting')
    <div class="profile col-md-9 col-sm-8">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">{{ trans('messages.skill.skill_settings') }}</div>
            </div>
            <div class="panel-body user-skill-panel" >
                {{ Form::open(['action' => 'UserSkillsController@store', 'class' => 'form', 'method' => 'POST']) }}
                    @if (Session::has('message'))
                        <div class="alert alert-success">
                            <p>{{ Session::get('message') }}</p>
                        </div>
                    @endif
                    @if(Session::has('err'))
                        <div class="alert alert-danger">
                            <p>{{ Session::get('err') }}</p>
                        </div>
                    @endif
                    @include('userskills._a_skill_category')
                        <div class="form-group pull-right">
                            {{ Form::submit(trans('buttons.update'),['class' => 'btn btn-success', 'name' => 'send'])  }}
                            <a class="btn btn-primary" href="{{ URL::previous() }}">{{ trans('buttons.cancel') }}</a>
                        </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@stop

@section('script')
    {{ HTML::script(version('js_min/userskills_create.min.js'), ['defer' => 'defer']) }}
@stop
