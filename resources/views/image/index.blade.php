@extends('layouts.default')

@section('css')

{{ HTML::style(version('css_min/image_index.min.css')) }}

@stop

@section('main')

<div class="col-md-12 setting">
    @include('layouts.includes.sidebar_setting')

    <div class="role col-md-9 col-sm-8">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">{{ trans('labels.sb_manage_images') }}</div>
            </div>
            <div class="panel-body">
                <div class="progress">
                    <div class="progress-bar" role="progressbar" data-transitiongoal="{{$percentStorager}}"></div>
                </div>
                @if (count($images) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>{{ trans('labels.images') }}</th>
                                <th>{{ trans('labels.created') }}</th>
                                <th>{{ trans('labels.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($images as $image)
                            <tr>
                                <td>
                                    <div class="image">
                                        <img src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" data-original = "{{ $url }}{{ $image->name }}" href="{{ $url }}{{ $image->name }}" class="lazy" alt="{{ $image->original_name }}" width="100" height="100">
                                        <br>
                                        {{{ $image->originalName }}}
                                    </div>
                                </td>
                                <td>{{ convert_to_japanese_date($image->created_at, $lang, trans('datetime.format.full_date')) }}</td>
                                <td>
                                    <a data-id="{{ $image->id }}"
                                    data-message="{{ trans('messages.image.confirm_delete', ['name' => $image->originalName])}}"
                                    data-url="{{ action('ImageController@postDestroy', $image->id) }}"
                                    data-labels="{{ trans('messages.image.title_confirm') }}"
                                    data-delete="{{ trans('buttons.confirm_delete') }}"
                                    data-success="{{ trans('messages.image.success') }}"
                                    data-false="{{ trans('messages.image.fail') }}"
                                    data-ok="{{ trans('buttons.ok') }}"
                                    class="btn btn-link action-delete">{{ trans('buttons.delete') }}</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                    <div class="alert alert-warning" role="alert">
                        {{ trans('messages.image.no_image')}}
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>

@stop


@section('script')
{{ HTML::script(version('js_min/image_index.min.js'), ['defer' => 'defer']) }}
@stop
