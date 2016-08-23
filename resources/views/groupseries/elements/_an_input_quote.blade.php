<div class="sortable-item ui-sortable-handle">
    <div class="form-group link-item-element">
        <div class="write-text col-lg-11 col-md-11 col-sm-11 col-xs-10 no-padding-left">
            {{ Form::textarea('group_series_description[]', isset($data['quote']) ? $data['quote'] : '', ['class' => 'quote', 'placeholder' => trans('labels.group_series.write_text'), 'class' => 'no-margin-left']) }}
            <input type="text" name="url[]" class="input-url-quote no-margin-left" 
                placeholder="{{ trans('labels.group_series.place_holder_url') }}"
                class="no-margin-left"
                value="{{ isset($data['url']) ? $data['url'] : '' }}">
        </div>
        <div class="delete btn-remove-item"></div>
        @if (isset($data['id']))
            {{ Form::hidden('id[]', $data['id']) }}
        @else
            {{ Form::hidden('id[]', null) }}
        @endif
        <div class="item-preview">
        @if(isset($preview))
            {{ $preview }}
        @endif
        </div>

        {{ Form::hidden('type[]', \App\Data\Blog\GroupSeries::URL_TYPE_QUOTE) }}
        {{ Form::hidden('group_post_id[]', null) }}
        {{ Form::hidden('group_series_title[]', null) }}
        {{ Form::hidden('group_series_thumbnail[]', null) }}
        <div class="clearfix"></div>
    </div>
    <div class="clearfix"></div>
</div>
