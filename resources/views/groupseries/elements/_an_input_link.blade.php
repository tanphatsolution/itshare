<div class="sortable-item ui-sortable-handle">
    <div class="form-group link-item-element">
        <div class="write-text col-lg-11 col-md-11 col-sm-11 col-xs-10 no-padding-left">
            <input type="text" name="url[]" class="input-url no-margin-left"
                data-link-type="{{ $data['type'] }}"
                placeholder="{{ trans('labels.group_series.place_holder_url') }}"
                value="{{ isset($data['url']) ? $data['url'] : '' }}">
        </div>
        <div class="delete btn-remove-item"></div>
        <div class="clearfix"></div>
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
    </div>
    <div class="clearfix"></div>
</div>
