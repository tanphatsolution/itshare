@foreach ($posts as $postSeries)
    @if (!is_null($postSeries->post))
        @include('post._a_post', ['post' => $postSeries->post])
    @elseif (!is_null($postSeries->series))
        @include('groups._a_series', ['series' => $postSeries->series, 'group' => $postSeries->series->group])
    @endif
@endforeach
