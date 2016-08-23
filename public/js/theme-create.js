$(function() {

    $('.url-professionals').on('click', '.remove-professional', function() {
        $(this).closest('.professional-element').animate({
            opacity: 0
        }, 1).delay(500).queue(function(nxt) {
            $(this).remove();
        });
    });
        
    $('.all-themes').on('click', '.remove-theme', function() {
        $(this).closest('.theme-element').animate({
            opacity: 0
        }, 1).delay(500).queue(function(nxt) {
            $(this).remove();
        });
    });

    checkCreatedTheme();
});

function checkCreatedTheme() {
    var publishMonth = $('#publish-month').val();
    var publishYear = $('#publish-year').val();
    
    if (publishMonth != 0 && publishYear != 0) {
        $.ajax({
            type: 'GET',
            url: baseURL + '/monthlythemesubjects/checkBackNumber',
            data: {
                'publishMonth': publishMonth,
                'publishYear' : publishYear,
            },
            success: function(response) {
                if (response.backNumber) {
                    $('.content-create').hide();
                    $('.alert-created').css('visibility', 'visible');
                    swal({
                        title: 'Notice!',
                        text: 'This month already created theme.',
                        type: 'error',
                    });
                } else {
                    $('.content-create').css('visibility', 'visible');
                    $('.content-create').show();
                    $('.alert-created').css('visibility', 'hidden');
                }
            },
            error: function() {
                swal({
                    title: 'Error!',
                    text: 'Something went wrong.',
                    type: 'error',
                });
            },
        });
    }
}