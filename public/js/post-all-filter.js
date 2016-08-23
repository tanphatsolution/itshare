$(function() {
    $('.load-more.fake-link.hidden-load-more').css('display', 'none');
    $('#post_filter').change(function() {
        filterBy = $('#post_filter').val();
        pageCount = 1;
    });
});

var viewType;
var filterBy = $('#post_filter').val();

function getEventTarget(e) {
    e = e || window.event;
    return e.target || e.srcElement;
}

$('#seeMorePostInFilter').on('click', function() {
    var seeMore = $(this);
    var message = seeMore.data('message');
    seeMore.attr('disabled', 'disabled').html(message);
    viewType = seeMore.attr('data-target-tab');
    $.ajax({
        type: 'GET',
        url: baseURL + '/posts/filtersInAll/' + viewType + '/' + pageCount + '/' + filterBy,
        success: function(response) {
            seeMore.removeAttr('disabled').html(response.seeMoreLabel);
            if (response.hideSeeMore) {
                seeMore.addClass('hidden');
            }
            $('.blog-post').append(response.data);
            if (typeof showMoreTag !== 'undefined') {
                showMoreTag();
            }
            pageCount ++;
        }
    });
});