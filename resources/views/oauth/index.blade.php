@extends('layouts.admin')

@section('css')

{{ HTML::style(version('css_min/oauth_index.min.css')) }}

@stop

@section('main')

<div class="container-fluid">
    <div class="admin">
        @include('layouts.includes.sidebar_admin_setting_2')

        <div class="role col-md-10 col-sm-8 right-admin">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title">{{ trans('labels.client.title_manage') }}</div>
                </div>
                <div class="panel-body" >
                    @if (!is_null($oauthClients))
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ trans('labels.client.id') }}</th>
                                        <th>{{ trans('labels.client.secret') }}</th>
                                        <th>{{ trans('labels.client.name') }}</th>
                                        <th>{{ trans('labels.client.redirect_uri') }}</th>
                                        <th>{{ trans('buttons.edit') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($oauthClients as $key => $oauthClient)
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td>{{ $oauthClient->id }}</td>
                                        <td>{{ $oauthClient->secret }}</td>
                                        <td>{{ $oauthClient->name }}</td>
                                        <td>{{ $oauthClient->OauthClientEndpoint()->redirect_uri }}</td>
                                        <td>
                                            <a href="javascript:void(0)" data-client-id="{{ $oauthClient->id }}"
                                                data-text-title-confirm="{{ trans('messages.client.title_confirm') }}"
                                                data-text-message="{{ trans('messages.client.message_warning') }}"
                                                data-text-detele-success="{{ trans('messages.client.delete_success') }}"
                                                data-text-detele-success="{{ trans('messages.client.delete_fail') }}"
                                                class="delete-client">
                                                {{ trans('labels.client.btn_delete') }}
                                            </a>
                                            <a href="{{ action('OAuthController@editApp', [$oauthClient->id]) }}">{{ trans('labels.client.btn_edit') }}</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="pull-left">
                            {{ $oauthClients->appends(Request::except('page'))->render() }}
                        </div>
                    @else
                        <div class="alert alert-warning" role="alert">
                            {{ trans('messages.client.no_client')}}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('script')
    {{ HTML::script(version('js/oauth/oauth.js')) }}
    {{ HTML::script('js/sweet-alert.min.js') }}
@stop
