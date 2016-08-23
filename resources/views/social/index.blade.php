@extends('layouts.default')

@section('css')

{{ HTML::style(version('css_min/setting_language.min.css')) }}

@stop

@section('main')

<div class="col-md-12 setting">
    @include('layouts.includes.sidebar_setting')

    <div class="role col-md-9 col-sm-8">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">{{ trans('messages.setting.social_login_title') }}</div>
            </div>
            <div class="panel-body" >
                {{ Form::open(['action' => 'SocialsController@getIndex', 'class' => 'form-horizontal', 'role' => 'form']) }}
                @if ($errors->has())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $message)
                            <p>{{ $message }}</p>
                        @endforeach
                    </div>
                @endif
                @if (Session::has('message'))
                    <div class="alert alert-success">
                        @if (Session::has('message'))
                            <p>{{ Session::get('message') }}</p>
                        @endif
                    </div>
                @endif
                @foreach(\App\Services\SocialService::getFields() as $type => $social)
                <div class="form-group">
                    {{ Form::label($type, $social, ['class' => 'col-md-3 control-label']) }}
                    <div class="col-md-9">
                        @if (\App\Services\SocialService::authorizedSocial($currentUser, $type))
                        Connected <a class="btn btn-sm btn-primary" href="{{ URL::action('SocialsController@getRevoke', ['type' => $type]) }}" onclick="return confirm('{{ trans('socials.confirm_revoke') }}');">{{ trans('labels.revoke') }}</a>
                        @else
                            {{ trans('labels.disconnected') }} <a class="btn btn-sm btn-success" href="{{ URL::action('SocialsController@getAuthorize', ['type' => $type]) }}">{{ trans('labels.connect') }}</a>
                        @endif
                    </div>
                </div>
                @endforeach
            {{ Form::close() }}
            </div>
        </div>
    </div>

</div>

@stop


@section('script')
    {{ HTML::script(version('js_min/setting_language.min.js')) }}
@stop
