$(function() {

    var pageCount = 1;

    $('#loadMoreGroups').click(function(){
        var follow = $(this).data('value');
        $('#loadMoreGroups').html(loading);
        var urlGroup = follow == 1 ? '/groups/follow' : '/groups';
        $.ajax({
            type: 'GET',
            url: baseURL + urlGroup,
            data: {
                'pageCount': pageCount
            },
            success: function(response) {
                pageCount ++;
                if (response.html != '') {
                    $('#list_groups').append(response.html);
                    $("img.lazy").lazyload({
                        effect : "fadeIn"
                    });
                    if (response.seeMore <= pageCount) {
                        $('#loadMoreGroups').hide();
                    } else {
                        $('#loadMoreGroups').html(loadMore);
                    }
                }
            }
        });
    });

    $('.navbar-menu-groups').click(function(e){
        e.preventDefault();
        window.location.href = baseURL + '/groups';
    });

    var pageCountUserGroup = 1;

    $('#load-more-user-groups').on('click', function() {
        $.ajax({
            type: 'GET',
            url: baseURL + '/groups/userGroups',
            data: {
                'pageCount': pageCountUserGroup,
            },
            success: function(response) {
                pageCountUserGroup++;
                $('.joined-groups').append(response.html);
                if (!response.seeMoreUserGroup) {
                    $('.more-user-groups').hide();
                }
            }
        });
    });

    $('#target').on('mousewheel', function (e) {
        var event = e.originalEvent,
            d = event.wheelDelta || -event.detail;
        this.scrollTop += ( d < 0 ? 1 : -1 ) * 30;
        e.preventDefault();
    });

});

function joinGroup(element)
{
    var elementJoin = $(element);
    var groupId = elementJoin.data('id');
    var joinFlag = elementJoin.data('flag');
    $.ajax({
        type: 'POST',
        url: baseURL + '/groups/join',
        data: {
            'groupId': groupId,
            'joinFlag': joinFlag
        },
        success: function(response) {
            if (!response.error) {
                if (joinFlag == 1) {
                    elementJoin.data('flag', '0');
                } else {
                    elementJoin.data('flag', '1');
                }
                elementJoin.html(elementJoin.data('flag') == 0 ? undoRequest : joinGroupLabel);

                swal(successLabel, response.message, 'success');
            } else {
                swal(errorLabel, response.message, 'error');
            }
        }
    });
}

function listAllUserJoinGroup(element)
{
    var groupId = $(element).data('id');
    $.ajax({
        type: 'GET',
        url: baseURL + '/groupUsers',
        data: { groupId: groupId },
        success: function(response) {
            if (response.result) {
                $('#modal-list-group-users').empty().append(response.modal);
                $('#modalGroupUsers').modal('show');
            } else {
                swal({
                    title: errorLabel,
                    text: errorMsg,
                    type: 'error',
                });
            }
        },
        error: function() {
            swal({
                title: errorLabel,
                text: errorMsg,
                type: 'error',
            });
        }
    });
}
