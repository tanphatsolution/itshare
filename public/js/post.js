
$(function() {
    stock();
    var processing = false;

    var editorConfig = {
        buttons: {
            bold: { text: '**', close: true },
            italic: { text: '_', close: true },
            heading: { text: '\n ### ', close: false },
            order_list: { text: '- ', close: false },
            code: { text: '`', close: true },
            quote: { text: '> ', close: false }
        }
    };

    var contentChanged = false;
    var contentChangedAutoSave = false;

    if ($('#editor').length > 0) {
        contentChanged = true;
        var input = $('#editor').parent().find('.markdown-content-hidden');
        var editor = $('#editor').codeMirrorMarkDownEditor();

        $(window).load(function() {
            setOutput(editor.getValue());
            processImageInPost();
            showLabelLang();
        });

        editor.on('change', function() {
            input.val(editor.getValue());
            setOutput(editor.getValue());
            processImageInPost();
            contentChanged = true;
            contentChangedAutoSave = true;
            showLabelLang();
        });

        editor.on('scroll', function() {
            var scrollTop = editor.doc.scrollTop;
            var previewDiv = $(".preview")[0];
            var content = $(".CodeMirror-scroll")[0];
            var diff = (previewDiv.scrollHeight + 100) / content.scrollHeight;
            previewDiv.scrollTop = scrollTop * diff;
        });
    }

    $('.editorButton').tooltip();

    $('.editorButton').click(function() {
        var editorButton = $(this).attr('data');
        var inserted = editorConfig.buttons[editorButton];
        var selectedText = editor.doc.getSelection();
        if (selectedText) {
            selectedText = inserted.text + selectedText + (inserted.close ? inserted.text : ' ');
            editor.doc.replaceSelection(selectedText);
        } else {
            editor.doc.replaceSelection(inserted.text);
        }
        editor.focus();
    });

    var languageSelector = $('#language-selector');

    for (var i in supportedLanguages) {
        languageSelector.append($('<option>').html(supportedLanguages[i]));
    }

    languageSelector.change(function() {
        var index = getSupportedLanguagesIndex(languageSelector.val());
        if (index === -1) {
            return;
        } else {
            var lang = supportedLanguages[index];
        }
        var selectedText = editor.getSelection();
        var insertText = '```' + lang + '\n';
        if (selectedText) {
            selectedText = insertText + selectedText + '\n```';
            editor.replaceSelection(selectedText);
        } else {
            editor.replaceSelection(insertText);
        }
        editor.focus();
        languageSelector.val($("#language-selector option:first").val());
    });

    var themeSelector = $('#theme-selector');

    themeSelector.change(function() {
        var theme = $(this).val();
        if (editorThemes.indexOf(theme) === -1) {
            return;
        }
        var themeName = theme.toLowerCase().split(' ').join('-');
        editor.setOption('theme', themeName)
    });

    if (typeof userTheme != 'undefined') {
        $('#theme-selector option').eq(userTheme+1).prop('selected', true).trigger('change');
    }

    var checkAutoSave = true;
    var isPublished = $('.post.create-post').data('is-published');

    if (!isPublished) {
        var timeToSave = setInterval(autoSave, 10000);
    }

    $('.btn.btn-detail, .btn.btn-draft').on('click', function() {
        if (processing) {
            return;
        }
        $(this).addClass('disabled');
        clearInterval(timeToSave);
    });

    function autoSave() {
        if (checkAutoSave == false) {
            return;
        }

        processing = true;
        var title = $("#title").val();
        var category = $("#category-input").val();
        var monthlyThemeId = $("#monthly-theme-id").val();
        var language_code = $('#language_code').val();
        var share_by_url = $('#share-by-url').val();
        var group_id = $('#group-id').val();
        var post_privacy_flag = $('#group-post-privacy').val();
        //url for post
        var postUrl = '/posts/autoSaveDraft';
        //url for question
        if ($('#type_post').val() === "question") {
            postUrl = '/faq/questions'
        }
        if (typeof editor != 'undefined') {
            var content = editor.getValue();
        }
        var encrypted_id = $("#encrypted_id").val();

        if (contentChangedAutoSave && content != '' && title != '') {
            checkAutoSave = false;
            $.ajax({
                type: 'POST',
                url: baseURL + postUrl,
                data: {
                    'encrypted_id' : encrypted_id,
                    'title' : title,
                    'category' : category,
                    'content': content,
                    'monthly_theme_id' : monthlyThemeId,
                    'language_code' : language_code,
                    'share_by_url' : share_by_url,
                    'group_id' : group_id,
                    'privacy_flag' : post_privacy_flag,
                    'autoSaveRunning' : true
                },
                success: function(response) {
                    $("#encrypted_id").val(response.encrypted_id);
                    if (response.saved === true) {
                        $.notify({ message: "Auto saved at " + response.saved_time });
                    }
                    checkAutoSave = true;
                    contentChangedAutoSave = false;
                    processing = false;
                },
                error: function(){
                    checkAutoSave = true;
                    processing = false;
                }
            }).done(function() {
                checkAutoSave = true;
                contentChangedAutoSave = false;
                processing = false;
            });
        }
    }

    initLinkTarget();
    initTocTree();

    $('#menuTocTree a').on('click', function (e) {
        e.preventDefault();
        var id = $(this).attr('href');
        $('html, body').stop().animate({
            'scrollTop': $(id).offset().top - 100
        }, 900, 'swing', function () {
            window.location.hash = id;
        });
        console.log($(id).offset().top);
    });

    if ($('#category-input').val() != null) {
        initCategoryInput();
    }

    if ($('#image-uploader').val() != null) {
        var title = $('#image-uploader').data('title');
        if (title) {
            $('#image-uploader').imageUploader({title: title, postEditor: editor});
        } else {
            $('#image-uploader').imageUploader({postEditor: editor});
        }
    }

    if ($.isFunction($.fn.dropzone)) {
        $('.thumbnail-uploader').dropzone({
            url: baseURL + '/images/upload',
            maxFilesize: MAX_IMAGE_SIZE,
            paramName: 'image',
            acceptedFiles: 'image/*',
            headers: {'X-CSRF-Token': $('meta[name="_token"]').attr('content')},
            previewTemplate : '<div style="display:none"></div>',
            init: function() {
                this.on('success', function(file, message) {
                    var imgUrl = '![' + message.original_name + '](' + message.url + ') \n';
                $('#thumbnail').val(message.url);
                $('.thumb-preview').css('background', "url('" + message.url +"') center");
                $('.thumb-preview').css('background-size', 'cover');
                $('.remove-thumb').addClass('btn-remove-thumb glyphicon glyphicon-remove-sign');

                    file.previewElement.addEventListener('click', function() {

                    });
                });
                this.on('error', function(file, response) {
                    var errorMessage = response;
                    if (typeof (response.status) !== 'undefined' && response.status === 'error') {
                        errorMessage = response.message;
                    }
                    swal(errorMessage);
                });
            }
        });
    }

    report();

    $('#delete-post').click(function() {
        var url = $(this).data('url');
        var message = $(this).data('message');
        var title = $(this).data('label');

        swal({
            title: title,
            text: message,
            type: 'warning',
            showCancelButton: true,
            confirmButtonClass: 'btn-confirm',
            confirmButtonText: yesBtn,
            cancelButtonText: noBtn,
            closeOnConfirm: false
        },
        function() {
            $.ajax ({
                type: 'DELETE',
                url: url,
                success: function(response) {
                    swal({
                        title: successLabel,
                        text: response.message,
                        type: 'success',
                        confirmButtonClass: 'btn-success',
                        confirmButtonText: okBtn
                    },
                    function () {
                        window.location.replace(baseURL + '/posts');
                    });
                },
                error: function(response) {
                    var res = response.responseJSON;
                    swal(errorLabel, res.message, 'error');
                }
            });
        });
    });

    $('#unpublished-post').click(function() {
        var url = $(this).data('url');
        var message = $(this).data('message');
        var title = $(this).data('label');

        swal({
            title: title,
            text: message,
            type: 'warning',
            showCancelButton: true,
            confirmButtonClass: 'btn-confirm',
            confirmButtonText: yesBtn,
            cancelButtonText: noBtn,
            closeOnConfirm: false
        },
        function() {
            $.ajax ({
                type: 'PATCH',
                url: url,
                success: function(response) {
                    swal({
                        title: successLabel,
                        text: response.message,
                        type: 'success',
                        confirmButtonClass: 'btn-success',
                        confirmButtonText: okBtn
                    },
                    function () {
                        document.location.reload(true);
                    });
                },
                error: function(response) {
                    var res = response.responseJSON;
                    swal(errorLabel, res.message, 'error');
                }
            });
        });
    });

    loadMoreUserStock();

    if (typeof ref !== 'undefined') {
        var commentId = '#comment' + ref;
        scrollToComment(commentId);
    }

    $('.post-content img, .comment-content img').each(function() {
        $(this).fancybox({
            href : $(this).attr('src')
        });
    });

    $('.btn-remove-img').click(function() {
        var postId = $(this).data('post-id');
        var thumbnail = $('.thumbnail-uploader');
        thumbnail.attr('src', '/img/thumbnail-default.png');
        $('input[id="thumbnail"]').val('');
    });

    $('.markdownContent').processText();

    processImageInPost();

    $('.show-clipped-users').click(function() {
        $.ajax({
            type: 'GET',
            url: baseURL + '/posts/getListUserStockModal',
            data : { 'postId' : post_id },
            success: function(response) {
                if (response.result) {
                    $('#modal-list-stocked-users').empty().append(response.modal);
                    $('#modalUserStock').modal('show');
                } else {
                    swal({
                        title: errorLabel,
                        text: errorMsg,
                        type: 'error',
                    });
                }
            }
        });
     });

    var pageCount = 1;
    var filterBy = $('#post_filter').val();
    var seeMorePost = $('#seeMorePost');

    $('#post_filter').change(function() {
        var filter_by = $(this).val();
        window.location.href = baseURL + '/posts/' + wall + '/' + filter_by;
    });

    seeMorePost.click(function() {
        var message = seeMorePost.data('message');
        seeMorePost.attr('disabled', 'disabled').html(message);
        $('#seeMorePost').removeClass('origin-load-more');
        $.ajax({
            type: 'GET',
            url: baseURL + '/posts',
            data: {
                'pageCount': pageCount,
                'wall': wall,
                'filterBy': filterBy,
                'lang': seoLang
            },
            success: function(response) {
                seeMorePost.removeAttr('disabled').html(response.msg);
                if (response.hideSeeMore) {
                    seeMorePost.addClass('hidden');
                }
                $('.post-list').append(response.html);
                if (response.html.length === 0) {
                    seeMorePost.fadeOut("slow");
                }
                if (typeof showMoreTag !== 'undefined') {
                    showMoreTag();
                }
                pageCount ++;
                $('#seeMorePost').addClass('origin-load-more');
                $("img.lazy").lazyload({
                    effect : "fadeIn"
                });
            }
        });
    });

    $(".themes-in-month").on('change', '#monthly-theme-id', function() {
        $("#hidden-theme-id").val($(this).val());
    });

    $('#comment').on('click', '.helpful-question .helpful-button', function() {
        var helpful = $(this).data('helpful');
        $.ajax({
            type: 'POST',
            url: baseURL + '/posthelpfuls',
            data: {
                'post_id' : post_id,
                'helpful' : helpful,
            },
            success: function(response) {
                if (response.success) {
                    $('.voted').empty().fadeIn().append('<span class="label label-success">' + response.message + '</span>');
                    if (response.helpful) {
                        $('#btn-helpful').empty().text(helpful_btn + ' (' + (helpful_count + 1) + ')');
                        $('#btn-not-helpful').addClass('btn-voted');

                        //Action HAVE_BEEN_HELPFUL
                        sendDataRequest('HAVE_BEEN_HELPFUL');
                    } else {
                        $('#btn-not-helpful').empty().text(not_helpful_btn + ' (' + (not_helpful_count + 1) + ')');
                        $('#btn-helpful').addClass('btn-voted');

                        //Action HAVE_NOT_BEEN_HELPFUL
                        sendDataRequest('HAVE_NOT_BEEN_HELPFUL');
                    }
                } else {
                    $('.voted').empty().fadeIn().append('<span class="label label-danger">' + response.message + '</span>');
                }
            }
        });
    });

    var themeId = $("#hidden-theme-id").val();
    if (typeof themeId != 'undefined') {
        getMonthlyThemes(themeId);
    }

    initShareButton();
    $(window).scroll(function() {
        initShareButton();
    });

    $('.remove-thumb').on('click', function() {
        $('input[id="thumbnail"]').val('');
        $('.remove-thumb').removeClass('btn-remove-thumb glyphicon glyphicon-remove-sign');
        $('.thumb-preview').css('background', "url('/img/img-thumb-default.png') center");
        $('.thumb-preview').css('background-size', 'cover');

    });

    $('.advance').on('click', function() {
        $('.btn-advance').toggleClass('open');
        $('.list-advance').slideToggle('fast');
    });

    if (typeof thumbnailFlag != 'undefined' && thumbnailFlag == 1) {
        $('.remove-thumb').addClass('btn-remove-thumb glyphicon glyphicon-remove-sign');
    }

    $('#modal-code').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var modalBody = $(this).find('.modal-body');
        var preElement = document.createElement('pre');

        modalBody.empty();
        $(preElement).append(button.parent().find('pre').html());
        modalBody.html(preElement);
    });

    if (typeof(encrypted_id) != "undefined" && encrypted_id !== null) {
        $('body img.lazy').lazyload({
            effect : 'fadeIn'
        });
    }

    $('.bootstrap-tagsinput input').attr('style', 'width: 16em !important;');

    $('.join-this-group').click(function(e) {
        var elementJoin = $(this);
        var groupId = elementJoin.data('id');
        var joinFlag = elementJoin.data('flag');

        $.ajax({
            type: 'POST',
            url: baseURL + '/groups/join',
            data: {
                'groupId': groupId,
                'joinFlag': joinFlag
            },
            success: function(response) {
                if (!response.error) {
                    if (joinFlag == 1) {
                        elementJoin.data('flag', '0');
                    } else {
                        elementJoin.data('flag', '1');
                    }
                    elementJoin.html(elementJoin.data('flag') == 0 ? undoRequestText : joinThisGroupText);

                    swal(successLabel, response.message, 'success');
                } else {
                    swal(errorLabel, response.message, 'error');
                }
            }
        });
    });

});

