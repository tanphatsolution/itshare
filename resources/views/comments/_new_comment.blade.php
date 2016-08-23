<div class="comment">
    <ul class="nav nav-tabs nav-stacked comment">
        {{ Form::textarea('comment-placeholder', '', ['class' => 'typeahead comment-placeholder', 'type' => 'text', 'placeholder' => trans('comments.placeHolder')]) }}
        <div class="tabbable comment-section hidden">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab-edit" data-toggle="tab" id="edit">{{ trans('stocks.tabs.edit') }}</a></li>
                <li><a href="#tab-view" data-toggle="tab" id="view">{{ trans('stocks.tabs.preview') }}</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab-edit" class="clicked">
                    {{ Form::textarea(
                        'content',
                        null,
                        [
                            'id' => 'comment-editor',
                            'class' => 'typeahead comment-edit markdown',
                            'type' => 'text',
                            'placeholder' => trans('comments.placeHolder'),
                            'data-provide' => 'markdown',
                        ]
                    ) }}
                </div>
                <div class="tab-pane comment-content" id="tab-view"></div>
                <div class="comment-btns">
                    {{ Form::button(trans('feedbacks.show.send'),['class' => 'btn-send pull-right', 'name' => 'send'])  }}
                    {{ Form::button(trans('feedbacks.show.cancel'),['class' => 'btn-comment-cancel pull-right', 'name' => 'send'])  }}
                    <div class="img">
                        <div class="btn btn-default" data="image-upload" id="comment-image-uploader" data-title="{{ trans('labels.image_uploader') }}">
                            <span>{{ trans('labels.image_uploader') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </ul>
</div>
@include('modals.markdown_help')
