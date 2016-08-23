socket_io_port = 8090;

var fs = require('fs');
var http = require('http');
var https = require('https');
var ioServer = require('socket.io');
var redis = require('redis');
var io = new ioServer();

function handler(req, res) {
    res.writeHead(200);
    res.end("Welcome to Viblo!");
}

try {
    var options = {
        key: fs.readFileSync('/etc/nginx/certs/viblo.asia.key'),
        cert: fs.readFileSync('/etc/nginx/certs/viblo.asia.crt'),
        ca: fs.readFileSync('/etc/nginx/certs/bundle.crt')
    };

    var httpsServer = https.createServer(options, handler);
    httpsServer.listen(socket_io_port, function(){
        console.log('Viblo SSL listening on port %d', socket_io_port);
    });
    io.attach(httpsServer);
} catch (err) {
    console.log('Error reading file. Can\'t start SSL server.');
    var httpServer = http.createServer(handler);
    httpServer.listen(socket_io_port, function(){
        console.log('Viblo listening on port %d', socket_io_port);
    });
    io.attach(httpServer);
}

function RedisConnection(redis_channel, type) {
    this.redis_conn = redis.createClient();
    this.redis_channel = redis_channel;
}

RedisConnection.prototype.subscribe = function(socket) {
    this.redis_conn.subscribe(this.redis_channel);
    this.redis_conn.on('message', function(channel, message) {
        console.log(message);
        socket.emit('data', message);
    });
};

RedisConnection.prototype.unsubscribe = function() {
    this.redis_conn.unsubscribe(this.redis_channel);
};

RedisConnection.prototype.destroy = function() {
    if (this.redis_conn !== null) {
        this.redis_conn.quit();
    }
};

io.sockets.on('connection', function(socket) {
    var redis_connection;
    socket.on('subscribe', function(redis_channel) {
        redis_connection = new RedisConnection(redis_channel);
        redis_connection.subscribe(socket);
    });
    socket.on('disconnect', function() {
        if (!redis_connection) {
            return;
        }
        redis_connection.unsubscribe();
        redis_connection.destroy();
    });
});
