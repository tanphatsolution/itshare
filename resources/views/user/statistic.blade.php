@extends('layouts.admin')

@section('css')

{{ HTML::style(version('css_min/user_statistic.min.css')) }}

@stop

@section('main')

<div class="container-fluid">
    <div class="admin">
    @include('layouts.includes.sidebar_admin_setting_2')

    <div class="role col-md-10 col-sm-8 right-admin">
        <div class="panel panel-info">
            <div class="panel-heading">
                {{ Form::open(['action' => 'UsersController@getStatistic', 'class' => 'form-inline', 'role' => 'form', 'method' => 'GET', 'onChange' => 'submit()']) }}
                    <div class="form-group">
                        {{ trans('labels.filter_by') }}
                    </div>
                    <div class="form-group">
                        {{ Form::select('filter', $filterOptions, $filter, ['class' => 'form-control']) }}
                    </div>
                {{ Form::close() }}
            </div>
            <div class="panel-heading">
                <div class="panel-title">{{ trans('messages.user.manage_statistic_title') }}</div>
            </div>
            <div class="panel-body" >
                <div class="col-lg-5 content">
                    <div class="tendency-of-posts">
                        <h4>{{ trans('messages.user.manage_statistic_title') }}</h4>
                        <div id="piechart" data-label=""></div>
                    </div>
                </div>
                <div class="col-lg-7 content content-skill">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>{{ trans('messages.user.statistic') }}</th>
                                <th>{{ trans('messages.user.number_users') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ trans('messages.user.actived') }}</td>
                                <td>{{ $statisticAll['actived'] }}</td>
                            </tr>
                            <tr>
                                <td>{{ trans('messages.user.deleted') }}</td>
                                <td>{{ $statisticAll['deleted'] }}</td>
                            </tr>
                            <tr>
                                <td>{{ trans('messages.user.banned') }}</td>
                                <td>{{ $statisticAll['blocked'] }}</td>
                            </tr>
                            <tr>
                                <td>{{ trans('messages.user.not_active') }}</td>
                                <td>{{ $statisticAll['notActive'] }}</td>
                            </tr>
                            <tr>
                                <td>{{ trans('messages.user.total') }}</td>
                                <td>{{ $statisticAll['total'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel-heading">
                <div class="panel-title">{{ trans('messages.user.by_day') }}</div>
            </div>
            <div class="panel-body" >
                <div class="col-lg-5 content">
                    <div>
                        {{ Form::open(['action' => 'UsersController@getStatistic', 'class' => 'form-inline', 'role' => 'form', 'method' => 'GET']) }}
                        {{ Form::hidden('filter', $filter) }}
                        <div class="input-group col-sm-12">
                            @if ($lang == 'ja')
                                <div class="form-group">
                                    {{ Form::selectYear('year', 2014, date('Y'), $optionsTime['year'], ['class' => 'form-control']) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::selectMonth('month', $optionsTime['month'], ['class' => 'form-control']) }}
                                </div>
                            @else
                                <div class="form-group">
                                    {{ Form::selectMonth('month', $optionsTime['month'], ['class' => 'form-control']) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::selectYear('year', 2014, date('Y'), $optionsTime['year'], ['class' => 'form-control']) }}
                                </div>
                            @endif
                            <div class="form-group">
                                <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
                <div class="panel-heading">
                    <div class="panel-title">
                        @if ($lang == 'ja')
                            {{ $optionsTime['year'] }}{{ trans('datetime.year_ja') }}{{ trans('datetime.month.' . $optionsTime['month']) }}{{ trans('messages.user.view_day') }}
                        @else
                            {{ trans('messages.user.view_day') }} {{ $optionsTime['month'] }}/{{ $optionsTime['year'] }}
                        @endif
                    </div>
                </div>
                <div>
                    @if ($statisticInMonth->isEmpty())
                        <div class="alert alert-warning">{{ trans('messages.user.no_data') }}</div>
                    @else
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>
                                    {{ trans('messages.user.day') }}
                                </th>
                                @for($i = 1; $i <= 16; $i++)
                                    <th>
                                        {{$i}}
                                    </th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ trans('messages.user.total') }}</td>
                                @for($i = 1; $i <= 16; $i++)
                                    <td>
                                    @if(isset($statisticInMonth[$i]))
                                        <b>{{$statisticInMonth[$i]}}</b>
                                    @endif
                                    </td>
                                @endfor
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>
                                    {{ trans('messages.user.day') }}
                                </th>
                                @for($i = 17; $i <= $optionsTime['daysInMonth']; $i++)
                                    <th>
                                        {{$i}}
                                    </th>
                                @endfor
                                <th>{{ trans('messages.user.total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ trans('messages.user.total') }}</td>
                                @for($i = 17; $i <= $optionsTime['daysInMonth']; $i++)
                                    <td>
                                    @if(isset($statisticInMonth[$i]))
                                        <b>{{$statisticInMonth[$i]}}</b>
                                    @endif
                                    </td>
                                @endfor
                                <td><b>{{ $statisticInMonth->count() }}</b></td>
                            </tr>
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
            <div class="panel-heading">
                <div class="panel-title">{{ trans('messages.user.by_week') }}</div>
            </div>
            <div class="panel-body" >
                <div class="col-lg-5 content">
                    <div>
                        {{ Form::open(['action' => 'UsersController@getStatistic', 'class' => 'form-inline', 'role' => 'form', 'method' => 'GET']) }}
                        {{ Form::hidden('filter', $filter) }}
                        <div class="input-group col-sm-12">
                            @if ($lang == 'ja')
                                <div class="form-group">
                                    {{ Form::selectYear('year', 2014, date('Y'), $optionsTime['year'], ['class' => 'form-control']) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::select('weeks', $weeksOptions, $optionsTime['weeks'], ['class' => 'form-control']) }}
                                </div>
                            @else
                                <div class="form-group">
                                    {{ Form::select('weeks', $weeksOptions, $optionsTime['weeks'], ['class' => 'form-control']) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::selectYear('year', 2014, date('Y'), $optionsTime['year'], ['class' => 'form-control']) }}
                                </div>
                            @endif
                            <div class="form-group">
                                <button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
                <div class="panel-heading">
                    <div class="panel-title">
                        @if ($lang == 'ja')
                            {{ $optionsTime['year'] }}{{ trans('datetime.year_ja') }}{{ trans('messages.user.view_week') }}
                        @else
                            {{ trans('messages.user.view_week') }}{{ $optionsTime['year'] }}
                        @endif
                    </div>
                </div>
                <div>
                    @if (!$statisticByWeek->isEmpty())
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>{{ trans('messages.user.week') }}</th>
                                <th>{{ trans('messages.user.start_week') }}</th>
                                <th>{{ trans('messages.user.end_week') }}</th>
                                <th>{{ trans('messages.user.total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($statisticByWeek as $totalUserByWeek)
                            <tr>
                                <td>{{ $totalUserByWeek->week }}</td>
                                <td>{{ date('d/m/Y', strtotime($totalUserByWeek->day_week_start)) }}</td>
                                <td>{{ date('d/m/Y', strtotime($totalUserByWeek->day_week_end)) }}</td>
                                <td>{{ $totalUserByWeek->total }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @else
                        <div class="alert alert-warning">{{ trans('messages.user.no_data') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>
</div>

@stop

@section('script')
    <script type="text/javascript">
        $(function() {
            var users = {{ json_encode($statisticAll) }};
            delete users['total'];
            drawPieChart('#piechart', users);
        });
    </script>
    {{ HTML::script(version('js_min/user_statistic.min.js')) }}

@stop
