$(function(){
    $('.post-content').processText();
    processImageInPost();

    var deleteButton = $('.btn-post-delete');
    deleteButton.click(function() {
        var id = deleteButton.data('id');
        var message = deleteButton.data('message');
        swal({
            title: youSureMsg,
            text: message,
            type: 'warning',
            showCancelButton: true,
            confirmButtonClass: 'btn-confirm',
            confirmButtonText: yesBtn,
            closeOnConfirm: false
        },
        function(){
            $.ajax ({
                type: 'DELETE',
                url: baseURL + '/posts/' + id,
                success: function(response){
                    swal({
                        title: successLabel,
                        text: response.message,
                        type: 'success',
                        confirmButtonClass: 'btn-success',
                        confirmButtonText: okBtn
                    },
                    function () {
                        window.location.replace(baseURL + '/drafts');
                    });
                },
                error: function(response) {
                    var res = response.responseJSON;
                    swal(errorLabel, res.message, 'error');
                }
            });
        });
    });
});