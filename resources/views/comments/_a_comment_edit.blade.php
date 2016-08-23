<div class="comment-content comment-update-container">
    {{ Form::textarea(
        'message',
        htmlentities($comment->content),
        [
            'class' => 'comment-update markdown',
            'type' => 'text',
            'placeholder' => 'Comment ..',
            'data-provide' => 'markdown',
            'id' => 'comment-update-editor-' . $comment->id
        ]
    ) }}
    <div class="button-group write-comment">
        @if (\App\Services\UserService::canEditComment($currentUser, $comment))
            <button class="btn-save">{{ trans('buttons.save') }}</button>
        @endif
        @if(\App\Services\UserService::canDeleteComment($currentUser, $comment))
            <button class="btn-cancel">{{ trans('buttons.cancel') }}</button>
        @endif
    </div>
    <div class="btn btn-default" data="image-upload" id="image-uploader" data-title="{{ trans('labels.image_uploader') }}">
        <span class="fa fa-cloud-upload">{{ trans('labels.image_uploader') }}</span>
    </div>
</div>