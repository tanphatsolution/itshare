$(function(){
    setDisplay();
    var STOCK_AJAX_CALL = false;
    $('body').on('click','.btn-favorite', function() {
        if (STOCK_AJAX_CALL) return;
        $('.btn-favorite').attr('disabled','disabled');
        var hasStock = $(this).hasClass('btn-stock');
        STOCK_AJAX_CALL = true;

        if (hasStock) {
            $.ajax({
                type: 'POST',
                url: baseURL + '/clip',
                data: {'postId': post_id},
                success: function(response) {
                    $('.become').addClass('hidden');
                    setDisplay();
                    $('.stockUsers').prepend(response[1]);
                    $('.btn-favorite').removeAttr('disabled');
                },
                complete: function() {
                    STOCK_AJAX_CALL = false;
                }
            });

            //Action CLICK_ADD_TO_CLIP
            sendDataRequest('CLICK_ADD_TO_CLIP');

        } else {
            $.ajax({
                type: 'DELETE',
                url: baseURL + '/clip/' + post_id,
                success: function() {
                    setDisplay();
                    $('.user_' + user_id).remove();
                    $('.btn-favorite').removeAttr('disabled');
                },
                complete: function() {
                    STOCK_AJAX_CALL = false;
                }
            });

            //Action CLICK_ADD_TO_CLIP
            sendDataRequest('CLICK_REMOVE_TO_CLIP');
        }
    });
});

function setDisplay() {
    $.ajax({
        type: 'GET',
        url: baseURL + '/clip/count',
        data: {'postId': post_id},
        dataType: 'json',
        success: function(data) {
            var userStockedCount = data[0];
            var totalCount = parseInt(data[1]);
            if (userStockedCount == 0) {
                $('.btn-with-text').text(clipThisPost);
                $('.become').removeClass('hidden');
                $('.btn-favorite').addClass('btn-stock');
                $('.btn-clip').addClass('clip');
                $('.btn-favorite').removeClass('btn-unstock');
                $('.btn-clip').removeClass('remove-clip');
            } else {
                $('.btn-with-text').text(removeClipped);
                $('.become').addClass('hidden');
                $('.btn-favorite').removeClass('btn-stock');
                $('.btn-clip').removeClass('clip');
                $('.btn-favorite').addClass('btn-unstock');
                $('.btn-clip').addClass('remove-clip');
            }

            var UserStockDisplay = $('.stockUsers li.user-favorite-list').length;
            var otherUser = totalCount - UserStockDisplay;
            $('#storedCount').text('+' + otherUser);
            $('#stockedCount').text(totalCount);
            if (totalCount > 0) {
                $('.stockedBy').removeClass('hidden');
                $('.firstStock').addClass('hidden');
            } else {
                $('.firstStock').removeClass('hidden');
                $('.stockedBy').addClass('hidden');
            }

        }
    });
}