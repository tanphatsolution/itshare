@extends('layouts.group')

@section('group-css')

{{ HTML::style(version('css_min/groupseries_create.min.css')) }}

@stop

@section('group-main')
<div class="series-new">
@if ($errors->has())
    <div class='alert alert-danger'>
        @foreach($errors->all() as $message)
            <p>{{ $message }}</p>
        @endforeach
    </div>
    @endif
    {{ Form::open(['action' => ['GroupSeriesController@store', 'encryptedGroupId' => $encryptedGroupId],
        'method' => 'POST','role' => 'form', 'files' => true,
        'id' => 'create-series', 'class' => 'serive']) }}
        <div class="col-lg-12 series-text">
            {{ Form::text('name', null, ['id' => 'name', 'class' => 'form-control write',
                'placeholder' => trans('labels.group_series.series_name')]) }}
            <label for="series_language" >{{ trans('messages.series.language_post') }} </label>&nbsp;&nbsp;
            {{ Form::select('language_code', Config::get('detect_language.code'),
                $defaultPostLang, ['id' => 'language_code', 'data-type' => 'default']) }}<br/><br/>
            {{ Form::textarea('description', null, ['id' => 'description', 'class' => 'form-control',
                'placeholder' => trans('labels.group_series.series_description')]) }}
        </div>
        <div class="clear-both"></div>
        <div class="group-series-list form-group sortable">
        </div>
        <div class="row series-select form-group element-input" id="element-input">
            <div class="col-lg-3 col-md-3 col-xs-3 col-sm-3 select no-padding-left">
                {{ Form::select('link_type', \App\Services\GroupSeriesService::getLinkTypeOption(), null, ['class' => 'form-control', 'id' => 'link-type']) }}
            </div>
            <div class="add btn-add"></div><span class="select-option">{{ trans('categories.select_desc') }}</span>
        </div>
        <span id="image-uploader"></span>
        <div class="submit">
            <input class="save" data-form-type="create" type="button" value="{{ trans('buttons.create') }}" />
        </div>
    {{ Form::close() }}
    </div>

@stop

@section('group-script')

    <script type="text/javascript">

        var typePost = {{ \App\Data\Blog\GroupSeries::URL_TYPE_POST }};
        var typeYoutube =  {{ \App\Data\Blog\GroupSeries::URL_TYPE_YOUTUBE }};
        var typeImage = {{ \App\Data\Blog\GroupSeries::URL_TYPE_IMAGE }};
        var typeQuote = {{ \App\Data\Blog\GroupSeries::URL_TYPE_QUOTE }};
        var typeHeading = {{ \App\Data\Blog\GroupSeries::URL_TYPE_HEADING }};
        var typeText = {{ \App\Data\Blog\GroupSeries::URL_TYPE_TEXT }};
        var typeLink = {{ \App\Data\Blog\GroupSeries::URL_TYPE_LINK }};
        var typeOther = {{ \App\Data\Blog\GroupSeries::URL_TYPE_OTHER }};
        var encryptedGroupId = "{{ $encryptedGroupId }}";
        var typeNameArr = [];
        var message = "{{ $leaveGroupMessage }}";
        var groupEncryptedId = "{{ $group->encryptedId }}";
        typeNameArr[typeYoutube] = "{{ trans('labels.group_series.youtube') }}";
        typeNameArr[typeImage] = "{{ trans('labels.group_series.image') }}";

    </script>

    {{ HTML::script(version('js_min/groupseries_create.min.js')) }}
@stop
