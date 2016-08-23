var pageCnt = 1;

$(document).ready(function(){
    var container = $('.category-list');
    var followId = '#category-follow';
    var unfollowId = '#category-unfollow';
    var viewType = 'new';

    $('body').on("submit", followId, function(event) {
        var currentContainer = $(this).parent();
        event.preventDefault();
        var $form = $(this),
            data = $form.serialize(),
            url = $form.attr("action");
        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            success: function(result)
            {
                if(!result.error){
                    currentContainer.html(result.html);
                    $('#category-follow-count-' + result.category_id).html(result.follow_count);
                }
            }
        });
    });

    $('body').on("submit", unfollowId, function(event) {
        var currentContainer = $(this).parent();
        event.preventDefault();
        var $form = $(this),
            data = $form.serialize(),
            url = $form.attr("action");
        $.ajax({
            type: 'DELETE',
            url: url,
            data: data,
            success: function(result)
            {
                if(!result.error){
                    currentContainer.html(result.html);
                    $('#category-follow-count-' + result.category_id).html(result.follow_count);
                }
            }
        });
    });

    if (typeof(category) != "undefined" && category !== null) {
        $('.load-more').click(function() {
            $.ajax({
                type: 'GET',
                url: baseURL + '/categories/' + category + '/' + viewType,
                data: {'pageCount': pageCnt},
                success: function(data) {
                    displayPost(data);
                    pageCnt ++;
                    $("img.lazy").lazyload({
                        effect : "fadeIn"
                    });
                }
            });
        });
    }
});

function getEventTarget(e) {
    e = e || window.event;
    return e.target || e.srcElement;
}

function displayPost(data) {
    $('.blog-post').append(data['views']);
    if (typeof showMoreTag !== 'undefined') {
        showMoreTag();
    }
    if(data['hasMore']) {
        $('.load-more').removeClass('hidden');
    } else {
        $('.load-more').addClass('hidden');
    }
}
