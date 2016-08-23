(function ($) {
    // Markdown CodeMirror Editor
    $.fn.codeMirrorMarkDownEditor = function (userOptions) {
        var options = {
            lineWrapping: true,
            lineNumbers: true,
            indentUnit: 4,
            styleActiveLine: true,
            matchBrackets: true,
            viewportMargin: Infinity,
            foldGutter: {
                rangeFinder: new CodeMirror.fold.combine(CodeMirror.fold.brace, CodeMirror.fold.markdown)
            },
            gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
            extraKeys: {
                "Enter": "newlineAndIndentContinueMarkdownList",
                "Ctrl-Q": function (cm) {
                    cm.foldCode(cm.getCursor());
                },
                "Tab": "indentMore"
            },
            theme: 'default',
            mode: 'markdown'
        };
        $.extend(options, userOptions);

        var editors = [];

        this.each(function () {
            var id = $(this).attr('id');
            if (!id) {
                return false;
            }
            var editor = CodeMirror.fromTextArea(document.getElementById(id), options);
            editor.setSize(null, 560);

            editors.push(editor);
        });

        if (editors.length === 1) {
            return editors[0];
        }
        return editors;
    };

    // Highlight
    $.fn.highlight = function (userOptions) {
        var options = {
            theme: 'monokai'
        };
        $.extend(options, userOptions);

        this.each(function () {
            $(this).find('code').each(function (index, element) {
                var text = $("<div/>").html($(this).html()).text();
                $(this).html(text);
                var code = $(this);
                var className = code.attr('class');
                var lang = parseLanguage(className);
                var index = getSupportedLanguagesIndex(lang);
                if (index !== -1) {
                    if (supportedLanguages[index] != 'TextileToHtml') {
                        code.empty();
                        var label = '<span class="label label-default">' + supportedLanguages[index] + '</span><br>';
                        var codeArea = '<textarea class="code-mirror-area">' + text + '</textarea>';
                        code.prepend(label);
                        code.append(codeArea);

                        var mode = getCodeMirrorMode(lang);
                        if (mode == 'twig') {
                            mode = {name: mode, htmlMode: true}
                        }

                        var codeMirrorOptions = {
                            mode: mode,
                            theme: 'monokai',
                            lineNumbers: false,
                            readOnly: true
                        };

                        var codeHighlight = CodeMirror.fromTextArea(code.find('textarea')[0], codeMirrorOptions);
                        codeHighlight.setSize("100%", "120%");
                    } else {
                        var preElement = code.parent();
                        var textileContentElement = $(document.createElement('div')).prop('class', 'textile-html-content');
                        var textileHtmlContent = textile.convert(prettyTextileTable(text));
                        //textileHtmlContent = htmlEncode(textileHtmlContent);
                        textileContentElement.append(textileHtmlContent);
                        preElement.after(textileContentElement);
                        preElement.remove();
                        textileContentElement.find('a').attr('target', '_blank');

                        textileContentElement.processTable();
                    }
                }
            });
        });

        function prettyTextileTable(textStr) {
            var regex = /\|[ ]*\n([^\n\|]+)/gi;
            return textStr.replace(regex, "|$1")
                .replace(/&lt;pre&gt;/g, '<pre>')
                .replace(/p&lt;/g, 'p<')
                .replace(/p&gt;/g, 'p>')
                .replace(/p<&gt;/g, 'p<>')
                .replace(/&lt;\/pre&gt;/g, '</pre>');
        }

        function decodeHTMLEntities(textStr) {
            return $("<textarea/>").html(textStr).text();
        }
    };

    // Add table class
    $.fn.processTable = function () {
        this.each(function () {
            $(this).find('table').each(function (index, element) {
                if (!$(element).hasClass('table')) {
                    $(element).addClass('table table-bordered table-nonfluid');

                    $(element).find('pre').each(function (idx, preElement) {
                        var content = $(preElement).html();

                        if (content) {
                            $(preElement).wrap("<div class='content-popup-view'></div>");
                            $(preElement).parent().append(
                                "<a href='#' class='view-code-content' data-toggle='modal' data-target='#modal-code'>view</a>"
                            );
                        }
                    });
                }

                $(element).wrap("<div class='table-responsive'></div>");
            });
        })
    };

    // Process text
    $.fn.processText = function (userOptions) {
        var options = {
            force: false,
            highlight: true
        };
        $.extend(options, userOptions);
        this.each(function () {
            if ($(this).data('processed') === true && options.force !== true) {
                return;
            }
            $(this).processTable();
            var text = $(this).html();
            text = replaceEmoticons(text);
            var exp = /(^|\s|<p>)@([a-z0-9][a-z0-9_\.]+)/gi;
            text = text.replace(exp, "$1<a href='" + baseURL + "/u/$2'>@$2</a>");
            $(this).html(text);
            $('.loading').hide();
            $(this).fadeIn(1000);
            $(this).attr('data-processed', true);
            if (options.highlight === true) {
                $(this).highlight();
            }
        });

        function replaceEmoticons(text) {
            var patterns = [];
            for (var i in emoticons) {
                patterns.push('(' + emoticons[i].regex + ')');
            }
            text = text.replace(new RegExp(patterns.join('|'), 'g'), function (match) {
                var emo = findEmo(match);
                if (emo === false) {
                    return match;
                }
                var imgUrl = getEmoUrl(emo.src);
                var title = emo.key;
                var alt = emo.key;
                var replaceText = emoticons[match] != 'undefined' ?
                '<img class="emoticons-img" src="' + imgUrl + '" title="' + title + '" alt="' + alt + '"/>' :
                    match;
                return replaceText;
            });
            return text;
        }

        function findEmo(regex) {
            for (var i in emoticons) {
                var key = emoticons[i].key;
                if (regex == key) {
                    return emoticons[i];
                }
            }
            return false;
        }

        function getEmoUrl(img) {
            if (img.indexOf('https://') == 0 || img.indexOf('http://') == 0) {
                return img;
            }
            return baseURL + "/img/emoticons/" + img;
        }

        function htmlEncode(value) {
            return $('<div/>').text(value).html();
        }
    };

    // Image Uploader
    $.fn.imageUploader = function (userOptions) {
        var options = {
            title: 'Image Uploader'
        };
        $.extend(options, userOptions);
        this.each(function () {
            $(this).click(function () {
                initImageUploader();
            });

            function initImageUploader() {
                var box = bootbox.dialog({
                    message: '<form class="dropzone" id="my-awesome-dropzone" style="min-height:300px"></form>',
                    title: options.title
                });

                if ($.isFunction($.fn.dropzone)) {
                    var myDropzone = $('#my-awesome-dropzone').dropzone({
                        url: baseURL + '/images/upload',
                        maxFilesize: MAX_IMAGE_SIZE,
                        paramName: 'image',
                        acceptedFiles: 'image/*',
                        previewTemplate: getPreviewTemplate(),
                        headers: {'X-CSRF-Token': $('meta[name="_token"]').attr('content')},
                        init: function () {
                            this.on('success', function (file, message) {
                                var imgUrl = '![' + message.original_name + '](' + message.url + ') \n\n';
                                var element = $(file.previewElement);
                                var btnInsert = element.find('.btn-insert');
                                var btnCopy = element.find('.btn-copy');
                                btnCopy.attr('data-clipboard-text', imgUrl);
                                var clipBoard = new ZeroClipboard(btnCopy);
                                clipBoard.on('ready', function (readyEvent) {
                                    clipBoard.on('aftercopy', function (event) {
                                        console.log('copied');
                                    });
                                });
                                $('.progress').hide();
                                $('.btn-insert').click(function () {
                                    if (userOptions.postEditor) {
                                        userOptions.postEditor.replaceSelection(imgUrl);
                                        userOptions.postEditor.focus();
                                    }
                                    if (userOptions.inputElement) {
                                        userOptions.inputElement.val(message.url);
                                    }
                                    if (userOptions.preview) {
                                        userOptions.preview.attr('style', 'background: url(' + message.url + ')');
                                    }
                                });
                            });
                            this.on('error', function (file, response) {
                                var errorMessage = response;
                                var element = $(file.previewElement);
                                element.remove();
                                if (typeof (response.status) !== 'undefined' && response.status === 'error') {
                                    errorMessage = response.message;
                                }
                                swal(errorMessage);
                            });
                            this.on('totaluploadprogress', function (progress) {
                                $('#uploadProgress .progress-bar').css('width', progress + '%');
                            });
                        }
                    });
                }
            }

            function getPreviewTemplate() {
                return '<div class="dz-preview dz-file-preview" style="word-break:break-all">' +
                    '<div class="dz-details">' +
                    '<div id="uploadProgress" class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">' +
                    '    <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress><span>Please wait...</span></div>' +
                    '</div>' +
                    '<div class="dz-filename text-primary"><span data-dz-name></span>  <span class="dz-size text-info" data-dz-size></span></div>' +
                    '    <img data-dz-thumbnail />' +
                    '</div>' +
                    '<div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>' +
                    '<strong><div class="dz-success-message text-success">' +
                    '<div class="btn btn-success btn-xs btn-copy">Copy Markdown text</div> ' +
                    '<div class="btn btn-success btn-xs btn-insert">Insert Markdown text</div>' +
                    '</div></strong>' +
                    '<strong><div class="dz-error-message text-danger"></div></strong>' +
                    '<hr>' +
                    '</div>';
            }
        });
    };

    //send feedback
    $('#send-feed-back').on('click', function () {
        var title = $('#title-feedback').val();
        var message = $('#message-feedback').val();
        var email = $('#email-user-feedback').val();
        $.ajax({
            type: 'POST',
            url: baseURL + '/feedbacks',
            data: {
                'title': title,
                'message': message,
                'email': email
            },
            success: function (response) {
                if (response.errors) {
                    swal({
                        title: noticeLabel,
                        text: response.message,
                        type: 'error'
                    });
                } else {
                    swal({
                        title: thanksLabel,
                        text: response.message,
                        type: 'success'
                    });
                    $('#title-feedback').val('');
                    $('#message-feedback').val('');
                }
            },
            error: function () {
                swal({
                    title: sorryLabel,
                    text: errorMsg,
                    type: 'error'
                });
            }
        });
    });

}(jQuery));

