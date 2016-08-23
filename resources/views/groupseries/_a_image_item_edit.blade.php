<div class="row series-pic form-group link-item-element">
    <div class="col-lg-11 col-md-11 col-sm-11 col-xs-10 no-padding-left">
        <p class="text-box-image">
            <a href="{{ HTML::entities($url) }}" target="_blank">
                {{{ str_limit($url, $limit = 90, $end = '...') }}}
            </a>
        </p>
    </div>
    <div class="col-lg-6 col-md-6 col-xs-8 col-sm-6 no-padding-left">
        <img class="picture" src="{{ asset('img/blank.png') }}" style="background: url('{{ HTML::entities($url) }}') center">         
    </div>

    {{ Form::hidden('type[]', App\Data\Blog\GroupSeries::URL_TYPE_IMAGE) }}
    {{ Form::hidden('group_post_id[]', null) }}
    {{ Form::hidden('group_series_title[]', '') }}
    {{ Form::hidden('group_series_description[]', '') }}
    {{ Form::hidden('group_series_thumbnail[]', $url) }}
</div>
