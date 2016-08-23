<div class="row series-text form-group link-item-element">
    <div class="col-lg-11 col-md-11 col-sm-11 col-xs-10 no-padding-left">
        <p class="text-box break-word">{{{ $data['text'] }}}</p>
    </div>
    <div class="delete btn-remove-item"></div>

    {{ Form::hidden('url[]', null) }}
    {{ Form::hidden('type[]', $data['type']) }}
    {{ Form::hidden('text[]', $data['text']) }}
    {{ Form::hidden('group_post_id[]', null) }}
    {{ Form::hidden('group_series_title[]', null) }}
    {{ Form::hidden('group_series_description[]', $data['text']) }}
    {{ Form::hidden('group_series_thumbnail[]', null) }}
    <br>
</div>