$(function() {

    scrolltop();

    $('body').on('click', '.close-popup-signup', function() {
        $('#pop-up').fadeOut(500);
        $('#pop-up').remove();
    });

    $('body').on('click', '.btn-login-modal', function() {
        $('#modal-popup-signup').modal('hide');
        setTimeout(function() {
            $('#modal-popup-login').modal('show');
        }, 700);
    });

    $('body').on('click', '.btn-register-modal, .btn-sign-up', function() {
        $('#modal-popup-login').modal('hide');
        setTimeout(function() {
            $('#modal-popup-signup').modal('show');
        }, 700);
    });

    $('body').on('submit', '.viblo-login-form', function(event) {
        
        event.preventDefault();
        var form = $(this);
        var errorElement = $(this).parents('.form-content').find('.message-error');

        $.ajax({
            type: 'POST',
            url : baseURL + '/users/login',
            data : form.serialize(),
            dataType : 'json',
            headers: { 'X-XSRF-TOKEN' : $("meta[name='csrf-token']").attr('content') },

            success: function(result) {
                if (result.success) {
                    window.location.assign(result.url);
                } else {
                    var message = '';
                    for (var key in result.messages) {
                        message += '<p>' + replaceAttrMsg(result.messages[key]) + '</p>';
                    }
                    errorElement.empty().append(message);
                    var contentHeight = 459 + errorElement.height();
                    $('body .l-box-login').css('height', contentHeight + 'px');
                }
            },

            error: function() {
                errorElement.empty().append('<p>' + errorMsg + '</p>');
                var contentHeight = 459 + errorElement.height();
                $('body .l-box-login').css('height', contentHeight + 'px');
            }
        });
    });

    $('body').on('submit', '.viblo-signup-form', function(event) {

        event.preventDefault();
        var form = $(this);
        var tos = $(this).parents('.form-content').find('.tos-check');
        var errorElement = $(this).parents('.form-content').find('.message-error');

        if (tos.is(':checked')) {
            $.ajax({
                type: 'POST',
                url : baseURL + '/users/signup',
                data : form.serialize(),
                dataType : 'json',

                success: function(result) {
                    if (result.success) {
                        window.location.assign(result.url);
                    } else {
                        var message = '';
                        for (var key in result.messages) {
                            message += '<p>' + replaceAttrMsg(result.messages[key]) + '</p>';
                        }
                        errorElement.empty().append(message);
                        var contentHeight = 599 + errorElement.height();
                        $('body .l-box-register').css('height', contentHeight + 'px');
                    }
                },

                error: function() {
                    errorElement.empty().append('<p>' + errorMsg + '</p>');
                    var contentHeight = 599 + errorElement.height();
                    $('body .l-box-register').css('height', contentHeight + 'px');
                }
            });
        } else {
            errorElement.empty().append('<p>' + agreeWithTerms + '</p>');
            var contentHeight = 599 + errorElement.height();
            $('body .l-box-register').css('height', contentHeight + 'px');
        }
    });
});

function scrolltop() {
    var offset = 350;
    var duration = 500;

    if ($(window).scrollTop() >= offset) {
        $('#pop-up').fadeIn(duration);
    }

    $(window).scroll(function() {
        if ($(this).scrollTop() >= offset) {
            $('#pop-up').fadeIn(duration);
        } else {
            $('#pop-up').fadeOut(duration);
        }
    });
}

function replaceAttrMsg(message) {
    var result = message;
    if ($.isArray(message)) {
        var result = message[0];
    }
    var AttrArr = ['username', 'name', 'email', 'password confirmation', 'password'];
    var LabelsArr = {
        'username' : userNameLabel,
        'name' : nameLabel,
        'email' : emailLabel,
        'password' : passwordLabel,
        'password confirmation' : confirmPwdLabel
    };

    for(var i = 0; i < AttrArr.length; i++) {
        result = result.replace(AttrArr[i], LabelsArr[AttrArr[i]]);
    }

    return result;
}
