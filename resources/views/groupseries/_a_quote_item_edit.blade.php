<div class="row series-edit form-group link-item-element">
    <div class="col-lg-11 col-md-11 col-sm-11 col-xs-10 no-padding-left">
        <p class="quote break-word">“{{ HTML::entities($data['quote']) }}”</p>
    </div>
    <div class="delete btn-remove-item"></div>
    <div class="link-quote col-lg-11 col-md-11 col-sm-11 col-xs-10 no-padding-left no-margin-left">
        <p><a href="{{{ $data['url'] }}}" target="_blank">
            {{{ str_limit($data['url'], $limit = 110, $end = '...') }}}
        </a></p>
    </div>

    {{ Form::hidden('type[]', $data['type']) }}
    {{ Form::hidden('quote[]', $data['quote']) }}
    {{ Form::hidden('group_post_id[]', null) }}
    {{ Form::hidden('group_series_title[]', null) }}
    {{ Form::hidden('group_series_description[]', $data['quote']) }}
    {{ Form::hidden('group_series_thumbnail[]', null) }}
    <br>
</div>