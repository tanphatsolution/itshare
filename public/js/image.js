$(function(){
    var btnDelete = $('a.action-delete');
    $('.progress .progress-bar').progressbar({display_text: 'fill'});
    btnDelete.on('click', function() {
        var url = $(this).data('url');
        var message = $(this).data('message');
        var title = $(this).data('labels');
        var confirm_delete = $(this).data('delete');
        var success = $(this).data('success');
        var fail = $(this).data('fail');
        var buttonOk = $(this).data('ok');
        var obj = $(this);
        swal({
            title: title,
            text: message,
            type: 'warning',
            showCancelButton: true,
            confirmButtonClass: 'btn-confirm',
            confirmButtonText: confirm_delete,
            closeOnConfirm: true
        },
        function() {
            $.ajax ({
                type: 'POST',
                url: url,
                success: function(response) {
                    $.notify({
                        title: success,
                        message: response.message
                    });
                    var tr = obj.closest('tr');
                    tr.css("background-color", "#FF3700");
                    tr.fadeOut(400, function () {
                        tr.remove();
                    });
                    return false;
                },
                error: function(response) {
                    var res = response.responseJSON;
                    swal(fail, res.message, 'error');
                }
            });
        });
    });

    $('.panel-body img').each(function() {
        $(this).fancybox({
            href : $(this).attr('src')
       });
    });
});