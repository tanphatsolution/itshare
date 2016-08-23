@extends('layouts.admin')

@section('css')

{{ HTML::style(version('css_min/post_statistic.min.css')) }}

@stop

@section('main')
<div class="container-fluid">
    <div class="admin">
    @include('layouts.includes.sidebar_admin_setting_2')

    <div class="role col-md-10 col-sm-8 right-admin">
        <div class="panel panel-info">
            <div class="panel-heading">
                {{ Form::open(['action' => 'PostsController@getStatistic', 'class' => 'form-inline', 'role' => 'form', 'method' => 'GET', 'onChange' => 'submit()']) }}
                    <div class="form-group">
                        {{ trans('labels.filter_by') }}
                    </div>
                    <div class="form-group">
                        {{ Form::select('filter', $filterOptions, $filter, ['class' => 'form-control']) }}
                    </div>
                {{ Form::close() }}
            </div>
            <div class="panel-heading">
                <div class="panel-title">{{ trans('messages.post.manage_statistic_title') }}</div>
            </div>
            <div class="panel-body" >
                <div class="col-lg-5 content">
                    <div class="tendency-of-posts">
                        <h4>{{ trans('messages.post.manage_statistic_title') }}</h4>
                        <div id="piechart" data-label=""></div>
                    </div>
                </div>
                <div class="col-lg-7 content content-skill">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>{{ trans('messages.post.statistic') }}</th>
                                <th>{{ trans('messages.post.number_posts') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ trans('messages.post.published') }}</td>
                                <td>{{ $statisticAll['published'] }}</td>
                            </tr>
                            <tr>
                                <td>{{ trans('messages.post.draft') }}</td>
                                <td>{{ $statisticAll['drafts'] }}</td>
                            </tr>
                            <tr>
                                <td>{{ trans('messages.post.deleted_drafts') }}</td>
                                <td>{{ $statisticAll['deletedDrafts'] }}</td>
                            </tr>
                            <tr>
                                <td>{{ trans('messages.post.deleted_published') }}</td>
                                <td>{{ $statisticAll['deletedPublished'] }}</td>
                            </tr>
                            <tr>
                                <td>{{ trans('messages.post.total') }}</td>
                                <td>{{ $statisticAll['total'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel-heading">
                <div class="panel-title">{{ trans('messages.post.by_month') }}</div>
            </div>
            <div class="panel-body" >
                <div class="col-lg-5 content">
                    <div style="display:inline">
                    {{ Form::open(['action' => 'PostsController@getStatistic', 'class' => 'form-inline', 'role' => 'form', 'method' => 'GET']) }}
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
                            {{ $optionsTime['year'] }}{{ trans('datetime.year_ja') }}{{ trans('datetime.month.' . $optionsTime['month']) }}{{ trans('messages.post.view_day') }}
                        @else
                            {{ trans('messages.post.view_day') }} {{ $optionsTime['month'] }}/{{ $optionsTime['year'] }}
                        @endif
                    </div>
                </div>
                <div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>
                                    {{ trans('messages.post.day') }}
                                </th>
                                @for($i = 1; $i <= 16; $i++)
                                    <th>
                                        {{ $i }}
                                    </th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ trans('messages.post.clips') }}</td>
                                @for($i = 1; $i <= 16; $i++)
                                    <td>
                                    @if (isset($statisticInMonth['clips'][$i]))
                                        {{ $statisticInMonth['clips'][$i] }}
                                    @endif
                                    </td>
                                @endfor
                            </tr>
                            <tr>
                                <td>{{ trans('messages.post.posts_clip') }}</td>
                                @for($i = 1; $i <= 16; $i++)
                                    <td>
                                    @if (isset($statisticInMonth['postsCliped'][$i]))
                                        {{ $statisticInMonth['postsCliped'][$i] }}
                                    @endif
                                    </td>
                                @endfor
                            </tr>
                            <tr>
                                <td>{{ trans('messages.post.deleted_published') }}</td>
                                @for($i = 1; $i <= 16; $i++)
                                    <td>
                                    @if (isset($statisticInMonth['deletedPublished'][$i]))
                                        {{ $statisticInMonth['deletedPublished'][$i] }}
                                    @endif
                                    </td>
                                @endfor
                            </tr>
                            <tr>
                                <td>{{ trans('messages.post.deleted_drafts') }}</td>
                                @for($i = 1; $i <= 16; $i++)
                                    <td>
                                    @if (isset($statisticInMonth['deletedDrafts'][$i]))
                                        {{ $statisticInMonth['deletedDrafts'][$i] }}
                                    @endif
                                    </td>
                                @endfor
                            </tr>
                            <tr>
                                <td>{{ trans('messages.post.draft') }}</td>
                                @for($i = 1; $i <= 16; $i++)
                                    <td>
                                    @if (isset($statisticInMonth['drafts'][$i]))
                                        {{ $statisticInMonth['drafts'][$i] }}
                                    @endif
                                    </td>
                                @endfor
                            </tr>
                            <tr>
                                <td>{{ trans('messages.post.published') }}</td>
                                @for($i = 1; $i <= 16; $i++)
                                    <td>
                                    @if (isset($statisticInMonth['published'][$i]))
                                        {{ $statisticInMonth['published'][$i] }}
                                    @endif
                                    </td>
                                @endfor
                            </tr>
                            <tr>
                                <td>{{ trans('messages.post.total_posts') }}</td>
                                @for($i = 1; $i <= 16; $i++)
                                    <td>
                                    @if (isset($statisticInMonth['total'][$i]))
                                        {{ $statisticInMonth['total'][$i] }}
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
                                    {{ trans('messages.post.day') }}
                                </th>
                                @for($i = 17; $i <= $optionsTime['daysInMonth']; $i++)
                                    <th>
                                        {{ $i }}
                                    </th>
                                @endfor
                                <th>{{ trans('messages.post.total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ trans('messages.post.clips') }}</td>
                                @for($i = 17; $i <= $optionsTime['daysInMonth']; $i++)
                                    <td>
                                    @if (isset($statisticInMonth['clips'][$i]))
                                        {{ $statisticInMonth['clips'][$i] }}
                                    @endif
                                    </td>
                                @endfor
                                <td><b>{{ array_sum($statisticInMonth['clips']->toArray()) }}</b></td>
                            </tr>
                            <tr>
                                <td>{{ trans('messages.post.posts_clip') }}</td>
                                @for($i = 17; $i <= $optionsTime['daysInMonth']; $i++)
                                    <td>
                                    @if (isset($statisticInMonth['postsCliped'][$i]))
                                        {{ $statisticInMonth['postsCliped'][$i] }}
                                    @endif
                                    </td>
                                @endfor
                                <td><b>{{ array_sum($statisticInMonth['postsCliped']->toArray()) }}</b></td>
                            </tr>
                            <tr>
                                <td>{{ trans('messages.post.deleted_published') }}</td>
                                @for($i = 17; $i <= $optionsTime['daysInMonth']; $i++)
                                    <td>
                                    @if (isset($statisticInMonth['deletedPublished'][$i]))
                                        {{ $statisticInMonth['deletedPublished'][$i] }}
                                    @endif
                                    </td>
                                @endfor
                                <td><b>{{ array_sum($statisticInMonth['deletedPublished']->toArray()) }}</b></td>
                            </tr>
                            <tr>
                                <td>{{ trans('messages.post.deleted_drafts') }}</td>
                                @for($i = 17; $i <= $optionsTime['daysInMonth']; $i++)
                                    <td>
                                    @if (isset($statisticInMonth['deletedDrafts'][$i]))
                                        {{ $statisticInMonth['deletedDrafts'][$i] }}
                                    @endif
                                    </td>
                                @endfor
                                <td><b>{{ array_sum($statisticInMonth['deletedDrafts']->toArray()) }}</b></td>
                            </tr>
                            <tr>
                                <td>{{ trans('messages.post.draft') }}</td>
                                @for($i = 17; $i <= $optionsTime['daysInMonth']; $i++)
                                    <td>
                                    @if (isset($statisticInMonth['drafts'][$i]))
                                        {{ $statisticInMonth['drafts'][$i] }}
                                    @endif
                                    </td>
                                @endfor
                                <td><b>{{ array_sum($statisticInMonth['drafts']->toArray()) }}</b></td>
                            </tr>
                            <tr>
                                <td>{{ trans('messages.post.published') }}</td>
                                @for($i = 17; $i <= $optionsTime['daysInMonth']; $i++)
                                    <td>
                                    @if (isset($statisticInMonth['published'][$i]))
                                        {{ $statisticInMonth['published'][$i] }}
                                    @endif
                                    </td>
                                @endfor
                                <td><b>{{ array_sum($statisticInMonth['published']->toArray()) }}</b></td>
                            </tr>
                            <tr>
                                <td>{{ trans('messages.post.total_posts') }}</td>
                                @for($i = 17; $i <= $optionsTime['daysInMonth']; $i++)
                                    <td>
                                    @if (isset($statisticInMonth['total'][$i]))
                                        {{ $statisticInMonth['total'][$i] }}
                                    @endif
                                    </td>
                                @endfor
                                <td><b>{{ array_sum($statisticInMonth['total']->toArray()) }}</b></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="panel-heading">
                <div class="panel-title">{{ trans('messages.post.by_week') }}</div>
            </div>
            <div class="panel-body" >
                <div class="col-lg-5 content">
                    <div style="display:inline">
                    {{ Form::open(['action' => 'PostsController@getStatistic', 'class' => 'form-inline', 'role' => 'form', 'method' => 'GET']) }}
                    {{ Form::hidden('filter', $filter) }}
                    <div class="input-group col-sm-12">
                        <div class="form-group">
                            {{ Form::select('weeks', $weekOption, $optionsTime['weeks'], ['class' => 'form-control']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::selectYear('year', 2014, date('Y'), $optionsTime['year'], ['class' => 'form-control']) }}
                        </div>
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
                            {{ $optionsTime['year'] }}{{ trans('datetime.year_ja') }}{{ trans('messages.post.view_week') }}
                        @else
                            {{ trans('messages.post.view_week') }} {{ $optionsTime['year'] }}
                        @endif
                    </div>
                </div>
                @if (count(array_filter($statisticByWeek['weeksStartDay'])) == 0)
                    <div class="alert alert-warning">
                        <div class="panel-title">{{ trans('messages.post.no_data') }}</div>
                    </div>
                @else
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>{{ trans('messages.post.week') }}</th>
                            <th>{{ trans('messages.post.start_day') }}</th>
                            <th>{{ trans('messages.post.end_day') }}</th>
                            <th>{{ trans('messages.post.clips') }}</th>
                            <th>{{ trans('messages.post.posts_clip') }}</th>
                            <th>{{ trans('messages.post.deleted_published') }}</th>
                            <th>{{ trans('messages.post.deleted_drafts') }}</th>
                            <th>{{ trans('messages.post.drafts') }}</th>
                            <th>{{ trans('messages.post.published') }}</th>
                            <th>{{ trans('messages.post.total_posts') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @for ($i = 0; $i <= 25; $i++)
                        @if (!empty($statisticByWeek['weeksStartDay'][$i + 1]) || !empty($statisticByWeek['weeksStartDay'][$i + 27]))
                        <tr>
                            <td>{{ $week = ($optionsTime['weeks'] == 1) ? $i + 1 : $i + 27 }}</td>
                            <td>
                                {{ date("d/m/Y", strtotime($statisticByWeek['weeksStartDay'][$week])) }}
                            </td>
                            <td>
                                {{ date("d/m/Y", strtotime("+6 day", strtotime($statisticByWeek['weeksStartDay'][$week]))) }}
                            </td>
                            <td>
                                @foreach ($statisticByWeek['clips'] as $clip)
                                    {{ $clip->week == $week ? $clip->total : ''}}
                                @endforeach
                            </td>
                            <td>
                                @foreach ($statisticByWeek['postsCliped'] as $postsCliped)
                                    {{ $postsCliped->week == $week ? $postsCliped->total : ''}}
                                @endforeach
                            </td>
                            <td>
                                @foreach ($statisticByWeek['deletedPublished'] as $deletedPost)
                                    {{ $deletedPost->week == $week ? $deletedPost->total : ''}}
                                @endforeach
                            </td>
                            <td>
                                @foreach ($statisticByWeek['deletedDrafts'] as $deletedPost)
                                    {{ $deletedPost->week == $week ? $deletedPost->total : ''}}
                                @endforeach
                            </td>
                            <td>
                                @foreach ($statisticByWeek['drafts'] as $draft)
                                    {{ $draft->week == $week ? $draft->total : ''}}
                                @endforeach
                            </td>
                            <td>
                                @foreach ($statisticByWeek['published'] as $publishedPost)
                                    {{ $publishedPost->week == $week ? $publishedPost->total : ''}}
                                @endforeach
                            </td>
                            <td>
                                @foreach ($statisticByWeek['total'] as $totalPost)
                                    {{ $totalPost->week == $week ? $totalPost->total : ''}}
                                @endforeach
                            </td>
                        </tr>
                        @endif
                    @endfor
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>
    </div>
</div>

@stop

@section('script')
    <script type="text/javascript">
        $(function() {
            var posts = {{ json_encode($statisticAll) }};
            delete posts['total'];
            drawPieChart('#piechart', posts);
        });
    </script>
    {{ HTML::script(version('js_min/post_statistic.min.js')) }}

@stop
