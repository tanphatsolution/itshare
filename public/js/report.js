function showDeletedMessage(message) {
    swal(message);
}

function loadDeleteReport() {
    var btnDelete = $('.action-delete');
    btnDelete.on('click', function() {
        var self = $(this);
        var reportId = self.data('id');
        var messageConfirm = self.data('message');
        var yesLabel = self.data('yes');
        var noLabel = self.data('no');
        swal({
            title: messageConfirm,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: yesLabel,
            cancelButtonText: noLabel,
            closeOnConfirm: false
        }, function(isConfirm) {
            if (isConfirm) {
                $.ajax({
                    type: 'DELETE',
                    url: baseURL + '/reports/' + reportId,
                    success: function(response) {
                        if (response.success) {
                            swal(response.message, '', 'success');
                            self.closest('tr').remove();
                        } else {
                            swal(response.message, '', 'error');
                        }
                    }
                });
            }
        });
    });
}

function filterReport(ctr) {
    $.ajax({
        type: 'GET',
        url: filterUrl + $(ctr).val(),
        success: function(data) {
            if (data.hasItem) {
                $('#reportContainer').removeClass('alert');
                $('#reportContainer').removeClass('alert-warning');
                $('#reportContainer').addClass('table-responsive');
            } else {
                $('#reportContainer').removeClass('table-responsive');
                $('#reportContainer').addClass('alert');
                $('#reportContainer').addClass('alert-warning');
            }
            $('#reportContainer').html(data.html);
            loadDeleteReport();
        },
        error: function(xhr) {
            console.log(xhr.message);
        }
    });
}

$(function() {
    loadDeleteReport();

    var selectAll = $('#select-all');
    selectAll.click(function(event) {
        var checkboxes = $(this).closest('form').find(':checkbox');
        checkboxes.each(function(index) {
            attr = $(this).attr('checked');
            if (typeof attr !== 'undefined' && attr !== false) {
                $(this).removeAttr('checked');
            } else {
                $(this).attr('checked', 'checked');
                $(this).prop('checked', true);
            }
        });
    });

    var filter = $('#filter');
    filter.change(function() {
        filterReport(this);
    });

    var batch = $('#batch');
    batch.change(function() {
        $.ajax({
            type: 'POST',
            url: baseURL + '/process',
            data: $('#report-form').serialize(),
            success: function(response) {
                filterReport($('#filter'));
                if (response.success) {
                    swal(response.message, '', 'success');
                } else {
                    swal(response.message, '', 'error');
                }
            }
        });
    });
});
