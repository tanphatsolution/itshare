<div class="col-lg-12">
    <div class="thumbnail">
        <div class="box-top">
            <a href="{{ URL::action('GroupSeriesController@show', [$group->encrypted_id, $series->id]) }}">
                <img src="{{ asset('img/viblo-series.jpg') }}">
            </a>
            <div class="mini-post-list">
                <span class="post-view">{{{ $series->views_count }}}</span>
            </div>
        </div>

        <div class="caption">
            <div class="item-info">
                <a class="name-title" href="{{ URL::action('GroupSeriesController@show', [$group->encrypted_id, $series->id]) }}">{{{ $series->name }}}</a>
                <div class="author">
                    @if (!empty($series->user->id))
                        <a href="{{ url_to_user($series->user) }}">
                            <img src="{{ asset('img/blank.png') }}" style="background: url({{ user_img_url($series->user, 100) }}) center no-repeat;">
                            <span>{{{ $series->user->name }}}</span>
                        </a>
                    @endif
                    <div class="mini-date">
                        <span>{{ trans('labels.posted_on') }}</span>
                        <span class="date">
                            {{ convert_to_japanese_date(is_null($series->created_at) ?
                            $series->updated_at : $series->created_at, $lang) }}
                        </span>
                    </div>
                </div>

                <p class="detail">
                    @if (mb_strlen($series->description) >= 700)
                        {{{ mb_substr($series->description, 0, strpos($series->description, ' ', 700)) . '...' }}}
                    @else
                        {{{ $series->description }}}
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>