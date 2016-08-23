<?php $verticals = isset($vertical) && $vertical ? true: false;  ?>
<div class="{{ $verticals ? 'share-social-vertical' : 'share-social' }} {{ isset($element) ? $element : '' }}" style="{{ $verticals ? 'top: 74px;':'' }}">
    <div class="fix-share">
        <span>{{ trans('labels.share') }}</span>
        <a class="face-share" target="_blank"
            href="http://www.facebook.com/sharer.php?u={{ url_to_post($post) }}&title={{{ $post->title }}}">
        </a>
        <div class="face-share-count share-box-count">0</div>
        <a class="google-share" target="_blank"
            href="https://plus.google.com/share?url={{ url_to_post($post) }}">
        </a>
        <div class="google-share-count share-box-count">0</div>
        @if (!empty($currentUser))
            <span>{{ trans('labels.clip_post') }}</span>
            <a class="clip btn-clip btn-favorite" href="javascript:void(0)"></a>
        @endif
        <span class="toc">{{ trans('labels.toc') }}</span>
        <div class="dropdown menu-toc pull-left" style="display: block;">
            <a class="menu-toc-title dropdown-toggle toc" href="javascript:void(0)"
                id="dropdown-toc-menu" data-toggle="dropdown" aria-haspopup="true"
                aria-expanded="true">
            </a>
            <ul class="dropdown-menu dropdown-menu-toc nav tocTreeContent" id="menuTocTree" aria-labelledby="dropdown-toc-menu"></ul>
        </div>
    </div>
</div>