$(function() {
    checkBackNumber();
});

function checkBackNumber() {
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
                    $('.alert-not-create').css('visibility', 'hidden');
                    $('.edit-theme').empty().append(response.html);
                } else {
                    $('.edit-theme').empty();
                    $('.alert-not-create').css('visibility', 'visible');
                    swal({
                        title: noticeLabel,
                        text: noTheme,
                        type: 'error',
                    });
                }
            },
            error: function() {
                swal({
                    title: errorLabel,
                    text: errorMsg,
                    type: 'error',
                });
            },
        });
    }
}