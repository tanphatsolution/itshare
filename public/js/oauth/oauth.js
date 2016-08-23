$(function() {

    $('.delete-client').on('click', function() {
        var clientId = $(this).data('client-id');
        var titleConfirm = $(this).data('text-title-confirm');
        var message = $(this).data('text-message');
        var deleteSuccess = $(this).data('text-delete-success');
        var deleteFail = $(this).data('text-delete-fail');

        swal({
            title: titleConfirm,
            text: message,
            type: 'warning',
            showCancelButton: true,
            confirmButtonClass: 'btn-confirm',
            confirmButtonText: yesBtn,
            cancelButtonText: noBtn,
            closeOnConfirm: false
        },
        function() {
            $.ajax({
                method: 'DELETE',
                url: baseURL + '/oauth/apps/delete',
                data: {
                    clientId: clientId,
                },
                success: function(response) {
                    if (response.error) {
                        swal(errorLabel, response.message, 'error');
                    } else {
                        swal({
                            title: successLabel,
                            text: deleteSuccess,
                            type: 'success',
                            confirmButtonClass: 'btn-success',
                            confirmButtonText: okBtn
                        },
                        function () {
                            window.location.reload();
                        });
                    }
                },
                error: function() {
                    swal(errorLabel, errorMsg, 'error');
                }
            });
        });
    });
});
