<div class="page-search">
    <div class="container page-search-header">
        <div class="box-title box-title-child">
            <ul class="list-title">
                <li><a class="selected">{{ trans('messages.search.index_title') }}</a></li>
            </ul>

            {{ Form::open(['action' => 'SearchsController@postIndex', 'class' => 'filter box-search', 'role' => 'form']) }}
                <div>
                    {{ Form::label('keyword', trans('messages.search.keyword'), ['class' => '']) }}
                    {{ Form::text('keyword', $keyword, ['class' => 'ui-autocomplete-input keyword-input', 'placeholder' => trans('messages.search.keyword_placeholder')]) }}
                </div>
                <div>
                    {{ Form::label('type', trans('messages.search.type'), ['class' => '']) }}
                    {{ Form::select('type', \App\Services\SearchService::getAllTypes(), \App\Services\SearchService::getDefaultType(), ['class' => '']) }}
                </div>
                <div>
                    {{ Form::submit(trans('buttons.search'), ['class' => 'button-search']) }}
                </div>
                <div class="clear-both"></div>
            {{ Form::close() }}

            <div class="clearfix"></div>
        </div>
    </div>
</div>
