$(document).ready(function() {

    // Action CLICK_TO_MY_CLIPS
    $('.menu-list a.clip').on('click', function() {
        sendDataRequest('CLICK_TO_MY_CLIPS');
    });

    //Action click CLICK_TO_THEME_OF_MONTH
    $('a.theme-of-month').on('click', function() {
        sendDataRequest('CLICK_TO_THEME_OF_MONTH');
    });

    //Action click CLICK_TO_POST_DETAIL
    $('a.name-title').on('click', function() {
        sendDataRequest('CLICK_TO_POST_DETAIL');
    });

    //Action click CLICK_TO_TAG
    $('a.tag-name').on('click', function() {
        sendDataRequest('CLICK_TO_TAG');
    });

    //Action click CLICK_TO_POST_ARTICLE
    $('a.create-a-post').on('click', function() {
        sendDataRequest('CLICK_TO_POST_ARTICLE');
    });

    //Action click CLICK_TO_AUTHOR_ARTICLE
    $('a.author-link-to-profile').on('click', function() {
        sendDataRequest('CLICK_TO_AUTHOR_ARTICLE');
    });

    //Action click CLICK_TO_ALL_GROUP
    $('a.to-all-group').on('click', function() {
        sendDataRequest('CLICK_TO_ALL_GROUP');
    });

    //Action click CLICK_TO_ONE_GROUP
    $('a.to-one-group').on('click', function() {
        sendDataRequest('CLICK_TO_ONE_GROUP');
    });

    //Action click share facebook
    $('a.face-share').on('click', function() {
        sendDataRequest('CLICK_SHARE_FACEBOOK');
    });

    //Action click share google+
    $('a.google-share').on('click', function() {
        sendDataRequest('CLICK_SHARE_GOOGLE');
    });

    //Action click POPULAR_POSTS
    $('a.popular_posts_detail').on('click', function() {
        var position_popular = parseInt($( "a.popular_posts_detail" ).index(this));
        position_popular +=1;
        sendDataPopularRelated('POPULAR_POSTS', position_popular);
    });

    //Action click RELATED_POSTS
    $('a.related_posts_detail').on('click', function() {
        var position = parseInt($(this).attr('number'));
        sendDataPopularRelated('RELATED_POSTS', position);
    });
    
});

function sendDataRequest(actionCode) {
    var screenCode = $('body').attr('screen');
    if (screenCode) {
        $.ajax({
            type: 'POST',
            url: baseURL + '/writelog',
            data: {
                'screen_code' : screenCode,
                'action_code' : actionCode
            }
        });
    }
}

function sendDataPopularRelated(actionCode, positionClick) {
    var screenCode = $('body').attr('screen');
    if (screenCode) {
        $.ajax({
            type: 'POST',
            url: baseURL + '/writelog',
            data: {
                'screen_code' : screenCode,
                'action_code' : actionCode,
                'position' : positionClick
            }
        });
    }
}
