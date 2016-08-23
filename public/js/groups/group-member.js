$(function() {
    var renderItem = function(ul, item) {
        return $("<li />")
            .data("item.autocomplete", item)
            .append("<a><img src=" + blankImg + " class='user-avartar' style='background: url(" + item.avatar + ")'/>" + item.value + "</a>")
            .appendTo(ul);
    };

    var autoSuggestion = $('#add-member').autocomplete({

        source: function(request, response) {
            $.ajax({
                type: 'GET',
                url: baseURL + '/groups/getUsersList',
                dataType: 'json',
                data: {
                    username: request.term,
                    groupId : groupId,
                },
                success: function(data) {
                    $('.ui-autocomplete').addClass('ui-autocomplete-suggest-user');
                    response($.map( data, function(item) {
                        return {
                            label: item.label,
                            id: item.id,
                            avatar: item.avatar
                        };
                    }));
                }
            });
        },

        minLength: 3,

        select:function(e, ui) {
            var user = ui;
            $.ajax({
                type: 'POST',
                url: baseURL + '/groups/addMember',
                data: {
                    userId : user.item.id,
                    groupId : groupId,
                    listOnlyFlag: true,
                },
                success: function(response) {
                    $('#add-member').val('');
                    if (response.error) {
                        $('.add-member-notice').text(addFailMsg);
                    } else {
                        if (response.html) {
                            $('.group-list-members').empty().append(response.html);
                        };
                        $('.add-member-notice').text(addSuccessMsg + response.message);
                    }
                },
                error: function() {
                    swal({
                        title: errorLabel,
                        text: errorMsg,
                        type: 'error'
                    });
                }
            });
        }
    });

    if (autoSuggestion.data("ui-autocomplete") != undefined) {
        autoSuggestion.data("ui-autocomplete")._renderItem = renderItem;
    }


    $('.show-group-members').click(function() {
        $.ajax({
            type: 'GET',
            url: baseURL + '/groupUsers',
            data : { 'groupId' : groupId },
            success: function(response) {
                if (response.result) {
                    $('#modal-list-group-users').empty().append(response.modal);
                    $('#modalGroupUsers').modal('show');
                } else {
                    swal({
                        title: errorLabel,
                        text: errorMsg,
                        type: 'error'
                    });
                }
            }
        });
     });

});
