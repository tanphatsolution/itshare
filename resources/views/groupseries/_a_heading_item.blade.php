<div class="l-title col-lg-12 break-word">
    {{{ $data['text'] }}}
    
    @if (isset($edit) && $edit)
    <div class="delete btn-remove-item"></div>
    @endif

    @if (isset($data['id']))
        {{ Form::hidden('id[]', $data['id']) }}
    @endif
    
    {{ Form::hidden('url[]', null) }}
    {{ Form::hidden('type[]', $data['type']) }}
    {{ Form::hidden('text[]', $data['text']) }}
    {{ Form::hidden('group_post_id[]', null) }}
    {{ Form::hidden('group_series_title[]', null) }}
    {{ Form::hidden('group_series_description[]', $data['text']) }}
    {{ Form::hidden('group_series_thumbnail[]', null) }}
</div>
<div class="clearfix"></div>