function initShareButton() {
    var offset = 100;
    var fixedTop = 74;
    var offsetBottom = 600;
    if ($(window).scrollTop() > offset) {
        if ($(window).scrollTop() + fixedTop + $('.share-social-vertical').height() + offsetBottom < $(document).height()) {
            $('.share-social-vertical').css('top', (fixedTop + $(window).scrollTop() - offset) + 'px').removeClass('static');
        } else {
            $('.share-social-vertical').css('top', ($(document).height() + fixedTop - offset - $('.share-social-vertical').height() - offsetBottom ) + 'px').removeClass('static');
        }
    } else {
        $('.share-social-vertical').css('top', fixedTop + 'px').addClass('static');
    }
}

function initCategoryInput() {
    $('#category-input').tagsinput({
        tagClass: 'label label-primary',
        trimValue: true,
        typeahead: {
            source: categories,
            autoSelect: false
        }
    });

    $('.bootstrap-tagsinput').addClass('col-xs-12 mgbt-0');
    $('.topCategories').click(function() {
        var category = $(this).data('category');
        $('#category-input').tagsinput('add', category);
    })
}

var previewStages = {
    not: 'not-previewing',
    half: 'half-previewing',
    full: 'full-previewing'
};

var navStages = {
    full: 'full-preview',
    halfl: 'half-preview-left',
    halfr: 'half-preview-right',
    close: 'close-preview'
};

