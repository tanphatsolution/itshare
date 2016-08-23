setTimeout(function () {
    $.ajax({
        type: 'GET',
        url: baseURL + '/posts/counter',
        data: {'post_id' : post_id},
        success: function(result) {
            $('#viewCount').html(result);
        }
    });
}, 15000);