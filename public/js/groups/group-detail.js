$(function() {
    $('.load-more').click(function(e) {
        e.preventDefault();
        $.ajax({
            type: 'GET',
            url: baseURL + '/groups/contents',
            data: {
                'pageCount': pageCount,
                'groupId': groupId,
                'filter': filter
            },
            success: function(response) {
                displayData(response);
                pageCount ++;
            }
        });
    });

    $('body').on('click', '.btn-join-group', function(e) {
        var elementJoin = $(this);
        var joinFlag = elementJoin.hasClass('request-join-group') ? 1: 0;

        elementJoin.addClass('disabled');

        $.ajax({
            type: 'POST',
            url: baseURL + '/groups/join',
            data: {
                'groupId': groupId,
                'joinFlag': joinFlag
            },
            success: function(response) {
                if (!response.error) {
                    elementJoin.html(joinFlag ? undoRequest : joinGroupLabel);

                    if (joinFlag) {
                        elementJoin.removeClass('request-join-group');
                    } else {
                        elementJoin.addClass('request-join-group');
                    }

                    swal(successLabel, response.message, 'success');
                } else {
                    swal(errorLabel, response.message, 'error');
                }

                elementJoin.removeClass('disabled');
            }
        });
    });

    $('.btn-leave-group').click(function(e) {
        e.preventDefault();
        $('.text-muted').removeClass('text-justify');

        swal({
            title: youSureMsg,
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
                type: 'DELETE',
                url: baseURL + '/groups/' + groupEncryptedId + '/leave',
                success: function(response) {
                    if (!response.error) {
                        swal({
                            title: successLabel,
                            text: response.message,
                            type: 'success',
                            confirmButtonClass: 'btn-success',
                            confirmButtonText: okBtn
                        },
                        function() {
                            if (response.data.isPublic) {
                                window.location.reload();
                            } else {
                                window.location.replace(baseURL + '/groups');
                            }
                        });
                    } else {
                        swal({
                            title: errorLabel,
                            text: response.message,
                            type: 'error',
                            confirmButtonClass: 'btn-success',
                            confirmButtonText: okBtn
                        })
                    }
                },
                error: function(response) {
                    swal(errorLabel, response.message, 'error');
                }
            });
        });
    });

    $('.delete_group').click(function(e) {
        e.preventDefault();
        $('.text-muted').addClass('text-justify');

        swal({
            title: confirmDelete + $(this).data('group') + '?',
            text: delete_group_message,
            type: 'warning',
            showCancelButton: true,
            confirmButtonClass: 'btn-confirm',
            confirmButtonText: yesBtn,
            cancelButtonText: noBtn,
            closeOnConfirm: false
        },
        function() {
            $.ajax({
                type: 'DELETE',
                url: baseURL + '/groups/' + groupEncryptedId + '/delete',
                success: function(response) {
                    $('.text-muted').removeClass('text-justify');
                    if (!response.error) {
                        swal({
                            title: successLabel,
                            text: response.message,
                            type: 'success',
                            confirmButtonClass: 'btn-success',
                            confirmButtonText: okBtn
                        },
                        function() {
                            if (response.error) {
                                window.location.reload();
                            } else {
                                window.location.replace(baseURL + '/groups');
                            }
                        });
                    }
                },
                error: function(response) {
                    swal(errorLabel, response.message, 'error');
                }
            });
        });
    });

    $('.addseries').click(function(e) {
        window.location.href = $(this).attr('data-href');
    });

    $('.addgroup').click(function(e) {
        window.location.href = $(this).attr('data-href');
    })

    $('.new-group-post').click(function(e) {
        var url = $(this).attr('data-href');
        var form = $('#hidden-create-group-post');
        form.append('<input type="text" name="groupId" value="' + $(this).attr('data-group-id') + '" />');
        form.append('<input type="text" name="privacyType" value="' + $(this).attr('data-privacy-type') + '" />');
        form.submit();
    })
});

function displayData(data) {
    $('#group-contents').append(data['views']);
    if(!data['hideSeeMore']) {
        $('.load-more').removeClass('hidden');
    } else {
        $('.load-more').addClass('hidden');
    }
}
