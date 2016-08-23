function approveGroupPost(elm) {

    var url = baseURL + '/groups/posts/approve';

    denyOrApprovePost(elm, url);
}

function denyGroupPost(elm) {
    var url = baseURL + '/groups/posts/deny';

    denyOrApprovePost(elm, url);
}

function denyOrApprovePost(elm, url) {

    var groupEncryptedId = $(elm).attr('data-group-encrypted-id');
    var postEncryptedId = $(elm).attr('data-post-encrypted-id');

    var parentGroupUnapprovedListElement = $(elm).parents('.group-unapproved-list');
    var parentApproveElement = $(elm).parent();

    $.ajax({
        type: 'POST',
        url: url,
        data: {
            'groupEncryptedId': groupEncryptedId,
            'postEncryptedId': postEncryptedId
        },
        success: function(response) {
            if (!response.error) {
                parentApproveElement.empty();
                parentGroupUnapprovedListElement.html(response.data);
            } else {
                swal(errorLabel, response.message, 'error');
            }
        }
    });
}