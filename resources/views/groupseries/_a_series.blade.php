@foreach ($groupSeriesDetail as $seriesDetail)
<div class="list-top-post list-more-post break-word">
    <div class="post-title top-post-detail">
        <div class="row top-series">
            <div class="caption-name">
                <span class="name-series">{{ $seriesDetail['aGroupSeries']->name }}</span>
                <ul class="mini-post">
                    <li class="post-view">
                        <a href="{{ URL::action('GroupSeriesController@show',
                            [$seriesDetail['aGroupSeries']->group_id, $seriesDetail['aGroupSeries']->id]) }}">
                            {{ $seriesDetail['totalPostSeries'] }} {{ trans('labels.groups.posts') }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    @foreach ($seriesDetail['groupSeriesItems'] as $post)
    <div class="post-title top-post-detail break-word">
        <a class="title break-word" href="{{ URL::action('PostsController@show',
            ['username' => $post->user->username, 'encryptedId' => $post->encrypted_id,]) }}">
            {{{ $post->title }}}
        </a>
        <a class="name-author" href="{{ url_to_user($post->user) }}">
            {{ $post->user->name }}&nbsp;-&nbsp;
            <span>{{ convert_to_japanese_date($post->created_at, $lang) }}</span>
        </a>
    </div>
    @endforeach
    <button type="button" class="viewall"
        onclick="window.location.href='{{ URL::action('GroupSeriesController@show',
            [$seriesDetail['aGroupSeries']->group_id, $seriesDetail['aGroupSeries']->id]) }}'">
            {{ trans('labels.groups.view_all') }}
    </button>
</div>
@endforeach