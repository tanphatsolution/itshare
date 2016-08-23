@foreach($comments as $comment)
    @include('comments._a_comment', ['comment' => $comment])
@endforeach