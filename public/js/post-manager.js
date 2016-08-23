$(function() {
    var btnDelete = $('a.action-delete');
    var btnUnpublished = $('a.action-unpublished');

    btnDelete.on('click', function() {
        var url = $(this).data('url');
        var message = $(this).data('message');
        var title = $(this).data('labels');
        var confirm_delete = $(this).data('delete');
        var success = $(this).data('success');
        var fail = $(this).data('fail');
        var buttonOk = $(this).data('ok');

        swal({
            title: title,
            text: message,
            type: 'warning',
            showCancelButton: true,
            confirmButtonClass: 'btn-confirm',
            confirmButtonText: confirm_delete,
            cancelButtonText: noBtn,
            closeOnConfirm: false
        },
        function() {
            $.ajax({
                type: 'DELETE',
                url: url,
                success: function(response) {
                    swal({
                        title: success,
                        text: response.message,
                        type: 'success',
                        confirmButtonClass: 'btn-success',
                        confirmButtonText: buttonOk,
                        cancelButtonText: noBtn
                    },
                    function() {
                        document.location.reload(true);
                    });
                },
                error: function(response) {
                    var res = response.responseJSON;
                    swal(fail, res.message, 'error');
                }
            });
        });
    });

    btnUnpublished.on('click', function() {
        var url = $(this).data('url');
        var message = $(this).data('message');
        var title = $(this).data('labels');
        var confirm_unpublished = $(this).data('unpublished');
        var success = $(this).data('success');
        var fail = $(this).data('fail');
        var buttonOk = $(this).data('ok');
        swal({
            title: title,
            text: message,
            type: 'warning',
            showCancelButton: true,
            confirmButtonClass: 'btn-confirm',
            confirmButtonText: confirm_unpublished,
            cancelButtonText: noBtn,
            closeOnConfirm: false
        },
        function() {
            $.ajax ({
                type: 'PATCH',
                url: url,
                success: function(response) {
                    swal({
                        title: success,
                        text: response.message,
                        type: 'success',
                        confirmButtonClass: 'btn-success',
                        confirmButtonText: buttonOk
                    },
                    function() {
                        document.location.reload(true);
                    });
                },
                error: function(response) {
                    var res = response.responseJSON;
                    swal(fail, res.message, 'error');
                }
            });
        });
    });

    $('.admin').on('change', '.post-language', function() {
        var element = $(this);
        var postId = $(this).data('id');
        var language = $(this).val();
        var originalLanguage = $(this).attr('data-original-language');
        var textConfirmChange = $(this).data('text-confirm');
        var textChangeSuccess = $(this).data('text-success');
        var textChangeFail = $(this).data('text-fail');

        swal({
            title: textConfirmChange,
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: yesBtn,
            cancelButtonText: noBtn,
            closeOnConfirm: false
        }, function(confirmChange) {
            if (confirmChange) {
                $.ajax({
                    type : 'POST',
                    url : baseURL + '/posts/changeLanguage',
                    data : {
                        postId : postId,
                        language : language,
                    },
                    success: function(response) {
                        if (response.success) {
                            swal({
                                title: successLabel,
                                text: textChangeSuccess,
                                type: 'success',
                            });
                            element.attr('data-original-language', language);
                        } else {
                            swal({
                                title: errorLabel,
                                text: textChangeFail,
                                type: 'error',
                            });
                        }
                    },
                    error: function() {
                        swal({
                            title: errorLabel,
                            text: errorMsg,
                            type: 'error',
                        });
                    }
                });
            } else {
                element.val(originalLanguage);
            }
        });
    });

    $('.category-filter').select2({
        theme: 'bootstrap',
        placeholder: categoryFilter
    });
    $('.author-filter').select2({
        theme: 'bootstrap',
        placeholder: authorFilter
    });
    $('.language-filter').select2({
        theme: 'bootstrap',
        placeholder: languageFilter
    });
});
