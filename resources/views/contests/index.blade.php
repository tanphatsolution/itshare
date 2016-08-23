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
                        <div class="panel-title">{{ trans('labels.contest.title_view') }}</div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-2 form-group">
                                {{ link_to_route(
                                    'contests.create',
                                    trans('labels.contest.create'),
                                    null,
                                    ['class' => 'btn btn-success form-control'])
                                }}
                            </div>
                        </div>
                        <div class="row">
                            {{ Form::open(['method' => 'GET', 'action' => ['ContestController@index']]) }}
                                <div class="col-sm-4 form-group">
                                    {{ Form::text('q', $q, ['class' => 'form-control', 'placeholder' => trans('labels.contest.name')])}}
                                </div>
                                <div class="col-md-offset-1 col-sm-2 form-group">
                                    {{ Form::submit(trans('buttons.search'), ['class' => 'btn btn-default'])}}
                                </div>
                            {{ Form::close() }}
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>{{ trans('labels.contest.name') }}</th>
                                        <th>{{ trans('labels.contest.contest_theme') }}</th>
                                        <th>{{ trans('labels.contest.contest_category') }}</th>
                                        <th>{{ trans('labels.contest.contest_email') }}</th>
                                        <th>{{ trans('labels.contest.start') }}</th>
                                        <th>{{ trans('labels.contest.end') }}</th>
                                        <th>{{ trans('labels.contest.contest_end_score') }}</th>
                                        <th>{{ trans('labels.action') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($contests as $contest)
                                            <tr>
                                                <td>{{ $contest->title }}</td>
                                                <td>{{ !is_null($contest->MonthlyThemeSubject) ? $contest->MonthlyThemeSubject->theme_name : '' }}</td>
                                                <td>
                                                    @if (!is_null($contest->categories))
                                                        @foreach($contest->categories as $category)
                                                            <div> {{ $category->name }} </div>
                                                        @endforeach
                                                    @endif
                                                </td>
                                                <td>
                                                    @forelse($contest->domains as $domain)
                                                        <p>{{ $domain->name }}</p>
                                                    @empty
                                                        <p>{{ trans('labels.contest.no_domain') }}</p>
                                                    @endforelse
                                                </td>
                                                <td>{{ $contest->term_start->format('m/d/Y') }}</td>
                                                <td>{{ $contest->term_end->format('m/d/Y') }}</td>
                                                <td>{{ $contest->term_score_end->format('m/d/Y') }}</td>
                                                <td>{{ link_to_action(trans('ContestController@show'), trans('labels.view'), [$contest->id], ['class' => 'btn btn-primary']) }}</td>
                                            </tr>
                                        @empty
                                            {{ trans('labels.contest.no_contest') }}
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="pull-left">
                            {{ $contests->render() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
