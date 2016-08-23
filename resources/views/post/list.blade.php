@extends('layouts.admin')

@section('css')

{{ HTML::style(version('css_min/post_list.min.css')) }}

@stop

@section('main')

<div class="container-fluid">
    <div class="admin">
    @include('layouts.includes.sidebar_admin_setting_2')

    <div class="role col-md-10 col-sm-8 right-admin">
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">{{ trans('messages.post.manage_statistic_title') }}</div>
            </div>
            <div class="alert">
                {{ Form::open(['action' => 'PostsController@getList', 'class' => 'form-inline', 'role' => 'search', 'method' => 'GET']) }}
                    <div class="form-inline">
                        <div class="form-group">
                            {{ Form::select('type',
                                $options,
                                isset($input['type']) ? $input['type'] : null,
                                ['class' => 'form-control'])
                            }}
                        </div>
                        <div class="input-group col-sm-8">
                            {{ Form::text('name',
                                isset($input['name']) ? $input['name'] : null,
                                [
                                    'class' => 'form-control',
                                    'placeholder' => trans('messages.post.search_hint')
                                ])
                            }}
                        </div>
                    </div>
                    <div class="form-inline form-filter">
                        <div class="form-group">
                            {{ Form::select('category[]',
                                $filters['categories'],
                                isset($input['category']) ? $input['category'] : null,
                                [
                                    'class' => 'category-filter',
                                    'multiple' => 'multiple',
                                ])
                            }}
                        </div>
                        <div class="form-group">
                            {{ Form::select('author[]',
                                $filters['authors'],
                                isset($input['author']) ? $input['author'] : null,
                                [
                                    'class' => 'author-filter',
                                    'multiple' => 'multiple',
                                ])
                            }}
                        </div>
                        <div class="form-group">
                            {{ Form::select('language[]',
                                $filters['language'],
                                isset($input['language']) ? $input['language'] : null,
                                [
                                    'class' => 'language-filter',
                                    'multiple' => 'multiple',
                                ])
                            }}
                        </div>
                        <div class="form-group">
                            {{ Form::select('status',
                                $filters['status'],
                                isset($input['status']) ? $input['status'] : null,
                                [
                                    'class' => 'form-control status-filter',
                                ])
                            }}
                        </div>
                        <div class="form-group">
                            {{ Form::submit(trans('buttons.submit'),
                                [
                                    'class' => 'btn btn-primary',
                                    'name' => 'filter',
                                    'value' => 1,
                                ])
                            }}
                        </div>
                        <div class="input-group col-sm-2">
                            {{ HTML::linkAction('PostsController@getList', trans('messages.post.show_all'), null, ['class' => 'btn btn-info']) }}
                        </div>
                    </div>
                {{ Form::close() }}
            </div>
            <div class="panel-heading">
                <div class="panel-title">{{ trans('messages.post.posts_list') }}</div>
            </div>
            <div class="panel-body" >
                @if (isset($name))
                <div class="alert alert-success">
                    {{ trans('messages.post.search_result') . '"' . $name . '"' . ":" }}
                </div>
                @endif
                @if (isset($input['filter']))
                    <div class="alert alert-success">
                        {{ trans('messages.post.filter_result') }}
                    </div>
                @endif
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="title">{{ trans('labels.title') }}</th>
                                <th>{{ trans('labels.category') }}</th>
                                <th>{{ trans('labels.author') }}</th>
                                <th>{{ trans('labels.language') }}</th>
                                <th>{{ trans('labels.status') }}</th>
                                <th>{{ trans('labels.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($posts as $post)
                                <tr>
                                    <td>{{ $post->id }}</td>
                                    <td class="break-word">{{{ str_limit($post->title, $limit = 200, $end = '...') }}}</a></td>
                                    <td>
                                        @include('categories._category_badges', ['categories' => $post->categories, 'takeCategoryNumber' => App\Services\PostService::CATE_POST])
                                    </td>
                                    <td>
                                        {{  link_to_user($post->user) }}
                                    </td>
                                    <td>
                                        @if ($post->language_code)
                                            {{ Form::select('post_language',
                                                Config::get('detect_language.code'),
                                                $post->language_code,
                                                [
                                                    'class' => 'form-control post-language',
                                                    'data-id' => $post->id,
                                                    'data-original-language' => $post->language_code,
                                                    'data-text-confirm' => trans('messages.post.confirm_change_post_lang'),
                                                    'data-text-success' => trans('messages.post.change_post_lang_success'),
                                                    'data-text-fail' => trans('messages.post.change_post_lang_fail'),
                                                ])
                                            }}
                                        @endif
                                    </td>
                                    <td>
                                        @if (!$post->isPublished())
                                            <label class="label label-danger">{{ trans('labels.draft') }}</label>
                                        @else
                                            <label class="label label-success">{{ trans('labels.published') }}</label>
                                        @endif
                                    </td>
                                    <td>
                                        <a data-id="{{ $post->id }}"
                                            data-message="{{ trans('messages.post.delete_confirm')}}"
                                            data-url="{{ action('PostsController@destroy', $post->encryptedId); }}"
                                            data-labels ="{{ trans('messages.post.title_confirm') }}"
                                            data-delete ="{{ trans('buttons.confirm_delete') }}"
                                            data-success = "{{ trans('messages.post.success') }}"
                                            data-false = "{{ trans('messages.post.fail') }}"
                                            data-ok = "{{ trans('buttons.ok') }}"
                                            class="btn btn-link action-delete"> {{ trans('buttons.delete') }}</a>
                                            <a href="{{ url_to_post($post) }}" target="_blank" class="btn btn-link"> {{ trans('buttons.view') }} </a>
                                        @if ($post->isPublished())
                                            <a data-id="{{ $post->id }}"
                                                data-message="{{ trans('messages.post.unpublished_confirm')}}"
                                                data-url="{{ action('PostsController@unpublished', $post->encryptedId); }}"
                                                data-labels ="{{ trans('messages.post.title_confirm') }}"
                                                data-unpublished ="{{ trans('buttons.post.unpublished') }}"
                                                data-success = "{{ trans('messages.post.success') }}"
                                                data-false = "{{ trans('messages.post.fail') }}"
                                                data-ok = "{{ trans('buttons.ok') }}"
                                                class="btn btn-link action-unpublished"> {{ trans('buttons.post.unpublished') }}</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if (count($posts) == 0)
                        <div class="alert alert-warning" role="alert">
                            {{ trans('messages.post.no_post')}}
                        </div>
                    @endif
                </div>
                <div class="pull-left">
                    {{ $posts->appends(Request::except('page'))->render() }}
                </div>
            </div>
        </div>
    </div>
    </div>
</div>

@stop

@section('script')
    <script type="text/javascript">
        var categoryFilter = "{{ trans('labels.category_filter') }}";
        var authorFilter = "{{ trans('labels.author_filter') }}";
        var languageFilter = "{{ trans('labels.language_filter') }}";
    </script>
    {{ HTML::script(version('js_min/post_list.min.js')) }}
@stop
