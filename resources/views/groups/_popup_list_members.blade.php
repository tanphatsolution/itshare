<div class="modal fade" id="modalGroupUsers">
    <div class="modal-dialog modal-inner">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">{{ trans('buttons.close') }}</span></button>
                <h4 class="modal-title">{{ trans('modals.groups.group_members') }}</h4>
            </div>

            <div class="modal-body">
                @foreach ($groupUsers as $groupUser)
                    <img src="{{ user_img_url($groupUser->user, 50) }}" width="50">
                @endforeach
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('buttons.close') }}</button>
            </div>
        </div>
    </div>
</div>