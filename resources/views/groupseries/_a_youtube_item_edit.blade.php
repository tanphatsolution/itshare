<div class="row series-embed form-group link-item-element">
    <div class="col-lg-11 col-md-11 col-sm-11 col-xs-10 no-padding-left">
        <p class="text-box-image">
            <a href="{{{ $data['url'] }}}" target="_blank">
                {{{ str_limit($data['url'], $limit = 90, $end = '...') }}}
            </a>
        </p>
    </div>
    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-8 no-padding-left">
        <div class="embed-responsive-group">
            <iframe height="321" src="https://www.youtube.com/embed/{{ $id }}" frameborder="0" allowfullscreen=""></iframe>
        </div>
    </div>

    {{ Form::hidden('type[]', $data['type']) }}
    {{ Form::hidden('group_post_id[]', null) }}
    {{ Form::hidden('group_series_title[]', $data['title']) }}
    {{ Form::hidden('group_series_description[]', $data['description']) }}
    {{ Form::hidden('group_series_thumbnail[]', $data['image']) }}
</div>