$(function(){
    $('.datepicker').datepicker({format: 'yyyy-mm-dd'});
    $(".ban_form").submit(function(event) {
        event.preventDefault();
        var $form = $(this),
        data = $form.serialize(),
        url = $form.attr( "action" );
        $.ajax({
            type: 'POST',
            url : url,
            data : data,
            dataType : 'json',
            success: function(result) {
                if (result.success == true) {
                    window.location.assign(result.url);
                } else {
                    var message = '';
                    var id = "#message_error" +  result.id;
                    $(id).addClass("alert alert-danger");
                    for (var key in result.messages) {
                        message += '<p>' + result.messages[key] + '</p>';
                    }
                    $(id).html(message);
                }
            }
        });
    });
});
