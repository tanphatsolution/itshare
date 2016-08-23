@extends('layouts.default')

@section('css')
{{ HTML::style(version('css_min/post_draft.min.css')) }}
{{ HTML::style(version('css_min/codemirror.min.css')) }}
@stop

@section('main')
@if ($errors->has())
    <div class='alert alert-danger text-center'>
        @foreach ($errors->all() as $message)
            <p>{{ $message }}</p>
        @endforeach
    </div>
@endif
<div class="col-md-12" id="draft">
    <div class="col-sm-3" id="draftsSidebar">
        <div id="draftsSidebarHeader">
            <h3 class="text-primary text-capitalize">{{ trans('labels.drafts_list') }}
                <a class="btn btn-primary pull-right" href="{{ route('posts.create') }}"><i class="fa fa-pencil"></i></a>
            </h3>
            <h4 class="text-muted">({{ count($drafts) . '/' . $maxDrafts }})</h4>
        </div>
        @include('post._list_drafts_title', ['drafts' => $drafts, 'post' => $post])
    </div>
    <div class="col-sm-9" id="draftsPreview">
        @if (isset($post))
        <div class="draftsPreviewHeader break-word">
            <h1 class="draftsPreviewHeader_title truncate" title="{{{ $post->title }}}">{{{ $post->title }}}
                <div class="btn btn-danger pull-right btn-post-delete" data-id="{{ $post->encryptedId }}" data-message="{{ trans('messages.post.delete_confirm') }}">
                    <i class="fa fa-trash"> {{ trans('buttons.delete') }} </i>
                </div>
                <a class="btn btn-info pull-right btn-post-edit" href="{{ route('post.edit', [$post->encryptedId]) }}">
                    <i class="fa fa-pencil"> {{ trans('buttons.edit') }} </i>
                </a>
            </h1>
            <div class="draftsPreviewHeader_tags">
                @foreach ($post->categories as $category)
                    <span class="glyphicon glyphicon-tag"></span>
                    {{ link_to_category($category) }}
                @endforeach
            </div>
        </div>
        <div class="draftsPreviewContent post-content break-word">
            {{ $post->getParsedContent() }}
        </div>
        @endif
    </div>
</div>
@stop

@section('script')
    {{ HTML::script(version('js_min/codemirror.min.js'), ['defer'=>'defer']) }}
    {{ HTML::script(version('js_min/post_draft.min.js'), ['defer'=>'defer']) }}
@stop
