@extends('layouts.admin')

@section('css')

{{ HTML::style(version('css_min/user_view.min.css')) }}

@stop

@section('main')

<div class="container-fluid">
    <div class="admin">
        @include('layouts.includes.sidebar_admin_setting_2')

        <div class="col-md-10 right-admin">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title">{{ trans('messages.user.manage_title') }}</div>
                </div>
                <div class="panel-body" >
                    @if (Session::has('message'))
                        <div class="alert {{ Session::get('type') }}">
                            <p>{{ Session::get('message') }}</p>
                        </div>
                    @endif
                    <div class="alert">
                        {{ Form::open(['action' => 'UsersController@getView', 'class' => 'form-inline', 'role' => 'search', 'method' => 'GET']) }}
                            <div class="input-group col-sm-8">
                                {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => trans('messages.user.search_hint')])}}
                                <div class="input-group-btn">
                                <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                                </div>
                            </div>
                            <div class="input-group col-sm-2">
                            {{ HTML::linkAction('UsersController@getView', trans('messages.user.show_all'), null, ['class' => 'btn btn-info']) }}
                            </div>
                        {{ Form::close() }}
                    </div>
                    @if(count($users) >0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ trans('labels.name') }}</th>
                                    <th>{{ trans('labels.username') }}</th>
                                    <th>{{ trans('messages.user.email') }}</th>
                                    <th>{{ trans('messages.user.work_email') }}</th>
                                    <th>{{ trans('labels.language_manager.default_post_language') }}</th>
                                    <th>{{ trans('labels.active') }}</th>
                                    <th>{{ trans('labels.action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{{ $user->name }}}</td>
                                        <td><a href="{{ url_to_user($user) }}" class="btn btn-link">{{{ $user->username }}}</a></td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->work_email }}</td>
                                        <td>
                                            @if (isset($user->setting->default_post_language))
                                                {{ Form::select('default_post_language',
                                                    $languages,
                                                    $user->setting->default_post_language,
                                                    [
                                                        'class' => 'form-control default-user-post-language',
                                                        'data-id' => $user->id,
                                                        'data-text-confirm-change' => trans('messages.user.admin_change_lang_confirm'),
                                                        'data-text-change-success' => trans('messages.user.admin_change_lang_success'),
                                                        'data-text-change-fail' => trans('messages.user.admin_change_lang_fail'),
                                                        'data-original-language' => $user->setting->default_post_language,
                                                    ])
                                                }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($user->isActived())
                                                <span class="btn_done">{{ trans('messages.user.done') }}</span>
                                            @elseif (!$user->isDeleted())
                                                <a data-url="{{ action('UsersController@postActive', [$user->id, $user->activeToken]); }}"
                                                data-message="{{ trans('messages.user.confirm_active', ['username' => $user->username])}}"
                                                data-labels ="{{ trans('messages.user.title_confirm') }}"
                                                data-active ="{{ trans('buttons.confirm_active') }}"
                                                data-success = "{{ trans('messages.user.success') }}"
                                                data-false = "{{ trans('messages.user.fail') }}"
                                                data-ok = "{{ trans('buttons.ok') }}"
                                                class="btn btn-link action-active">{{ trans('labels.active') }}</a>
                                            @endif
                                        </td>
                                        <td>
                                            @if (!$user->isCurrent())
                                                @if(!$user->isDeleted())
                                                    <a data-id="{{ $user->id }}"
                                                    data-message="{{ trans('messages.user.confirm_delete', ['username' => $user->username])}}"
                                                    data-url="{{ action('UsersController@postDestroy', $user->id); }}"
                                                    data-labels ="{{ trans('messages.user.title_confirm') }}"
                                                    data-delete ="{{ trans('buttons.confirm_delete') }}"
                                                    data-success = "{{ trans('messages.user.success') }}"
                                                    data-false = "{{ trans('messages.user.fail') }}"
                                                    data-ok = "{{ trans('buttons.ok') }}"
                                                    class="btn btn-link action-delete"> {{ trans('buttons.delete') }}</a>
                                                    @if($user->isActived())
                                                        @if(is_null($user->ban))
                                                            <button class="btn btn-default" data-toggle="modal" data-target="#banModal{{$user->id}}">{{ trans('labels.modal.ban') }}</button>
                                                            @include('modals.ban_user', ['user' => $user])
                                                        @else
                                                            <a data-id="{{ $user->id }}"
                                                               data-message="{{ trans('messages.user.confirm_unban', ['username' => $user->username])}}"
                                                               data-url="{{ action('UsersController@postUnBan', $user->id); }}"
                                                               data-labels ="{{ trans('messages.user.title_confirm') }}"
                                                               data-unban ="{{ trans('buttons.confirm_unBan') }}"
                                                               data-success = "{{ trans('messages.user.success') }}"
                                                               data-ok = "{{ trans('buttons.ok') }}"
                                                               data-false = "{{ trans('messages.user.fail') }}"
                                                               class="btn action-unban btn-default"> {{ trans('buttons.unban') }}</a>
                                                        @endif
                                                    @endif
                                                @else
                                                    <span style="color:#FF0000">{{ trans('messages.user.deleted') }}</span>
                                                @endif
                                            @else
                                                <a href="{{ url_to_user($user) }}" class="btn btn-link">{{ trans('buttons.view') }}</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                        <div class="alert alert-warning" role="alert">
                            {{ trans('messages.user.no_user')}}
                        </div>
                    @endif
                    <div class="pull-left">
                        {{ $users->appends(Request::except('page'))->render() }}
                    </div>
                </div>
            </div>
        </div>

        <div class="clearfix"></div>
    </div>
</div>

@stop

@section('script')
    {{ HTML::script(version('js_min/user_view.min.js')) }}
@stop
