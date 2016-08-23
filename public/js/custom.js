$(document).ready(function() {

    $('li.dropdown').click(function() {
        $('li.dropdown').not(this).find('ul').hide();
        $(this).find('ul').toggle();
    });

    var keys = {
        enter: 13,
        space: 32
    };

    var $rgns = $('message');

    $(window).resize(function() {
        var wWidth = $(window).width();

        if ( wWidth >= 992 ) {
            $('.message').attr('aria-hidden','true');
        }

        return false;
    });

    $('.controls li').keydown(function(e) {

        if (e.altKey || e.ctrlKey || e.shiftKey) {
            // do nothing
        } else if (e.keyCode == keys.enter || e.keyCode == keys.space) {

            var $rgn =  $('#' + $(this).attr('aria-controls'));

            if ($(this).attr('aria-expanded') == 'true') {

                $(this).attr('aria-expanded', 'false');
                $rgn.attr('aria-hidden', 'true');

                $(this).find('span').html('Show');
            } else {
                $(this).attr('aria-expanded', 'true');
                $rgn.attr('aria-hidden', 'false');

                $(this).siblings().attr('aria-expanded', 'false');
                $rgn.siblings().attr('aria-hidden', 'true');

                $(this).siblings().find('span').html('Show');
                $(this).find('span').html('Hide');
            }

            e.stopPropagation();
            return false;
        }

        return true;
    });

    $('.controls li').click(function(e) {
        var $rgn =  $('#' + $(this).attr('aria-controls')),
            bodyNoScrollFlag = false;

        if ($(this).attr('aria-expanded') == 'true') {
            $(this).attr('aria-expanded', 'false');
            $rgn.attr('aria-hidden', 'true');
            $(this).find('span').html('Group');
        } else {
            $(this).attr('aria-expanded', 'true');
            $rgn.attr('aria-hidden', 'false');

            $(this).siblings().attr('aria-expanded', 'false');
            $rgn.siblings().attr('aria-hidden', 'true');

            $(this).siblings().find('span').html('Group');
            $(this).find('span').html('Group');
        }

        $(this).parent().find('li').each(function() {
            if ($(this).attr('aria-expanded') == 'true') {
                bodyNoScrollFlag = true;
            };
        });

        if (bodyNoScrollFlag) {
            $('body').addClass('no-scroll');
            $('#region_wrapper').css('bottom', 0);
        } else {
            $('body').removeClass('no-scroll');
            $('#region_wrapper').css('bottom', 'auto');
        }

        e.stopPropagation();
        return false;
    });

    // Close menu dropdown target
    $('body').on('tap', '#region_wrapper, .header-res', function(elem){
        if ($(elem.target).is(this)) {
            $('.header-res .header-res-menu ul.controls').find('li').each(function() {
                if ($(this).attr('aria-expanded') == 'true') {
                    triggerClickCrossBrowser($(this)[0]);
                };
            })
        }
    });

    $(window).scroll(function() {
        if ($(this).scrollTop() > 200) {
            $('nav').addClass('navbar-scroll');
        } else {
            $('nav').removeClass('navbar-scroll');
        }
    });

    function triggerClickCrossBrowser(element) {
        if(document.createEvent) {
            var evt = document.createEvent("MouseEvents");
            evt.initMouseEvent("click", true, true, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
            element.dispatchEvent(evt);
        }
        else {
            element.click();
        }
    }
});
