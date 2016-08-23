$(function() {
    var btnDelete = $('a.action-delete');
    var btnRestore = $('a.action-restore');
    var btnActive = $('a.action-active');
    var btnUnban = $('a.action-unban');

    btnUnban.on('click', function() {
        var url = $(this).data('url');
        var message = $(this).data('message');
        var title = $(this).data('labels');
        var confirm_unBan = $(this).data('unban');
        var success = $(this).data('success');
        var fail = $(this).data('fail');
        var buttonOk = $(this).data('ok');
        swal({
            title: title,
            text: message,
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: 'btn-confirm',
            confirmButtonText: confirm_unBan,
            cancelButtonText: noBtn,
            closeOnConfirm: false
        },
        function() {
            $.ajax({
                type: 'POST',
                url: url,
                success: function(response) {
                    swal({
                        title: success,
                        text: response.message,
                        type: "success",
                        confirmButtonClass: "btn-success",
                        confirmButtonText: buttonOk
                    },
                    function() {
                        window.location.replace(baseURL + '/users/view');
                    });
                },
                error: function(response) {
                    var res = response.responseJSON;
                    swal(fail, res.message, "error");
                }
            });
        });
    });
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
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: 'btn-confirm',
            confirmButtonText: confirm_delete,
            cancelButtonText: noBtn,
            closeOnConfirm: false
        },
        function() {
            $.ajax({
                type: 'POST',
                url: url,
                success: function(response) {
                    swal({
                        title: success,
                        text: response.message,
                        type: "success",
                        confirmButtonClass: "btn-success",
                        confirmButtonText: buttonOk
                    },
                    function() {
                        window.location.replace(baseURL + '/users/view');
                    });
                },
                error: function(response) {
                    var res = response.responseJSON;
                    swal(fail, res.message, "error");
                }
            });
        });
    });

    btnRestore.on('click', function() {
        var categoryId = $(this).data('id');
        var message = $(this).data('message');
        var url = $(this).data('url');
        var title = $(this).data('labels');
        var confirm_restore = $(this).data('restore');
        var success = $(this).data('success');
        var fail = $(this).data('fail');
        var buttonOk = $(this).data('ok');
        swal({
            title: title,
            text: message,
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: 'btn-confirm',
            confirmButtonText: confirm_restore,
            cancelButtonText: noBtn,
            closeOnConfirm: false
        },
        function() {
            $.ajax({
                type: 'POST',
                url: url,
                success: function(response) {
                    swal({
                        title: success,
                        text: response.message,
                        type: "success",
                        confirmButtonClass: "btn-success",
                        confirmButtonText: buttonOk
                    },
                    function() {
                        window.location.replace(baseURL + '/users/view');
                    });
                },
                error: function(response) {
                    var res = response.responseJSON;
                    swal(fail, res.message, "error");
                }
            });
        });
    });

    btnActive.on('click', function() {
        var message = $(this).data('message');
        var url = $(this).data('url');
        var title = $(this).data('labels');
        var confirm_active = $(this).data('active');
        var success = $(this).data('success');
        var fail = $(this).data('fail');
        var buttonOk = $(this).data('ok');
        swal({
            title: title,
            text: message,
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: 'btn-confirm',
            confirmButtonText: confirm_active,
            cancelButtonText: noBtn,
            closeOnConfirm: false
        },
        function() {
            $.ajax({
                type: 'POST',
                url: url,
                success: function(response){
                    swal({
                        title: success,
                        text: response.message,
                        type: "success",
                        confirmButtonClass: "btn-success",
                        confirmButtonText: buttonOk
                    },
                    function() {
                        window.location.replace(baseURL + '/users/view');
                    });
                },
                error: function(response) {
                    var res = response.responseJSON;
                    swal(fail, res.message, "error");
                }
            });
        });
    });

    $("#login-form").submit(function(event) {
        event.preventDefault();
        var $form = $ (this),
        data = $form.serialize(),
        url = $form.attr( "action" );
        $.ajax({
            type: 'POST',
            url : url,
            data : data,
            dataType : 'json',
            success: function(result) {
                if (result.success == true) {
                    window.location.assign(result.url);
                } else {
                    var message = '';
                    $("#alert-message").hide();
                    $("#message_error").addClass("alert alert-danger");
                    for (var key in result.messages) {
                        message += '<p>' + result.messages[key] + '</p>';
                    }
                    $("#message_error").html(message);
                }
            }
        });
    });

    $("#signup-form").submit(function(event) {
        event.preventDefault();
        var $form = $ (this),
        data = $form.serialize(),
        url = $form.attr( "action" );
        $.ajax({
            type: 'POST',
            url : url,
            data : data,
            dataType : 'json',
            success: function(result) {
                console.log(result);
                if (result.success == true) {
                    window.location.assign(result.url);
                } else {
                    var message = '';
                    $("#message_error").addClass("alert alert-danger");
                    for (var key in result.messages) {
                        message += '<p>' + result.messages[key] + '</p>';
                    }
                    $("#message_error").html(message);
                }
            }
        });
    });

    loadMorePost();
    loadMoreUser();


    if (typeof(showFollowing) != "undefined" && showFollowing !== null) {
        $('ul.list-title li a.showFollowing').addClass('selected');
    } else if (typeof(showCategories) != "undefined" && showCategories !== null) {
        $('ul.list-title li a.showCategories').addClass('selected');
    } else if (typeof(showPosts) != "undefined" && showPosts !== null) {
        $('ul.list-title li a.showPosts').addClass('selected');
    }else if (typeof(userDraft) != "undefined" && userDraft !== null) {
        $('ul.list-title li a.showDraft').addClass('selected');
    }

    $('.admin').on('change', '.default-user-post-language', function() {
        var element = $(this);
        var userId = $(this).data('id');
        var language = $(this).val();
        var originalLanguage = $(this).attr('data-original-language');
        var titleChangeLang = $(this).data('text-confirm-change');
        var msgChangeLangSuccess = $(this).data('text-change-success');
        var msgChangeLangFail = $(this).data('text-change-fail');

        swal({
            title: titleChangeLang,
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: okBtn,
            cancelButtonText: noBtn,
            closeOnConfirm: false
        }, function(confirmChange) {
            if (confirmChange) {
                $.ajax({
                    type : 'POST',
                    url : baseURL + '/users/changeDefaultPostLang',
                    data : {
                        userId : userId,
                        language : language,
                    },
                    success: function(response) {
                        if (response.success) {
                            swal({
                                title: successLabel,
                                text: msgChangeLangSuccess,
                                type: 'success',
                            });
                            element.attr('data-original-language', language);
                        } else {
                            swal({
                                title: errorLabel,
                                text: msgChangeLangFail,
                                type: 'error'
                            });
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
            } else {
                element.val(originalLanguage);
            }
        });
    });
});

function loadMorePost() {
    var seeMorePost = $('#seeMorePost');
    var pageCount = 1;
    if (typeof(username) != "undefined" && username !== null) {
        var url = baseURL + '/u/' + username;
        if (typeof(userStocked) != "undefined" && userStocked !== null) {
            url = baseURL + '/u/' + username + '/clip';
            $('ul.list-title li a.showStock').addClass('selected');
        }

        seeMorePost.click(function() {
            var message = seeMorePost.data('message');
            seeMorePost.attr('disabled', 'disabled').html(message);
            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    'pageCount': pageCount
                },
                success: function(response) {
                    seeMorePost.removeAttr('disabled').html(response.msg);
                    if (response.hideSeeMore) {
                        seeMorePost.addClass('hidden');
                    }
                    $('.blog-post').append(response.html);
                    if (response.html.length === 0) {
                        seeMorePost.fadeOut("slow");
                    }
                    if (typeof showMoreTag !== 'undefined') {
                        showMoreTag();
                    }
                    pageCount ++;
                    $("img.lazy").lazyload({
                        effect : "fadeIn"
                    });
                }
            });
        });
    }
}