var postBodyWrapper = $('#postBodyWrapper');

function previewNavigation() {
    if (postBodyWrapper.hasClass(previewStages.not)) {
        $('i.' + navStages.full).show();
        $('i.' + navStages.halfl).show();
        $('i.' + navStages.halfr).hide();
        $('i.' + navStages.close).hide();
        return;
    }
    if (postBodyWrapper.hasClass(previewStages.half)) {
        $('i.' + navStages.full).hide();
        $('i.' + navStages.halfl).show();
        $('i.' + navStages.halfr).show();
        $('i.' + navStages.close).hide();
        return;
    }
    if (postBodyWrapper.hasClass(previewStages.full)) {
        $('i.' + navStages.full).hide();
        $('i.' + navStages.halfl).hide();
        $('i.' + navStages.halfr).show();
        $('i.' + navStages.close).show();
        return;
    }
}

$('.preview-nav').on('click', function() {
    if (postBodyWrapper.hasClass(previewStages.not)) {
        postBodyWrapper.removeClass(previewStages.not);
        if ($(this).hasClass(navStages.full)) {
            postBodyWrapper.addClass(previewStages.full);
        } else {
            postBodyWrapper.addClass(previewStages.half);
        }
    } else if (postBodyWrapper.hasClass(previewStages.half)) {
        postBodyWrapper.removeClass(previewStages.half);
        if ($(this).hasClass(navStages.halfl)) {
            postBodyWrapper.addClass(previewStages.full);
        } else {
            postBodyWrapper.addClass(previewStages.not);
        }
    } else if (postBodyWrapper.hasClass(previewStages.full)) {
        postBodyWrapper.removeClass(previewStages.full);
        if ($(this).hasClass(navStages.halfr)) {
            postBodyWrapper.addClass(previewStages.half);
        } else {
            postBodyWrapper.addClass(previewStages.not);
        }
    }
    previewNavigation();
});

