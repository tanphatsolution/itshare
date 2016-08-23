$(function() {

    $("[name='display_slider']").bootstrapSwitch({
        size: 'mini',
        offColor: 'warning',
        onText: 'Yes',
        offText: 'No'
    });

    $('#theme-image').change(function() {
        var checkImg = checkImage(this);
        $('#user-change-main-picture').val(1);
        if (checkImg) {
            readURL(this);
        } else {
            $(this).val('');
            $('.thumb-preview').children('img').attr('src', '/img/img-slideadmin.jpg');
            swal({
                title: 'Error!',
                text: 'Only jpg, jpeg, png, gif images extension and less than 6000KB images size are supported.',
                type: 'error',
            });
        }
    });

    $('.url-professionals').on('change', '.slider-image, .professional-image', function() {
        var checkImg = checkImage(this);
        if (!checkImg) {
            $(this).val('');
            swal({
                title: 'Error!',
                text: 'Only jpg, jpeg, png, gif images extension and less than 6000KB images size are supported.',
                type: 'error',
            });
        }
    });

    $('.url-professionals').on('click', '.add-professional', function() {
        var n = $('.professional-element').length;
        if (n <= maxProfessional) {
            $('.professionals').append(aProfessional).children(':last').animate({
                opacity: 1
            }, 1);
        }
        $(this).removeClass('add add-professional').addClass('remove remove-professional');
    });

    $('.all-themes').on('click', '.add-theme-this-month', function() {
        var n = $('.theme-element').length;
        if (n <= maxProfessional) {
            $('.themes-this-month').append(aTheme).children(':last').animate({
                opacity: 1
            }, 1);
        }
        $(this).removeClass('add add-theme-this-month').addClass('remove remove-theme');
    });

    $('.save').on('click', function() {
        $.ajax({
            type: 'POST',
            url: baseURL + '/monthlythemesubjects/checkInput',
            data: $("#monthly-theme-form").serialize(),
            success: function(response) {
                if (response.error) {
                    swal({
                        title: 'Error!',
                        text: response.notice,
                        type: 'error',
                    });
                } else {
                    $('form#monthly-theme-form').submit();
                }
            },
            error: function() {
                swal({
                    title: 'Error!',
                    text: 'Something went wrong.',
                    type: 'error',
                });
            }
        });
    });
});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('.thumb-preview').children('img').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function checkImage(image) {
    var file = image.files[0];
    var fileType = file['type'];
    var fileSize = file['size']/1000;
    var ValidImageTypes = ['image/gif', 'image/jpeg', 'image/png', 'image/jpg',];
    var checkImg = true;
    if (($.inArray(fileType, ValidImageTypes) < 0) || (fileSize > imgMaxSize)) {
        checkImg = false;
    }
    return checkImg;
}