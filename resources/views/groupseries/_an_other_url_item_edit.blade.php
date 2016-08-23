<div class="row">
    <div class="series-site form-group">
        <div class="col-lg-11 col-md-11 col-sm-11 col-xs-10 no-padding-left">
            <p class="http-name break-word">
                <a href="{{{ $data['url'] }}}" target="_blank">
                    {{{ str_limit($data['url'], $limit = 90, $end = '...') }}}
                </a>
            </p>
        </div>
    </div>
    <div class="group-name no-padding-left">
        <img src="{{ asset('img/blank.png') }}" style="background: url({{{ $data['image'] }}}) center">
        <span class="name break-word">
        @if (!is_null($data['title']))
            {{{ trim($data['title']) }}}
        @else
            {{{ $data['url']}}}
        @endif
        </span> 
    </div>
    
    {{ Form::hidden('type[]', $data['type']) }}
    {{ Form::hidden('group_post_id[]', null) }}
    {{ Form::hidden('group_series_title[]', $data['title']) }}
    {{ Form::hidden('group_series_description[]', $data['description']) }}
    {{ Form::hidden('group_series_thumbnail[]', $data['image']) }}
    <br>
</div>