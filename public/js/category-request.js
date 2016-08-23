$(function(){
    var btnDelete = $('a.action-delete');
    var btnRestore = $('a.action-restore');
    var btnAccept = $('a.action-accept');
    var btnRejected = $('a.action-reject');

    btnDelete.on('click', function() {
        var categoryId = $(this).data('id');
        var messageConfirm = $(this).data('message');
        if (confirm(messageConfirm)) {
            $.ajax ({
                type: 'DELETE',
                url: baseURL + '/categoryrequests/' + categoryId,
                success: function(response) {
                    if(response.error === false) {
                        swal({
                            title: successLabel,
                            text: response.message,
                            type : 'success'
                        }, function() {
                            document.location.reload(true);
                        });
                    } else {
                        swal(response.message, '', 'error');
                    }
                }
            });
        }
    });

    btnRestore.on('click', function() {
        var categoryId = $(this).data('id');
        var messageConfirm = $(this).data('message');
        if (confirm(messageConfirm)) {
            $.ajax ({
                type: 'POST',
                url: baseURL + '/categoryrequests/restore',
                data : { 'id' : categoryId },
                success: function(response) {
                    if (response.error === false){
                        swal({
                            title: successLabel,
                            text: response.message,
                            type : 'success'
                        }, function() {
                            document.location.reload(true);
                        });
                    } else {
                        swal(response.message, '', 'error');
                    }
                }
            });
        }
    });

    btnAccept.on('click', function() {
        var categoryId = $(this).data('id');
        var messageConfirm = $(this).data('message');
        if (confirm(messageConfirm)) {
            $.ajax ({
                type: 'POST',
                url: baseURL + '/categoryrequests/accept',
                data : { 'id' : categoryId },
                success: function(response) {
                    if (response.error === false) {
                        swal({
                            title: successLabel,
                            text: response.message,
                            type : 'success'
                        }, function() {
                            document.location.reload(true);
                        });
                    } else {
                        swal(response.message, '', 'error');
                    }
                }
            });
        }
    });

    btnRejected.on('click', function() {
        var categoryId = $(this).data('id');
        var messageConfirm = $(this).data('message');
        if (confirm(messageConfirm)) {
            $.ajax ({
                type: 'POST',
                url: baseURL + '/categoryrequests/reject',
                data : { 'id' : categoryId },
                success: function(response) {
                    if (response.error === false) {
                        swal({
                            title: successLabel,
                            text: response.message,
                            type : 'success'
                        }, function() {
                            document.location.reload(true);
                        });
                    } else {
                        swal(response.message, '', 'error');
                    }
                }
            });
        }
    });
});