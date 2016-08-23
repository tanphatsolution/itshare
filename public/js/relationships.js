$(function() {
    var container = $('body');
    var followSection = '#relationship-follow';

    $('.box-profile .relationship-container, .post-author').on('click', '.btn-follow-mini', function() {
        var followers = $('.number-follow').text();
        if ($(this).hasClass('btn-following')) {
            var followersNumber = parseInt(followers) - 1;
            //Log CLICK_FOLLOW_AUTHOR
            sendDataRequest('CLICK_FOLLOW_AUTHOR');
        } else {
            var followersNumber = parseInt(followers) + 1;
            //Log CLICK_FOLLOW_AUTHOR
            sendDataRequest('CLICK_UNFOLLOW_AUTHOR');
        }
        $('.number-follow').text(followersNumber);
    });

    $('.list-follow .relationship-container').on('click', '.btn-follow-mini', function() {
        var followings = $('.followingNumbers.owner').text();
        if ($(this).hasClass('btn-following')) {
            var followingNumbers = parseInt(followings) - 1;
        } else {
            var followingNumbers = parseInt(followings) + 1;
        }
        $('.followingNumbers.owner').text(followingNumbers);
    });

    var checkAjaxDone = true;

    container.on("submit", followSection, function(event) {
        if (checkAjaxDone == false) {
            return;
        }
        var currentContainer = $(this).parent();
        event.preventDefault();
        var $form = $(this),
            data = $form.serialize(),
            url = $form.attr("action"),
            action = $form.data('action'),
            type = 'POST';
        if (action == 'unfollow') {
            type = 'DELETE';
        }
        checkAjaxDone = false;
        $.ajax({
            type: type,
            url: url,
            data: data,
            success: function(result)
            {
                if (result.error == false) {
                    checkAjaxDone = true;
                    currentContainer.parent().find('.number-follow').text(result.numberFollowers);
                    currentContainer.html(result.html);
                }
            },
            error: function() {
                checkAjaxDone = true;
            }
        }).done(function() {
            checkAjaxDone = true;
        });
    });
});
