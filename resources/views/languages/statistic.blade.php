@extends('layouts.admin')

@section('css')

{{ HTML::style(version('css_min/language_statistic.min.css')) }}

@stop

@section('main')

<div class="container-fluid">
    <div class="admin">
        @include('layouts.includes.sidebar_admin_setting_2')

        <div class="role col-md-10 col-sm-8 right-admin">
            <div class="panel panel-info">
                <div class="panel-heading">
                    {{ Form::open(['action' => 'UserPostLanguagesController@getStatistic', 'class' => 'form-inline', 'role' => 'form', 'method' => 'GET', 'onChange' => 'submit()']) }}
                        <div class="form-group">
                            {{ trans('labels.filter_by') }}
                        </div>
                        <div class="form-group">
                            {{ Form::select('filter', $filterLangOption, $filter, ['class' => 'form-control']) }}
                        </div>
                    {{ Form::close() }}
                </div>
                <div class="panel-heading">
                    <div class="panel-title">{{ trans('labels.language_manager.language_statistic') }}</div>
                </div>
                <div class="panel-body" >
                    <div class="col-lg-7 content">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ trans('labels.language') }}</th>
                                    <th>
                                        @if ($filter == App\Services\LanguageService::PUBLISHED_POST_LANG)
                                            {{ trans('labels.language_manager.total_posts') }}
                                        @else
                                            {{ trans('labels.language_manager.total_users') }}
                                        @endif
                                    </th>
                                    <th>{{ trans('labels.language_manager.percent_user_active') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($languages as $language)
                                <tr>
                                    <td>{{ $language['language'] }}</td>
                                    <td>{{ $language['langCount'] }}</td>
                                    <td>{{ $language['percent'] }}%</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@section('script')

@stop
