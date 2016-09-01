$(document).ready(function() {

    // make navbar when display on mobile
    function makeMobileNabar() {
        $('#menu .navbar .navbar-header button').click(function() {
            var status = $('#menu .navbar .navbar-collapse').css('display');

            if (status == 'none') {
                $('body').css({"overflow-y": "hidden"});
                $('#content, #content, #footer, #slogan, #underSlogan').css('opacity', 0.7);
            } else {
                $('body').css('overflow-y', 'scroll');
                $('#content, #content, #footer, #slogan, #underSlogan').css('opacity', 1);
            }
        });
    }

    makeMobileNabar();

    $(window).resize(function() {
        var width = $(this).width();

        if (width < 768) {
            makeMobileNabar();
        } else {
            $('body').css('overflow-y', 'scroll');
            $('#content, #content, #footer, #slogan, #underSlogan').css('opacity', 1);
        }
    });
});
