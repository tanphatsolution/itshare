<div class="picture-group col-lg-8 col-md-8 col-sm-8 col-xs-8">
    <img src="{{ asset('img/blank.png') }}" style="background: url({{ $url }}) center">
    <a class="link" href="{{ HTML::entities(\App\Services\HelperService::formatURL($url)) }}" target="_blank">
        Source: {{{ str_limit($url, $limit = 90, $end = '...') }}}
    </a>

    @if (isset($id))
        {{ Form::hidden('id[]', $id) }}
    @endif
    
    {{ Form::hidden('url[]', $url) }}
    {{ Form::hidden('type[]', \App\Data\Blog\GroupSeries::URL_TYPE_IMAGE) }}
    {{ Form::hidden('group_post_id[]', null) }}
    {{ Form::hidden('group_series_title[]', '') }}
    {{ Form::hidden('group_series_description[]', '') }}
    {{ Form::hidden('group_series_thumbnail[]', $url) }}
</div>
<div class="clearfix"></div>
