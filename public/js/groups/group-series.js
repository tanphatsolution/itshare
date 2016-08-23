var getCurrentImageUploadBtn = null;

$(function() {

    $('.sortable').sortable({items: '.sortable-item'});
    $('.sortable').disableSelection();

    $('#element-input').on('click', '.btn-add', function() {

        var type = $('#link-type').val();
        changeElementInput(type);
    });

    $('.group-series-list, .element-input').on('click','.btn-remove-item', function() {

        var element = $(this).closest('.sortable-item');

        element.animate({
            opacity: 0
        }, 1).delay(500).queue(function(nxt) {
            $(this).remove();
        });
    });

    $('.save').on('click', function() {

        var formType = $(this).data('form-type');

        if (formType == 'create') {
            var form = $('#create-series');
        } else {
            var form = $('#update-series');
        }

        $.ajax({
            type: 'POST',
            url: baseURL + '/groupseries/checkInput',
            data: form.serialize(),
            success: function(response) {
                if (response.error) {
                    swal({
                        title: errorLabel,
                        text: response.notice,
                        type: 'error'
                    });
                } else {
                    form.submit();
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
    });

    var timer;
    var delay = 1000; // 1 second

    $('#element-input, .group-series-list').on('input', '.input-url', function() {
        var inputElement = $(this);
        var inputUrl = inputElement.val();
        var linkType = inputElement.data('link-type');
        var preview = $(this).parents('.link-item-element').find('.item-preview');

        if (inputUrl.length > 4) {
            window.clearTimeout(timer);
            timer = window.setTimeout(function() {
                preview.empty().append('<img src="/img/notif-loading.gif" />');

                $.ajax({
                    type: 'POST',
                    url: baseURL + '/groupseries/item',
                    data: {
                        url: inputUrl,
                        type: linkType,
                        encryptedGroupId: encryptedGroupId,
                    },
                    contentType: "application/x-www-form-urlencoded;charset=utf-8",
                    success: function(response) {
                        if (response != 'false') {
                            preview.empty().append(response);
                        } else {
                            var typeName = typeof typeNameArr[linkType] == 'undefined' ? '' : typeNameArr[linkType];
                            var notice = '<span class="incorrect-link">Please enter correct ' + typeName + ' link.</span>';
                            notice += '<input type="hidden" name="incorrectFlag" value="1">';
                            preview.html(notice);
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
            }, delay);
        }
    });

    $('.group-series-list').on('click', '.img-uploader', function(e) {

        $('#image-uploader').click();
        e.preventDefault();
        getCurrentImageUploadBtn = $(this);
    });

    $('#image-uploader').dropzone({

        url: baseURL + '/images/upload',
        maxFilesize: MAX_IMAGE_SIZE,
        paramName: 'image',
        acceptedFiles: 'image/*',
        headers: {'X-CSRF-Token': $('meta[name="_token"]').attr('content')},
        previewTemplate : '<div style="display:none"></div>',

        init: function() {
            this.on('success', function(file, message) {
                var inputUrl = baseURL + message.url;
                getCurrentImageUploadBtn.parents('.link-item-element').find('.input-url').val(inputUrl);
                var linkType = typeImage;
                var preview = getCurrentImageUploadBtn.parents('.link-item-element').find('.item-preview');
                $.ajax({
                    type: 'POST',
                    url: baseURL + '/groupseries/item',
                    data: {
                        url: inputUrl,
                        type: linkType,
                    },
                    contentType: "application/x-www-form-urlencoded;charset=utf-8",
                    success: function(response) {
                        if (response != 'false') {
                            preview.empty().append(response);
                        } else {
                            swal({
                                title: noticeLabel,
                                text: noticeLinkSeries,
                                type: 'error'
                            });
                            preview.empty();
                            getCurrentImageUploadBtn.parents('.link-item-element').find('.input-url').val('');
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
            });

            this.on('uploadprogress', function() {
                var preview = getCurrentImageUploadBtn.parents('.link-item-element').find('.item-preview');
                preview.empty().append('<img src="/img/notif-loading.gif" />');
            });

            this.on('error', function(file, response) {
                var errorMessage = response;
                
                if (typeof (response.status) !== 'undefined' && response.status === 'error') {
                    errorMessage = response.message;
                }
                swal(errorLabel, errorMessage, 'error');
            });
        }
    });

});

function changeElementInput(type) {
    $.ajax({
        type: 'POST',
        url: baseURL + '/groupseries/getElementBtnByLinkType',
        data: {
            type: type,
        },
        success: function(response) {
            $('.group-series-list').append(response.html);
        },
    });
}