function initLinkTarget() {
    var linkTarget = $('.markdownContent').find('a');
    linkTarget.each(function () {
       $(this).attr("target", '_blank');
    });
}

function initTocTree() {
    var tocTree = $('.markdownContent').find('h1, h2, h3');

    tocTree.each(function (i) {
        var current = $(this);
        var link = stringSanitizer(current.text()) + '-' + i;
        current.attr("id", link);
        var singleLink = '<li><a class="' + current.prop("tagName") + '" href="' + '#' + link + '">' + htmlEncode(current.text()) + '</a></li>';
        $(singleLink).appendTo('ul#menuTocTree');
    });
}

function stringSanitizer(str) {
    str = str.toLowerCase();
    str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
    str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
    str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
    str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
    str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
    str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
    str = str.replace(/đ/g, "d");
    /* tìm và thay thế các kí tự đặc biệt trong chuỗi sang kí tự - */
    str = str.replace(/!|@|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\.|\:|\;|\'| |\"|\&|\#|\[|\]|~|$|_/g, "-");
    //thay thế -- thành -
    str = str.replace(/-+-/g, "-");
    str = str.replace(/^\-+|\-+$/g, "");
    //cắt bỏ ký tự - ở đầu và cuối chuỗi
    return str;
}

function report() {
    $('#report-post').click(function() {
        var title = $(this).data('title');
        var message = $(this).data('message');
        var content = $(this).data('content');
        var report = $(this).data('report');
        var post_id = $(this).data('post-id');
        bootbox.dialog({
            title: title,
            message: message + content,
            buttons: {
                success: {
                    label: report,
                    className: "btn-primary",
                    callback: function () {
                        var type = $('input[name=type]:checked').val();
                        $.ajax({
                            type: 'POST',
                            url: baseURL + '/reports',
                            data: {'post_id': post_id, 'type': type},
                            success: function(response) {
                                bootbox.alert(response.message);
                            }
                        });
                    }
                }
            }
        });
    });
}

function stock() {
    $('.post-list').on('click', '.icon-stock', function() {
        var element = $(this);
        var postId = $(this).attr('post_id');

        if ($(this).hasClass('activated')) {
            element.attr('disabled','disabled');
            $.ajax({
                type: 'DELETE',
                url: baseURL + '/clip/' + postId,
                success: function() {
                    element.removeClass('activated');
                    element.removeAttr('disabled');
                }
            });
        } else {
            element.attr('disabled','disabled');
            $.ajax({
                type: 'POST',
                url: baseURL + '/clip',
                data: {'postId': postId},
                success: function() {
                    element.addClass('activated');
                    element.removeAttr('disabled');
                }
            });
        }
    })
}

function loadMoreUserStock() {
    $(document).on('click', '#seeMoreUserStock', function () {
        var self = $(this),
            postId = self.data('post');
            message = self.data('message');
            start = self.attr('data-start');
            increase = self.data('increase');

        self.attr('disabled', 'disabled').html(message);
        $.ajax({
            type: 'GET',
            url: baseURL + '/posts/listclip',
            data : { 'start' : start, 'postId' : postId},
            success: function(response) {
                self.removeAttr('disabled').html(response.message);
                newStart = parseInt(start) + increase;
                self.attr('data-start', newStart);
                if (response.html) {
                    $('ul#userStockList').append(response.html);
                    // Must be reload relationships.js when append new container-follow elements
                    $.getScript(baseURL + '/js/relationships.js');
                }
                if (response.end) {
                    self.removeAttr('id').fadeOut();
                }
            }
        });
    });
}

function scrollToComment(id) {
    if ($(id).length == 0) {
        $('html, body').animate({
            scrollTop: $('.last-comment').offset().top
        }, 1000);
    } else {
        $('html, body').animate({
            scrollTop: $(id).offset().top
        }, 1000);
    }
}

function getMonthlyThemes(themeId) {
    var monthlyThemeSubjectId = $('#monthly-theme-subject-id').val();
    $.ajax({
        type: 'GET',
        url: baseURL + '/monthlythemesubjects/getListMonthlyThemes',
        data: {
            'monthlyThemeSubjectId': monthlyThemeSubjectId,
            'themeId' : themeId
        },
        success: function(response) {
            $('.themes-in-month').empty().html(response.html);
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

// Preview
var md = markdownit({ html: false }).use(markdownitFootnote);

function setOutput(val) {
    $('#screen_preview').html(md.render(val));
}

function showLabelLang() {
    $('#screen_preview pre code').each(function () {
        var stringLanguage = $(this).attr('class');
        var languageName = 'Default';
        if (stringLanguage != undefined) {
            languageName = stringLanguage.slice(9, stringLanguage.length);
        }
        $(this).prepend('<span class="label label-default">' + languageName + '</span><br/>');
    });

    //show table format
    $('#screen_preview').find('table').each(function (index, element) {
        if (!$(element).hasClass('table')) {
            $(element).addClass('table table-bordered table-nonfluid');
        }
    });
}


var load = false;
$(window).scroll(function () {
    if ($(window).scrollTop() >= 200 && !load) {
        if (typeof(encrypted_id) != "undefined" && encrypted_id !== null) {
            load = true;
            $.ajax({
                type: 'GET',
                url: baseURL + '/related/load',
                data: {'encrypted_id': encrypted_id},
                success: function (data) {
                    $('#related').hide().html(data).fadeIn(1000);
                }
            });
        }
    }
});

