function SocketClient(channel) {
    var socket = io(baseURL + ':' + socketIoPort);

    socket.on('connect', function() {
        socket.emit('subscribe', channel);
    });

    socket.on('data', function(msg){
        var data = $.parseJSON(msg);
        if (typeof data.view !== 'undefined') {
            if (data.notifications_count > 0) {
                updateNotificationBadge(data.notifications_count);
                $('title').html('(' + data.notifications_count + ')' + title);
            }
            $.notify(
                {
                    icon: 'fa fa-bell-o',
                    message: ' ' + data.view,
                    url: data.url
                },
                {
                    delay: 10000,
                    url_target: '_self'
                }
            );
        }
    });

    socket.on('disconnect', function() {
    });
}

$(function() {
   if (typeof userRedisChannel !== 'undefined') {
       var client = new SocketClient(userRedisChannel);
   }
});