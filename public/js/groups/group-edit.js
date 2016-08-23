$(function() {
    var renderItem = function(ul, item) {
        return $("<li />")
            .data("item.autocomplete", item)
            .append("<a><img src=" + blankImg + " class='user-avartar' style='background: url(" + item.avatar + ")'/>" + item.value + "</a>")
            .appendTo(ul);
    };

    var autoSuggestion = $('#add-member').autocomplete({

        source: function(request, response) {
            $.ajax({
                type: 'GET',
                url: baseURL + '/groups/getUsersList',
                dataType: 'json',
                data: {
                    username: request.term,
                    groupId : groupId,
                },
                success: function(data) {
                    response($.map( data, function(item) {
                        return {
                            label: item.label,
                            id: item.id,
                            avatar: item.avatar
                        };
                    }));
                }
            });
        },

        minLength: 3,

        select:function(e, ui) {
            var user = ui;
            $.ajax({
                type: 'POST',
                url: baseURL + '/groups/addMember',
                data: {
                    userId : user.item.id,
                    groupId : groupId,
                },
                success: function(response) {
                    $('#add-member').val('');
                    if (response.error) {
                        $('.add-member-notice').text(addFailMsg);
                    } else {
                        $('.group-list-members').empty().append(response.html);
                        $('.add-member-notice').text(addSuccessMsg);
                    }
                },
                error: function() {
                    swal({
                        title: errorLabel,
                        text: errorMsg,
                        type: 'error'
                    });
                }
            });
        }
    });

    if (autoSuggestion.data("ui-autocomplete") != undefined) {
        autoSuggestion.data("ui-autocomplete")._renderItem = renderItem;
    }

    $('.group-list-members').on('click', '.user-delete', function() {

        var currentGroupMemberElement = $(this).parents('.group-member');
        var userId = $(this).data('user-id');

        swal({
            title: confirmRemove,
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: yesBtn,
            cancelButtonText: noBtn,
            confirmButtonClass: 'btn-confirm',
            closeOnConfirm: false
        }, function(confirmDelete) {

            if (confirmDelete) {
                $.ajax({
                    type : 'POST',
                    url : baseURL + '/groups/removeMember',
                    data : {
                        userId : userId,
                        groupId : groupId,
                    },
                    success: function(response) {
                        if (response.error) {
                            swal({
                                title: errorLabel,
                                text: cannotRemoveMsg,
                                type: 'error'
                            });
                        } else {
                            swal(deletedLabel, removeSusscessMsg, 'success');
                            currentGroupMemberElement.remove();
                        }
                    },
                    error: function() {
                        swal({
                            title: errorLabel,
                            text: errorMsg,
                            type: 'error'
                        });
                    }
                });
            }
        });
    });


    $.fn.editable.defaults.mode = 'inline';

    $('#group-name').editable({
        type: 'text',
        pk: groupId,
        url: baseURL + '/groups/editGroupByClick',
        ajaxOptions: {
            dataType: 'json'
        },
        success: function(response) {
            $('.editable-groupname label').empty();
            $('.alert-danger').remove();

            if (response.error) {
                $('.editable-groupname label').html(response.notice);
            }
            $('#hidden-groupname').val(response.content);
        },
        error: function() {
            $('.editable-error-block.help-block.editable-groupname').empty().html('Error');
        }
    });

    $('#group-shortname').editable({
        type: 'text',
        pk: groupId,
        url: baseURL + '/groups/editGroupByClick',
        ajaxOptions: {
            dataType: 'json'
        },
        success: function(response) {
            $('.editable-shortname label').empty();
            $('.alert-danger').remove();

            if (response.error) {
                $('.editable-shortname label').html(response.notice);
            }
            $('#hidden-group-shortname').val(response.content);
            
            response.isShortname ? $("#hidden-group-is-shortname").val(1) : $("#hidden-group-is-shortname").val(0);

            if (response.isShortname) {
                $(this).html(response.content);
            } else {
                $(this).html('');
            }
        },
        error: function() {
            $('.editable-error-block.help-block.editable-groupname').empty().html('Error');
        },
        display: function(value, response) {
            return false;
        }
    });

    $('#description').editable({
        type: 'textarea',
        pk: groupId,
        url: baseURL + '/groups/editGroupByClick',
        ajaxOptions: {
            dataType: 'json'
        },
        error: function() {
            $('.editable-error-block.help-block').empty().html('Error');
        }
    });

    $('#url').editable({
        type: 'text',
        pk: groupId,
        url: baseURL + '/groups/editGroupByClick',
        ajaxOptions: {
            dataType: 'json'
        },
        error: function() {
            $('.editable-error-block.help-block').empty().html('Error');
        }
    });

    $('.group-list-members').on('change', '.change_authority', function() {
        var userId = $(this).data('user-id');
        var role = $(this).val();

        swal({
            title: confirmChangeRole,
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: yesBtn,
            cancelButtonText: noBtn,
            closeOnConfirm: false
        }, function(confirmChange) {

            if (confirmChange) {
                $.ajax({
                    type : 'POST',
                    url : baseURL + '/groups/changeRole',
                    data : {
                        userId : userId,
                        groupId : groupId,
                        role : role,
                    },
                    success: function(response) {
                        if (response.error) {
                            swal({
                                title: errorLabel,
                                text: cannotChangeRoleMsg,
                            });
                        } else {
                            swal(successLabel, changeRoleSuccessMsg, 'success');
                        }
                    },
                    error: function() {
                        swal({
                            title: errorLabel,
                            text: errorMsg,
                        });
                    }
                });
            }
        });
    });

    $('.save').on('click', function(e) {
        e.preventDefault();
        var formData = $("#edit-group :input[value!='PATCH']").serialize();

        $.ajax({
            type: 'POST',
            url: baseURL + '/groups/checkInput',
            data: formData,
            success: function(response) {
                if (response.error) {
                    swal({
                        title: errorLabel,
                        text: response.notice,
                        type: 'error',
                    });
                } else {
                    var currentCoverStateObj = getCropItImageStateInfo(true);
                    var currentProfileStateObj = getCropItImageStateInfo(false);

                    setInputCropPosition(true, currentCoverStateObj);
                    setInputCropPosition(false, currentProfileStateObj);

                    $('form#edit-group').submit();
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
    });

    if ($('#url').text() == "Empty") {
        $('#url').text(emptyField);
    }

});
