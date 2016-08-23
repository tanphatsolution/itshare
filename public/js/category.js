$(function(){
    var btnDelete = $('a.action-delete');
    var btnRestore = $('a.action-restore');
    var btnFilter = $('a.action-filter');
    var btnUnfilter = $('a.action-unfilter');

    btnDelete.on('click', function(){
        var categoryId = $(this).data('id');
        var messageConfirm = $(this).data('message');
        if(confirm(messageConfirm)){
            $.ajax ({
                type: 'DELETE',
                url: baseURL + '/categories/' + categoryId,
                success: function(response){
                    if(response.error ===false){
                        swal({
                            title: successLabel,
                            text: response.message,
                            type : 'success'
                        }, function() {
                            document.location.reload(true);
                        });
                    } else{
                        swal(response.message, '', 'error');
                    }
                }
            });
        }
    });

    btnRestore.on('click', function(){
        var categoryId = $(this).data('id');
        var messageConfirm = $(this).data('message');
        if(confirm(messageConfirm)){
            $.ajax ({
                type: 'POST',
                url: baseURL + '/categories/restore',
                data : {'id' : categoryId },
                success: function(response){
                    if(response.error ===false){
                        swal({
                            title: successLabel,
                            text: response.message,
                            type : 'success'
                        }, function() {
                            document.location.reload(true);
                        });
                    } else{
                        swal(response.message, '', 'error');
                    }
                }
            });
        }
    });

    btnFilter.on('click', function() {
        var categoryId = $(this).data('id');
        var messageConfirm = $(this).data('message');
        swal({
            title: confirmLabel,
            text: messageConfirm,
            type: 'warning',
            showCancelButton: true,
            confirmButtonClass: 'btn-confirm',
            confirmButtonText: yesBtn,
            closeOnConfirm: false
        },
        function() {
            $.ajax({
                type: 'POST',
                url: baseURL + '/categoryfilters',
                data: {'category_id': categoryId},
                success: function(response) {
                    swal({
                        title: successLabel,
                        text: response.message,
                        type: 'success',
                        confirmButtonClass: 'btn-success',
                        confirmButtonText: okBtn
                    },
                    function() {
                        window.location.reload(true);
                    });
                },
                error: function(response) {
                    var res = response.responseJSON;
                    swal(errorLabel, res.message, 'error');
                }
            });
        });
    });

    btnUnfilter.on('click', function() {
        var categoryId = $(this).data('id');
        var messageConfirm = $(this).data('message');
        swal({
            title: confirmLabel,
            text: messageConfirm,
            type: 'warning',
            showCancelButton: true,
            confirmButtonClass: 'btn-confirm',
            confirmButtonText: 'Yes, remove it!',
            closeOnConfirm: false
        },
        function() {
            $.ajax({
                type: 'DELETE',
                url: baseURL + '/categoryfilters/' + categoryId,
                success: function(response) {
                    swal({
                        title: successLabel,
                        text: response.message,
                        type: 'success',
                        confirmButtonClass: 'btn-success',
                        confirmButtonText: 'OK'
                    },
                    function() {
                        window.location.reload(true);
                    });
                },
                error: function(response) {
                    var res = response.responseJSON;
                    swal(errorLabel, res.message, 'error');
                }
            });
        });
    });

    $('#category-image').bind('change', function() {
        var mb = 1048576;
        //Max file size, default 2 megabytes
        var maxFileSize = $(this).data('size')/1000;
        var imageSize = this.files[0].size/mb;
        var message = $(this).data('message');
        if(imageSize > maxFileSize) {
            alert(message + maxFileSize + 'MB');
            $(this).val('');
            return false;
        }
    });

    loadMoreCategory();
});

function loadMoreCategory() {
    var seeMoreCategory = $('#see-more-category');
    var pageCount = 1;
    var url = baseURL + '/categories';
    if (typeof(username) != "undefined" && username !== null) {
        url = baseURL + '/u/' + username + '/following-categories';
    }
    seeMoreCategory.click(function() {
        var message = seeMoreCategory.data('message');
        seeMoreCategory.attr('disabled', 'disabled').html(message);
        $.ajax({
            type: 'GET',
            url: url,
            data: {
                'pageCount': pageCount,
            },
            success: function(response) {
                seeMoreCategory.removeAttr('disabled').html(response.msg);
                if (response.hideSeeMore) {
                    seeMoreCategory.addClass('hidden');
                }
                $('.category-container').append(response.html);
                if (response.html.length === 0) {
                    seeMoreCategory.fadeOut("slow");
                }
                pageCount ++;
            }
        });
    });
}
