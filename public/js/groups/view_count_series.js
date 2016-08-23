setTimeout(function () {
    $.ajax({
        type: 'GET',
        url: baseURL + '/groupseries/counter',
        data: {'series_id' : series_id},
        success: function(result) {
            $('#viewCount').html(result);
        }
    });
}, 15000);