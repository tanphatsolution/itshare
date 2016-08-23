$(function() {
    $('#choice-image-header').click(function(e) {
        e.preventDefault();
        $('#cover-img').click();
    });

    $('#choice-group-icon').click(function(e) {
        e.preventDefault();
        $('#profile-img').click();
    });

    $('.group_member').click(function() {
        var groupId = $(this).data('id');
        var inputMember = $('#showMember' + groupId).val();
        $.ajax({
            type: 'GET',
            url: baseURL + '/groupUsers',
            data: { groupId: groupId },
            success: function(response) {
                if (response.result) {
                    $('#modal-list-group-users').empty().append(response.modal);
                    $('#modalGroupUsers').modal('show');
                } else {
                    swal({
                        title: errorLabel,
                        text: errorMsg,
                    });
                }
            },
            error: function() {
                swal({
                    title: errorLabel,
                    text: errorMsg,
                    type: 'error',
                });
            }
        });
    });

    $('body').on('click', '.btn-join-group', function(e) {
        var elementJoin = $(this);
        var groupId = elementJoin.data('id');
        var joinFlag = elementJoin.data('flag');
        $.ajax({
            type: 'POST',
            url: baseURL + '/groups/join',
            data: {
                'groupId': groupId,
                'joinFlag': joinFlag
            },
            success: function(response) {
                if (!response.error) {
                    if (joinFlag == 1) {
                        elementJoin.data('flag', '0');
                    } else {
                        elementJoin.data('flag', '1');
                    }
                    elementJoin.html(elementJoin.data('flag') == 0 ? undoRequest : joinGroupLabel);
                    console.log(undoRequest);

                    swal(successLabel, response.message, 'success');
                } else {
                    swal(errorLabel, response.message, 'error');
                }
            }
        });
    });

    $('#cover-img').on('change', function(e) {
        var changeSuccess = onChangeGroupImage(this);
    });

    $('#profile-img').on('change', function(e) {
        var changeSuccess = onChangeGroupImage(this);
    });
});

function onChangeGroupImage(element) {
    var parentElement = $(element).parent();
    var checkImg = checkImage(element);

    if (!checkImg) {
        swal({
            title: errorLabel,
            text: 'Only jpg, jpeg, png, gif images extension and less than ' + imgMaxSize + 'KB images size are supported.',
            type: 'error'
        });
        $(element).val('');

        parentElement.find('img').attr('src', '/img/img-groupdetail.png');
    } else {
        readURL(element, parentElement);
    }

    return checkImg;
}

function readURL(input, parentElement) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            parentElement.find('img').attr('src', e.target.result);
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
