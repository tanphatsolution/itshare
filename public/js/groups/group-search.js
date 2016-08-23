$(function() {
    $('#load-more').on('click', function() {
        $.ajax({
            type: 'POST',
            url: baseURL + '/groups/' + groupEncryptedId +'/search',
            data: {
                'pageCount': pageCount,
                'groupId': groupId,
                'keywords': keywords,
            },
            success: function(response) {
                displayData(response);
                pageCount ++;
            }
        });
    });
});

function displayData(data) {
    $('#group-contents').append(data['views']);
    if(!data['hideSeeMore']) {
        $('.load-more').removeClass('hidden');
    } else {
        $('.load-more').addClass('hidden');
    }
}