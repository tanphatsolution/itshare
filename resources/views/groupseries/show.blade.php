@extends('layouts.group')

@section('group-css')

{{ HTML::style(version('css_min/groupseries_create.min.css')) }}

@stop

@section('group-main')
    @if(isset($editable) && $editable)
        <a href="{{ URL::action('GroupSeriesController@edit', ['groupEncryptedId' => $group->encrypted_id, 'groupSeriesId' => $groupSeries->id ]) }}" class="group-name pull-right"> {{ trans('buttons.edit') }} </a>
    @endif
    <div class="form-horizontal col-md-12">
        @if (Session::has('success'))
            <div class="alert alert-success" role="alert">
                <p>{{ Session::get('success') }}</p>
            </div>
        @endif
    </div>
    @if (!$hideSeriesContent)
        <div class="series-detail">
            <form class="serive">
                <div class="series-title break-word">{{{ $groupSeries->name }}}</div>
            </form>
            <div class="description break-word">
                {{{ $groupSeries->description }}}
            </div>
            <div class="group-series-list">
                {{ App\Services\HelperService::getSeriesListItems($groupSeries->id, false) }}
            </div>
        </div>
    @else
        <div class="content-group">
            <p>{{ trans('messages.group.join_group_notice_title') }}</p>
            <div class="join-group">
                    {{ trans('messages.group.join_group_notice', [
                            'join' => '<a href="javascript:void(0)" id="join-group-btn"
                                data-url="' . url_to_group($group) . '"
                                data-group-id="' . $group->id . '" >' .
                                trans('messages.group.join_group') . '</a>',
                            'group' => HTML::entities($group->name)
                        ])
                    }}
            </div>
        </div>
    @endif
@stop

@section('group-script')
    <script type="text/javascript">
        var imgMaxSize = {{ Config::get('image')['max_image_size'] }};
        var series_id = {{ $groupSeries->id }}
    </script>
    {{ HTML::script(version('js_min/groupseries_show.min.js'), ['defer'=> 'defer']) }}
@stop
