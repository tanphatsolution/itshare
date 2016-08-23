@extends('layouts.admin')

@section('css')

{{ HTML::style(version('css_min/server_deploy.min.css')) }}

@stop

@section('main')
<div class="container-fluid">
    <div class="admin">
        @include('layouts.includes.sidebar_admin_setting_2')
        <div class="col-md-10 col-sm-8 right-admin">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div class="panel-title">
                        {{ trans('messages.contest.show_title') }} - {{ $contest->title }}
                    </div>
                </div>
                <div class="panel-body">
                    <meta name="csrf-token" content="{{ csrf_token() }}">
                    <table class="table table-bordered">
                        <tr>
                            <th>#</th>
                            <th>{{ trans('messages.role.user_username') }}</th>
                            <th>{{ trans('messages.role.user_name') }}</th>
                            <th>{{ trans('messages.contest.score') }}</th>
                            <th>{{ trans('messages.contest.articles') }}</th>
                        </tr>
                        @if (!empty($users))
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>
                                        {{ $user->score }}
                                    </td>
                                    <td>{{ Form::submit(PostService::getPostByUserContest($contest, $user->id)->count(), [
                                        'class' => 'btn btn-primary btn-articles',
                                        'data-user_id' => $user->id,
                                        'data-contest_id' => $contest->id,
                                        'data-username' => $user->username,
                                        'data-target' => '#articles-modal',
                                        'data-toggle' => 'modal'
                                    ]) }}
                                    </td>

                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" align="center">{{ trans('messages.contest.no_ranks') }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <div id="articles-modal" class="modal fade" role="dialog">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">
                        <span class="modal-title-user"></span>'s {{ trans('messages.contest.articles') }}
                    </h4>
                </div>
                <div class="modal-body">
                    <!-- Content Here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>

@stop

@section('script')
    @include('elements.js.codemirror')
    {{ HTML::script(version('js_min/contest.min.js')) }}
@stop