$(function() {

    $('#join-group-btn').on('click', function() {
        var groupId = $(this).data('group-id');
        var url_to_group = $(this).data('url');

        $.ajax({
            type: 'POST',
            url: baseURL + '/groups/join',
            data: {
                'groupId': groupId,
                'joinFlag': 1,
            },
            success: function(response) {
                window.location.href = url_to_group;
            }
        });
    });
});