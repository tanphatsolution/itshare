$(function() {
    $('.load-more').click(function() {
        $.ajax({
            type: 'GET',
            url: baseURL + '/theme/' + subjectCategoryName + '/' + categoryName,
            data: {'pageCount': pageCount},
            success: function(response) {
                displayPost(response);
                pageCount ++;
            }
        });
    });
});

function displayPost(data) {
    $('.blog-post').append(data['views']);
    if (typeof showMoreTag !== 'undefined') {
        showMoreTag();
    }
    if(!data['hideSeeMore']) {
        $('.load-more').removeClass('hidden');
    } else {
        $('.load-more').addClass('hidden');
    }
}