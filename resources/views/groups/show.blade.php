@extends('layouts.group')

@section('group-css')
    {{ HTML::style(version('css_min/groups_show.min.css')) }}
@stop

@section('group-main')
    <div class="box-title box-title-child">
        <ul class="list-title">
            <li>
                <a class="{{ $filter == \App\Services\GroupService::GROUP_FILTER_ALL ? 'selected' : ''}}" href="{{ route('getGroupFillter', [$group->shortname, \App\Services\GroupService::GROUP_FILTER_ALL]) }}">
                    {{ trans('labels.groups.all_content') }}
                    ({{ $totalContentCount }})
                </a>
            </li>
            <li>
                <a class="{{ $filter == \App\Services\GroupService::GROUP_FILTER_POST ? 'selected' : ''}}" href="{{ route('getGroupFillter', [$group->shortname, \App\Services\GroupService::GROUP_FILTER_POST]) }}">
                    {{ trans('labels.groups.post_list') }} ({{ $groupContentCount->total_posts }})
                </a>
            </li>
            @if ($groupContentCount->total_series > 0)
            <li>
                <a class="{{ $filter == \App\Services\GroupService::GROUP_FILTER_SERIES ? 'selected' : ''}}" href="{{ route('getGroupFillter', [$group->shortname, \App\Services\GroupService::GROUP_FILTER_SERIES]) }}">
                    {{ trans('labels.groups.series_list') }} ({{ $groupContentCount->total_series }})
                </a>
            </li>
            @endif
            @if ($groupContentCount->total_wiki > 0)
            <li>
                <a class="{{ $filter == \App\Services\GroupService::GROUP_FILTER_WIKI ? 'selected' : ''}}" href="{{ route('getGroupFillter', [$group->shortname, \App\Services\GroupService::GROUP_FILTER_WIKI]) }}">
                    {{ trans('labels.groups.wiki') }} ({{ $groupContentCount->total_wiki }})
                </a>
            </li>
            @endif
        </ul>

        <ul class="right-group">
            @if (!is_null($groupUser))
                @if ($groupUser->isMember())
                    <li class="addseries" data-href="{{ URL::action('GroupSeriesController@create', [$group->encrypted_id]) }}">{{ trans('labels.groups.create_series') }}</li>
                    <li class="new-group-post" data-group-id="{{ $group->id }}" data-privacy-type="{{ \App\Services\HelperService::getDefaultPrivacy($group->id) }}">{{ trans('labels.groups.new_group_post') }}</li>
                @endif
            @endif

            <li class="addgroup" data-href="{{ route('groups.create') }}">{{ trans('labels.groups.create_group') }}</li>
        </ul>

        <div class="clearfix"></div>
    </div>
    
    @if ((!is_null($groupSettings) && $groupSettings->privacy_flag == 0) || (!is_null($groupUser) && $groupUser->isMember()))
        @if (Session::has('success'))
            <div class="alert alert-success" role="alert">
                <p>{{ Session::get('success') }}</p>
            </div>
        @endif
        <div id="group-contents" class="group-content blog-post">
            @include('groups._a_contents', ['groupContents' => $groupContents, 'group' => $group])
        </div>
    @endif

    <div class="load-more {{ ($hideSeeMore) ? 'hidden' : '' }}">
        <a href="#">{{ trans('labels.load_more') }}</a>
    </div>
    {{ Form::open(['action' => 'PostsController@postCreate', 'method' => 'POST','role' => 'form', 'files' => true, 'id' => 'hidden-create-group-post', 'class' => 'hidden']) }}
    {{ Form::close() }}
@stop

@section('group-script')
    <script type="text/javascript">
        var groupId = "{{ $group->id }}";
        var filter = "{{ $filter }}";
        var pageCount = 1;
        var message = "{{ $leaveGroupMessage }}";
        var delete_group_message = "{{ $privacyGroup }}";
        var groupEncryptedId = "{{ $group->encryptedId }}";
    </script>
    {{ HTML::script(version('js_min/groups/groups_show.min.js'), ['defer' => 'defer']) }}
@stop
