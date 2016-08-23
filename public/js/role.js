$(function() {
    var btnChangeRole = $('.action-change-role');
    btnChangeRole.on('change', function() {
        var self = $(this);
        var messageConfirm = self.data('message');
        var yesLabel = self.data('yes');
        var noLabel = self.data('no');
        $('input[name=user_id]').val(self.data('user-id'));
        $('input[name=role_id]').val(self.val());
        swal({
            title: messageConfirm,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: yesLabel,
            cancelButtonText: noLabel,
            closeOnConfirm: false
        }, function(isConfirm) {
            if (isConfirm) {
                $('#change-role-form').submit();
            }
        });
    });
});