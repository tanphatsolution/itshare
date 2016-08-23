{{ Form::open(['action' => ['FeedbacksController@postIndex'], 'method' => 'post']) }}
    {{ Form::hidden('feedback_id', $feedback->id) }}
    {{ Form::hidden('is_feedback', 'true') }}
    {{ Form::hidden('action', 'finish') }}
    {{ Form::hidden('option', $searchOption) }}
    <button class="follow-btn btn-follow-mini" type="submit" >{{ trans('feedbacks.index.finish') }}</button>
{{ Form::close() }}
