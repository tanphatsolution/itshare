<div class="row embed">
    <div class="embed-responsive-group col-lg-8 col-md-8 col-sm-8 col-xs-8">
        <iframe  height="400" src="https://www.youtube.com/embed/{{ $id }}" frameborder="0" allowfullscreen></iframe>
        <a class="link" href="{{{ \App\Services\HelperService::formatURL($data['url']) }}}" target="_blank">
            {{ trans('socials.source') }}: {{{ str_limit(\App\Services\HelperService::formatURL($data['url']), $limit = 90, $end = '...') }}}
        </a>
    </div>

    @if (isset($data['id']))
        {{ Form::hidden('id[]', $data['id']) }}
    @endif

    {{ Form::hidden('url[]', $data['url']) }}
    {{ Form::hidden('type[]', $data['type']) }}
    {{ Form::hidden('group_post_id[]', null) }}
    {{ Form::hidden('group_series_title[]', $data['title']) }}
    {{ Form::hidden('group_series_description[]', $data['description']) }}
    {{ Form::hidden('group_series_thumbnail[]', $data['image']) }}
</div>
<div class="clearfix"></div>
