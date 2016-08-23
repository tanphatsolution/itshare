<div class="sortable-item ui-sortable-handle">
    <div class="form-group link-item-element">
        <div class="write-text col-lg-11 col-md-11 col-sm-11 col-xs-10 no-padding-left">
            {{ Form::textarea('group_series_description[]', isset($data['text']) ? $data['text'] : '', ['class' => 'text', 'placeholder' => trans('labels.group_series.write_text'), 'class' => 'no-margin-left']) }}
        </div>
        <div class="delete btn-remove-item"></div>
        @if (isset($data['id']))
            {{ Form::hidden('id[]', $data['id']) }}
        @else
            {{ Form::hidden('id[]', null) }}
        @endif
        <div class="clearfix"></div>
        <div class="item-preview">
        @if(isset($preview))
            {{ $preview }}
        @endif
        </div>

        {{ Form::hidden('url[]', null) }}
        {{ Form::hidden('type[]', \App\Data\Blog\GroupSeries::URL_TYPE_HEADING) }}
        {{ Form::hidden('group_post_id[]', null) }}
        {{ Form::hidden('group_series_title[]', null) }}
        {{ Form::hidden('group_series_thumbnail[]', null) }}
    </div>
    <div class="clearfix"></div>
</div>
