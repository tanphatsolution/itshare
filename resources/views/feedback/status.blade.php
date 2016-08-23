<div class="relationship-container">
    @if ($feedback->status == \App\Services\FeedbackService:: STATUS_FINISHED)
        @include('feedback.open', ['feedback' => $feedback, 'searchOption' == $searchOption])
    @else
        @include('feedback.finish', ['feedback' => $feedback, 'searchOption' == $searchOption])
    @endif
</div>
