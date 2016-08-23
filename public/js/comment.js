$(function () {
    var flag = true;
    var pageCnt = 1;
    var editor;
    var comment = $('#comment');

    comment.on('click', '.comment-placeholder', function () {
        editor.setValue('');
        $(this).addClass('hidden');
        $('.comment-section').removeClass('hidden');
        editor.refresh();
        editor.focus();
    });

    comment.on('click','#view', function () {
        $('#comment-image-uploader').hide();
        if (!$('#tab-view').hasClass('clicked')) {
            var content = editor.getValue();
            $.ajax({
                type: 'POST',
                url: baseURL + '/comments/preview',
                data: {'content': content},
                success: function (data) {
                    $('#tab-view').addClass('clicked').append(data).processText({force: true});
                    processImageInPost();
                }
            });
        }
    });

    comment.on('click','#edit', function () {
        $('#tab-view').text('').removeClass('clicked');
        $('#comment-image-uploader').show();
        editor.focus();
    });

    comment.on('click', '.btn-send', function (e) {
        e.preventDefault();
        var content = editor.getValue();
        $.ajax({
            type: 'POST',
            url: baseURL + '/comments',
            data: {'post_id': post_id, 'content': content},
            success: function (response) {
                if (response.error === true) {
                    swal('Error', response.data, 'error');
                } else {
                    $('.list-comment').append(response.data);
                    $('.display-comment').processText();
                    editor.setValue('');
                    editor.refresh();
                    $('.CodeMirror-gutter-wrapper').next().remove();
                    $('#tab-view').html('');
                    $('.comment-section').addClass('hidden');
                    $('.comment-placeholder').removeClass('hidden');
                    $('#commentCount').text(response.commentCount);
                    processImageInPost();
                    sendDataRequest('HAVE_COMMENTED');
                }
            }
        });
    });

    comment.on('click', '.delete-comment', function () {
        if (flag) {
            var element = $(this).closest('.body-comment');
            var id = element.attr('id').replace('comment', '');
            var deleteButton = element.find('.btn-delete');
            var message = deleteButton.data('message');
            var title = deleteButton.data('title');
            var confirm = deleteButton.data('confirm');
            swal({
                    title: title,
                    text: message,
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: 'btn-confirm',
                    confirmButtonText: confirm,
                    closeOnConfirm: false
                },
                function () {
                    $.ajax({
                        type: 'DELETE',
                        url: baseURL + '/comments/' + id,
                        success: function (response) {
                            if (response.error === false) {
                                swal({
                                        title: successLabel,
                                        text: response.message,
                                        type: "success",
                                        confirmButtonClass: "btn-success",
                                        confirmButtonText: okBtn
                                    },
                                    function () {
                                        element.remove();
                                        var currentCommentCount = parseInt($('#commentCount').text());
                                        $('#commentCount').text(currentCommentCount - 1);
                                    });
                            } else {
                                swal(errorLabel, response.message, "error");
                            }
                        }
                    });
                });
        }
    });
    comment.on('click', '.edit-comment', function () {
        if (flag) {
            var container = $(this).closest('.body-comment');
            var id = container.attr('id').replace('comment', '');
            $.ajax({
                type: 'GET',
                url: baseURL + '/comments/' + id + '/edit',
                success: function (data) {
                    container.find('.comment-show').addClass('hidden');
                    container.append(data);
                    commentEditor = container.find('.comment-update').codeMirrorMarkDownEditor();
                    commentEditor.setSize(null, 200);
                    commentEditor.focus();
                    if ($('#image-uploader').val() != null) {
                        var title = $('#image-uploader').data('title');
                        if (title) {
                            $('#image-uploader').imageUploader({title: title, postEditor: commentEditor});
                        } else {
                            $('#image-uploader').imageUploader({postEditor: commentEditor});
                        }
                    }
                    flag = false;
                },
                error: function () {
                }
            });
        }
    });

    comment.on('click', '.btn-cancel', function () {
        if (flag == false) {
            var elementEdit = $(this).closest('.comment-update-container');
            var elementShow = $(this).closest('.body-comment').find('.comment-show');
            elementShow.removeClass('hidden');
            elementEdit.remove();
            flag = true;
        }
    });
    comment.on('click', '.btn-save', function () {
        if (flag == false) {
            var element = $(this).closest('.body-comment');
            var elementEdit = $(this).closest('.comment-update-container');
            var elementShow = $(this).closest('.body-comment').find('.comment-show');
            var content = commentEditor.getValue();
            var id = element.attr('id').replace('comment', '');
            if (content.length === 0) {
                return;
            }
            $.ajax({
                type: 'PUT',
                url: baseURL + '/comments/update',
                data: {'id': id, 'content': content},
                success: function (response) {
                    if (response.error == false) {
                        elementShow.removeClass('hidden');
                        elementShow.find('.display-comment').html(response.data);
                        elementShow.find('.display-comment').processText({force: true});
                        elementEdit.remove();
                        flag = true;
                        processImageInPost();
                    }
                    $('.btn-cancel').trigger('click');
                }
            });
        }
    });

    comment.on('click', '.btn-more', function () {
        $.ajax({
            type: 'GET',
            url: baseURL + '/comments',
            data: {'post_id': post_id, 'offset': pageCnt},
            success: function (data) {
                if (!data['hasMore']) {
                    $('.btn-more').addClass('hidden');
                }
                $('.list-comment').append(data['view']);
                $('.display-comment').processText();
                pageCnt++;
            }
        });
    });
    comment.on('click','.btn-comment-cancel', function () {
        editor.setValue('');
        editor.refresh();
        $(':focus').blur();
        $('.comment-placeholder').removeClass('hidden');
        $('.comment-section').addClass('hidden');
    });

    var loaded = false;
    $(window).bind('scroll', function() {
        if($(window).scrollTop() >= comment.offset().top + comment.outerHeight() - window.innerHeight - 200 && !loaded) {
            loaded = true;
            $.ajax({
                type: 'POST',
                url: baseURL + '/comments/load',
                data: {'postId': encrypted_id},
                success: function (data) {
                    comment.hide().html(data).fadeIn(1000);
                    if ($('#comment-editor').length > 0) {
                        editor = $('#comment-editor').codeMirrorMarkDownEditor();
                        editor.setSize(null, 200);
                        var commentEditor;
                    }
                    $('.display-comment').processText();
                    processImageInPost();
                    if ($('#comment-image-uploader').val() != null) {
                        var title = $('#comment-image-uploader').data('title');
                        if (title) {
                            $('#comment-image-uploader').imageUploader({title: title, postEditor: editor});
                        } else {
                            $('#comment-image-uploader').imageUploader({postEditor: editor});
                        }
                    }
                }
            });
        }
    });
});