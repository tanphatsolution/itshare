<div class="modal select-language-modal fade" id="modalGroupUsers">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span class="sr-only">{{ trans('buttons.close') }}</span></button>
                <h4 class="modal-title">{{ trans('modals.groups.group_members') }}</h4>
            </div>

            <div class="modal-body">
              @include('groups._popup_member_list', ['userMembers' => $userMembers])
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->