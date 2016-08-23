<div class="row group-name-series">
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 images">
        <img  src="{{ asset('img/blank.png') }}" style="background: url({{{ $data['image'] }}}) center">
    </div>
    <div class="l-description col-lg-10 col-md-10 col-sm-10 col-xs-10">
        <a href="{{{ App\Services\HelperService::formatURL($data['url']) }}}" class="name break-word" target="_blank">{{{ $data['title'] }}}</a>
        <p class="detail break-word">{{{ $data['description'] }}}</p>
        <a class="link break-word" href="{{ App\Services\HelperService::formatURL($data['url']) }}" target="_blank">
            Source: {{ str_limit(App\Services\HelperService::formatURL($data['url']), $limit = 90, $end = '...') }}
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
