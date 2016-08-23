@foreach ($drafts as $draft)
    <div class="draftsSidebarItem" data-item_uuid="{{ $draft->encryptedId }}">
        <a class="draftsSidebarItem_link {{ (isset($post) && $post->id === $draft->id) ? 'text-success' : 'text-muted' }}" data-pjax="true" href="{{ route('post.draft', [$draft->encrypted_id])  }}">
            <h4 class="draftsSidebarItem_title break-word truncate" title="{{{ $draft->title }}}">{{{ $draft->title }}}</h4>
            <h5 class="draftsSidebarItem_time">{{ $draft->printTime() }}</h5>
        </a>
    </div>
@endforeach