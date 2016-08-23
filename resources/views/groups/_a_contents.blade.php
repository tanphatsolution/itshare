@foreach ($groupContents as $groupContent)
    @if (!empty($groupContent->post_id) && empty($groupContent->group_series_id))
        @include('groups._a_post', ['post' => $groupContent->post, 'group' => $group, 'lang' => $lang])
    @endif

    @if (empty($groupContent->post_id) && !empty($groupContent->group_series_id))
        @include('groups._a_series', ['series' => $groupContent->series, 'group' => $group, 'lang' => $lang])
    @endif
@endforeach