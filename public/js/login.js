$(function(){
    $("#viblo-login-form").submit(function(event) {
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
                    $("#alert-message").hide();
                    $("#message_error").addClass("alert alert-danger");
                    for (var key in result.messages) {
                        message += '<p>' + result.messages[key] + '</p>';
                    }
                    $("#message_error").html(message);
                }
            }
        });
    });

    $("#viblo-signup-form").submit(function(event) {
        event.preventDefault();
        var $form = $ (this),
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
                    $("#signup_message_error").addClass("alert alert-danger");
                    for (var key in result.messages) {
                        message += '<p>' + result.messages[key] + '</p>';
                    }
                    $("#signup_message_error").html(message);
                }
            }
        });
    });

    $(function(){
        $('#signup-submit-btn').attr("disabled", true);
        $('#tos-check').change(function() {
            if($(this).is(":checked")) {
                $('#signup-submit-btn').attr("disabled", false);
            } else {
                $('#signup-submit-btn').attr("disabled", true);
            }
        });
    });
});
