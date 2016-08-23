@extends('layouts.admin')

@section('css')

{{ HTML::style(version('css_min/feedback_index.min.css')) }}

@stop

@section('main')

<div class="container-fluid">
    <div class="admin">
    @include('layouts.includes.sidebar_admin_setting_2')

    <div class="col-md-10 col-sm-8 right-admin">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">{{ trans('feedbacks.index.feedback_list' )}}</div>
            </div>
            <div class="panel-body" >
                <div class="col-md-12 feedback search-option">
                    {{ Form::open(['action' => 'FeedbacksController@index', 'class' => 'form', 'role' => 'search', 'method' => 'GET']) }}
                        {{ Form::label('option', trans('feedbacks.index.display_options')) }}
                        @foreach (App\Services\FeedbackService::getFindFeedbackOptions() as $option => $value)
                            <label for="{{$value}}" class="radio-inline">
                            {{ Form::radio('option', $option, $option == $searchOption ? true : false, ['id' => $value])}}{{$value}}
                            </label>
                        @endforeach
                        {{ Form::submit(trans('buttons.search'), ['class' => 'btn btn-default'])  }}
                    {{ Form::close()  }}
                </div>
                @if ($data->toArray() != [])
                <div>
                    <table class="table table-borderd table-striped table-condensed">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>
                                    {{ trans('feedbacks.index.title') }}
                                </th>
                                <th>
                                    {{ trans('feedbacks.index.message') }}
                                </th>
                                <th>
                                    {{ trans('feedbacks.index.status') }}
                                </th>
                                <th>
                                    {{ trans('feedbacks.index.action') }}
                                </th>
                            </tr>
                        </thead>
                        <br />
                        <tbody>
                            @foreach($data as $feedback)
                            <tr>
                                <td class="col-md-1 col-sm-1">{{ $feedback->id }}</td>
                                <td class="col-md-3 col-sm-3"><a href="{{ URL::action('FeedbacksController@show', $feedback->id) }}">{{{ $feedback->title }}}</a></td>
                                <td class="col-md-6 col-sm-6">{{ $feedback->message }}</td>
                                <td class="col-md-1 col-sm-1">
                                    {{ \App\Services\FeedbackService::getFeedbackStatusText($feedback->status) }}
                                </td>
                                <td class="col-md-1 col-sm-1">
                                    @include('feedback.status', ['feedback' => $feedback, 'searchOption' == $searchOption])
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="pull-left">
                        {{ $data->appends(Request::except('page'))->render() }}
                    </div>
                </div>
                @else
                <div class="col-md-12 feedback">
                    <div class="alert alert-danger" role="alert">
                        <h4>
                            {{ trans('feedbacks.index.no_feedback')}}
                        </h4>
                    </div>
                </div>
                @endif
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