function parseLanguage(className) {
    if (className !== undefined && className.indexOf('language-') === 0) {
        return className.split('-')[1];
    }
    return false;
}

function getSupportedLanguagesIndex(item) {
    if (!item) {
        return -1;
    }

    for (var i = 0; i < supportedLanguages.length; i++) {
        if (supportedLanguages[i].toLowerCase() === item.toLowerCase()) {
            return i;
        }
    }

    return -1;
}

function processImageInPost() {
    var img = $('.post-text-content img:not(.emoticons-img), .box-comment img:not(.emoticons-img), .post-content img:not(.emoticons-img)');
    img.css('cursor', 'pointer');
    img.each(function () {
        if (!$(this).hasClass('markdown-icon')) {
            $(this).addClass('lazy image-view');
            var data_origin = $(this).attr('data-original');
            if (typeof(data_origin) == "undefined" || data_origin == null) {
                data_origin = $(this).attr('src');
            }
            $(this).attr('href', data_origin) ;
            $(this).parent().addClass('post-detail-image-wrapper');
        }
    });
    $('.post-detail-image-wrapper').css('text-align', 'center');
    if ($('.image-view').length > 0) {
        $('.image-view').fancybox({
            href: $(this).attr('src'),
            width: '100%',
            padding: 0,
            margin: 0,
            autoScale: false,
            scrolling: false,
            autoDimensions: false,
            autoSize: true,
            openSpeed: 100,
            closeSpeed: 50,
            helpers: {
                title: null,
                overlay: {
                    css: {
                        'background': 'rgba(0, 0, 0, 0.95)'
                    },
                    locked: true
                }
            },
            afterShow: function () {
                $(".fancybox-wrap, #fancybox-overlay").swipe({
                    tap: function (event, target) {
                        $.fancybox.close();
                    },
                    swipe: function (event, direction, distance, duration, fingerCount) {
                        $.fancybox.close();
                    }
                });
            }
        });
    }
}

