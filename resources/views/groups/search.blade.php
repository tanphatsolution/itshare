@extends('layouts.group')

@section('group-css')

{{ HTML::style(version('css_min/groups_search.min.css')) }}

@stop

@section('group-main')
    <div class="box-title box-title-child">
        @if($groupContents->count() == 0)
            <div class="alert alert-danger">{{ trans('messages.group.not_found') }}</div>
        @else
            <div class="alert alert-success">{{ trans('messages.group.search_result') }} {{ $keywords }}</div>
        @endif
        <div class="clearfix"></div>
    </div>

    <div id="group-contents" class="group-content row group-blog-post">
        @include('groups._a_contents', ['groupContents' => $groupContents, 'group' => $group])
    </div>

    <div class="load-more {{ ($hideSeeMore) ? 'hidden' : '' }}">
        <a href="javascript:void(0)" id="load-more">{{ trans('labels.load_more') }}</a>
    </div>
@stop

@section('group-script')
    <script type="text/javascript">
        var groupId = '{{ $group->id }}';
        var pageCount = 1;
        var groupEncryptedId = '{{ $group->encryptedId }}';
        var keywords = '{{ $keywords }}';
    </script>
    {{ HTML::script(version('js_min/groups_search.min.js')) }}
@stop
