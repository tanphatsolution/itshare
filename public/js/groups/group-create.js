$(function () {

    $('.save').on('click', function (e) {
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: baseURL + '/groups/checkInput',
            data: $('#create-group').serialize(),
            success: function (response) {
                if (response.error) {
                    swal({
                        title: errorLabel,
                        text: response.notice,
                        type: 'error',
                    });
                } else {
                    var currentCoverStateObj = getCropItImageStateInfo(true);
                    var currentProfileStateObj = getCropItImageStateInfo(false);

                    setInputCropPosition(true, currentCoverStateObj);
                    setInputCropPosition(false, currentProfileStateObj);

                    $('form#create-group').submit();
                }
            },
            error: function () {
                swal({
                    title: errorLabel,
                    text: errorMsg,
                    type: 'error',
                });
            }
        });
    });

    $('.member-admin').on('click', '.user-remove', function () {
        $(this).parents('.group-member').remove();
    });

    $('#add-member').autocomplete({

        source: function (request, response) {
            $.ajax({
                type: 'GET',
                url: baseURL + '/groups/getUsersListWhenCreate',
                dataType: 'json',
                data: $('#create-group').serialize(),
                success: function (data) {
                    $('.ui-autocomplete').addClass('ui-autocomplete-suggest-user');
                    response($.map(data, function (item) {
                        return {
                            label: item.label,
                            id: item.id
                        };
                    }));
                }
            });
        },

        minLength: 3,

        select: function (e, ui) {
            var user = ui;
            $.ajax({
                type: 'POST',
                url: baseURL + '/groups/addMemberWhenCreate',
                data: {
                    userId: user.item.id,
                },
                success: function (response) {

                    $('#add-member').val('');

                    if (response.error) {
                        $('.add-member-notice').text(addFailMsg);
                    } else {
                        $('.member-admin').append(response.html);
                        $('.add-member-notice').text(addSuccessMsg);
                    }
                },
                error: function () {
                    swal({
                        title: errorLabel,
                        text: errorMsg,
                        type: 'error'
                    });
                }
            });
        }
    });

    $('.input-group-name').keyup(function () {
        $.ajax({
            type: 'Post',
            url: baseURL + '/groups/slug',
            dataType: 'json',
            data: $('.input-group-name').serialize(),
            success: function (data) {
                $('.input-group-shortname').val(data.name);
            }
        });
    });

});
