$(function() {

    $('#group-post-privacy').prop('disabled', true);
    if (typeof isAuthor != 'undefined' && isAuthor == 0) {
        $('#group-post-privacy').prop('disabled', true);
    } else if (typeof groupPrivacyProtected != 'undefined' && groupPrivacyProtected) {
        $('#group-post-privacy').prop('disabled', false);
    }

    $('#group-id').on('change', function() {
        var groupId = $('#group-id').val();
        $.ajax({
            type: 'GET',
            url: baseURL + '/groups/checkGroupPrivacy',
            data: {
                'groupId' : groupId,
            },
            success: function(response) {
                if (response.error) {
                    $('#group-post-privacy').prop('disabled', true);
                    $('#group-post-privacy').val('');
                } else {
                    if (response.privacy == 'public') {
                        $('#group-post-privacy').val(postPublic);
                        $('#group-post-privacy option[value=' + postPrivate + ']').text(secretPrivate);
                        $('#group-post-privacy').prop('disabled', true);
                    } else if (response.privacy == 'protected') {
                        $('#group-post-privacy').prop('disabled', false);
                        $('#group-post-privacy option[value=' + postPrivate + ']').text(closedPrivate);
                        $('#group-post-privacy').val(postPrivate);
                    } else if (response.privacy == 'private') {
                        $('#group-post-privacy').val(postPrivate);
                        $('#group-post-privacy option[value=' + postPrivate + ']').text(secretPrivate);
                        $('#group-post-privacy').prop('disabled', true);
                    } else {
                        $('#group-post-privacy').prop('disabled', true);
                        $('#group-post-privacy').val('');
                        swal({
                            title: errorLabel,
                            text: errorMsg,
                            type: 'error'
                        });
                    }
                }
            },
            error: function() {
                swal({
                    title: errorLabel,
                    text: errorMsg,
                    type: 'error'
                });
            }
        });
    });
    
    if (typeof triggerChange != 'undefined' && triggerChange == true) {
            $('#group-id').trigger('change');
    }
});