$('body img.lazy').lazyload({
    effect : 'fadeIn'
});

function getCodeMirrorMode(lang) {
    if (!lang) {
        return '';
    }
    return (typeof highlightModeMappingArr[lang.toLowerCase()] === 'undefined') ? '' : highlightModeMappingArr[lang.toLowerCase()];
}

function htmlEncode(html) {
    return document.createElement('a').appendChild(
        document.createTextNode(html)).parentNode.innerHTML;
}

function htmlDecode(html) {
    var a = document.createElement('a');
    a.innerHTML = html;

    return a.textContent;
}

function showMoreTag() {
    $('.tags').each(function () {
        var topPositionCheck = 0;
        var anchorTag = $(this).find('a');
        $(anchorTag).each(function () {
            if ((!$(this).hasClass('show-more-tag'))) {
                topPositionCheck = (topPositionCheck == 0) ? $(this).position().top : topPositionCheck;
                if ($(this).position().top > topPositionCheck) {
                    $(this).parent().parent().addClass('has_more_tag');
                    return false;
                }
            }
        });
    });

    if ($('.has_more_tag').length > 0) {
        var width = parseInt($('.tags').width());
        $('.has_more_tag .tag-item').css({'width': (width - 31) + 'px'});
        var anchors = '';
        var topPosition = 0;
        $('.has_more_tag').each(function () {
            $(this).find('a').each(function () {
                if ((!$(this).hasClass('show-more-tag'))) {
                    if (topPosition == 0) {
                        topPosition = $(this).position().top;
                    }
                    if ($(this).position().top > topPosition) {
                        anchors = '<a class="' + $(this).attr('class') + '" href="' + $(this).attr('href') + '">' + $(this).html() + '</a>' + anchors;
                        $(this).remove();
                    }
                } else {
                    if (anchors != '') {
                        $(this).data('content', anchors);
                    }
                }
            });
        });
        $('.has_more_tag .tag-item').css({width: 'auto'});
        $('.has_more_tag .tag-see-more').addClass('more-visible');
    }
}

$(window).load(function () {
    showMoreTag();
    $('body').on('click', '.show-more-tag', function () {
        $(this).popover({
            html: true,
            animation: false,
            placement: function (tip, element) {
                var offset = $(element).offset();
                height = $(document).outerHeight();
                width = $(document).outerWidth();
                vert = 0.5 * height - offset.top;
                vertPlacement = vert > 0 ? 'bottom' : 'top';
                horiz = 0.5 * width - offset.left;
                horizPlacement = horiz > 0 ? 'right' : 'left';
                placement = Math.abs(horiz) > Math.abs(vert) ? horizPlacement : vertPlacement;
                return placement;
            }
        }).popover('show');
    });
});
