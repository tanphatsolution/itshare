$(function() {
    $('.load-more').click(function(e) {
        e.preventDefault();
        $.ajax({
            type: 'GET',
            url: baseURL + '/themes/professionals',
            data: {
                'pageCount': pageCount,
                'monthlyThemSubjectId': monthlyThemSubjectId
            },
            success: function(response) {
                displayPost(response);
                pageCount ++;
            }
        });
    });

    homeSlider.init({
        timeOut: 5000
    });

});

function displayPost(data) {
    $('#professional-area').append(data['views']);
    if(!data['hideSeeMore']) {
        $('.load-more').removeClass('hidden');
    } else {
        $('.load-more').addClass('hidden');
    }
}