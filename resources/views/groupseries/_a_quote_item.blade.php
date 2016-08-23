<div class="l-quote col-lg-12">
    <span class="icon-quote"></span>
    <p class="quote break-word">{{{ HTML::entities($data['quote']) }}}</p>
    @if (!empty($data['url']))
        <a class="link" href="{{ HTML::entities(App\Services\HelperService::formatURL($data['url'])) }}" target="_blank">
            Source: {{{ str_limit(App\Services\HelperService::formatURL($data['url']), $limit = 110, $end = '...') }}}
        </a>
    @endif

    @if (isset($data['id']))
        {{ Form::hidden('id[]', $data['id']) }}
    @endif

    {{ Form::hidden('url[]', $data['url']) }}
    {{ Form::hidden('type[]', $data['type']) }}
    {{ Form::hidden('quote[]', $data['quote']) }}
    {{ Form::hidden('group_post_id[]', null) }}
    {{ Form::hidden('group_series_title[]', null) }}
    {{ Form::hidden('group_series_description[]', $data['quote']) }}
    {{ Form::hidden('group_series_thumbnail[]', null) }}
</div>
<div class="clearfix"></div>
