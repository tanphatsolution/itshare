function approveGroupUser(elm, flag) {
    var groupId = $(elm).attr('data-group-id');
    var userId = $(elm).attr('data-user-id');

    var parentGroupUnapprovedListElement = $(elm).parents('.group-unapproved-users-list');
    var parentApproveElement = $(elm).parent();

    $.ajax({
        type: 'POST',
        url: baseURL + '/groups/users/approve',
        data: {
            'groupId': groupId,
            'userId': userId,
            'approveFlag': flag
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
