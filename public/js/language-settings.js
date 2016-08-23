$(function() {

    if (typeof isSetDefaultLang != 'undefined' && isSetDefaultLang == 0) {
        callModalLanguage();
    }

    $('#language-setting, #language-setting-res, #language-setting-footer').on('click', function() {
        $('#m2').attr('aria-hidden', 'true');
        callModalLanguage();
    });

    $('#modal-language-settings-content').on('click', '#add-language', function () {
        var n = $('.language-element').length;
        var element = $(this);
        if (n <= maxLanguages) {
            var arrLang = [];
            var item = aLanguage;
            if(n > 0) {
                $('.language-element').find('.language-post').each(function() {
                    arrLang.push($(this).val());
                });
                item = '<li class="language-element"><select class="form-control language-post" name="languages[]">';
                var check = true;
                $('option', $(aLanguage)).each(function() {
                    if (($.inArray($(this).val(), arrLang) < 0) && (check)) {
                        item += '<option value="' + $(this).val() + '" selected="selected" >' + $(this).text() + '</option>';
                        check = false;
                    } else {
                        item += '<option value="' + $(this).val() + '" >' + $(this).text() + '</option>';
                    }
                });
                item += '</select></li>';
            }
            $('.languages').append(item).children(':last').animate({
                opacity: 1
            }, 1);
            var form = element.parents('.form-filter-post-language').find('.post-filter-language');
            form.find('.type-selected').remove();
            var typeHidden = '<div class="type-selected"><input type="hidden" value="' + element.data('type') + '" name="type" ></div>';
            requestToServer(element, form, typeHidden);
        }
    });

    $('#modal-language-settings-content').on('click', '.remove-language', function () {
        $(this).closest('.language-element').animate({
            opacity: 0
        }, 1).delay(300).queue(function(nxt) {
            $(this).remove();
        });

        var element = $(this).parent().find('.language-post');
        var form = element.parents('.form-filter-post-language').find('.post-filter-language');
        form.find('.type-selected').remove();
        var typeHidden = '<div class="type-selected"><input type="hidden" value="' + element.data('type') + '" name="type" ></div>';
        $(this).parent().remove();
        requestToServer(element, form, typeHidden);
        return false;
    });

    $('#modal-language-settings-content').on('click', '#selected-all-languages', function() {
        var element = $(this);
        var form = element.parents('.form-filter-post-language').find('.post-filter-language');
        form.find('.type-selected').remove();
        var typeHidden = '<div class="type-selected"><input type="hidden" value="' + element.data('type') + '" name="type" ></div>';
        requestToServer(element, form, typeHidden);
    });

    $('#modal-language-settings-content').on('change', '.language-post', function() {
        var element = $(this);
        var form = element.parents('.form-filter-post-language').find('.post-filter-language');
        form.find('.type-selected').remove();
        var typeHidden = '<div class="type-selected"><input type="hidden" value="' + element.data('type') + '" name="type" ></div>';
        requestToServer(element, form, typeHidden);
    });

    $('#modal-language-settings-content').on('change', '#system-language', function() {
        var element = $(this).parents('.system-language-setting');
        var systemLang = element.find('#system-language').val();
        var type = null;
        var url = baseURL + '/languages/settings/changeLanguageSystem';
        var btnClass = $(this).attr('class');
        var thisBtn = $(this);
        callAjax(element, systemLang, type, url, btnClass, thisBtn);
        $(this).removeClass('original-btn-yellow');
    });

    $('#modal-language-settings-content').on('click', '#change-default-post-language, #change-default-all-post-language', function() {
        var element = $(this).parents('.default-create-post-language');
        var defaultLang = element.find('#default-post-language').val();
        var type = $(this).data('type');
        var url = baseURL + '/languages/settings/changeDefaultPostLang';
        var btnClass = $(this).attr('class');
        var thisBtn = $(this);
        callAjax(element, defaultLang, type, url, btnClass, thisBtn);
    });

    $('#modal-language-settings-content').on('change', '#default-post-language', function() {
        var element = $(this).parents('.default-create-post-language');
        var defaultLang = $(this).val();
        var type = $(this).data('type');
        var url = baseURL + '/languages/settings/changeDefaultPostLang';
        var btnClass = $(this).attr('class');
        var thisBtn = $(this);
        callAjax(element, defaultLang, type, url, btnClass, thisBtn);
    });

    $('#modal-language-settings-content').on('click', '#change-top-page-lang', function() {
        var element = $(this).parents('.change-toppage-language');
        var topPageLang = element.find('#top-page-language').val();
        var type = null;
        var url = baseURL + '/languages/settings/changeTopPageLang';
        var btnClass = $(this).attr('class');
        var thisBtn = $(this);
        callAjax(element, topPageLang, type, url, btnClass, thisBtn);
    });

    $('#modal-language-settings-content').on('hidden.bs.modal', '#modal-language-settings', function () {
        var hostname = window.location.href;
        if (hostname.indexOf('theme') >= 0) {
            window.location.href = window.location.href.replace(/[\?#].*|$/, "?language=change");
        } else {
            location.reload();
        }
    });

    $('body').on('click', '.remove-language', function(e) {
        $(this).parent().remove();
    });
    
});

function callModalLanguage() {
    $.ajax({
        type: 'POST',
        url: baseURL + '/languages/settings',
        success: function(response) {
            if (response.modal != '') {
                $('#modal-language-settings-content').empty().append(response.modal);
                $('#modal-language-settings').modal('show');
            } else {
                swal('Fail!', 'Something went wrong!', 'error');
            }
        },
        error: function(response) {
            swal('Fail!', 'Something went wrong!', 'error');
        },
    });
}