function loadMoreUser() {
    var seeMoreUser = $('#see-more-user');
    var pageCount = 1;
    if (typeof(username) != "undefined" && username !== null) {
        var url = baseURL + '/u/' + username + '/following';
        if (typeof(followers) != "undefined" && followers !== null) {
            url = baseURL + '/u/' + username + '/followers';
            $('ul.list-title li a.showFollowers').addClass('selected');
        }
        seeMoreUser.click(function() {
            var message = seeMoreUser.data('message');
            seeMoreUser.attr('disabled', 'disabled').html(message);
            $.ajax({
                type: 'GET',
                url: url,
                data: {
                    'pageCount': pageCount
                },
                success: function(response) {
                    seeMoreUser.removeAttr('disabled').html(response.msg);
                    if (response.hideSeeMore) {
                        seeMoreUser.addClass('hidden');
                    }
                    $('.user-follow').append(response.html);
                    if (response.html.length === 0) {
                        seeMoreUser.fadeOut("slow");
                    }
                    pageCount ++;
                }
            });
        });
    }
}

$(function(){
    $('#signup-submit-btn').attr("disabled", true);
    $('#tos-check').change(function() {
        if($(this).is(":checked")) {
            $('#signup-submit-btn').attr("disabled", false);
        } else {
            $('#signup-submit-btn').attr("disabled", true);
        }
    });
});