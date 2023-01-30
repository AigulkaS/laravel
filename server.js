const http = require('http').Server();
// const io = require('socket.io')(http);
const io = require('socket.io')(http, {
    cors: {
        origins: ['http://127.0.0.1:8000']
    }
});

const Redis = require('ioredis');

const redis = new Redis();
redis.subscribe('todays-update');
redis.subscribe('bookings-update');
redis.subscribe('bookings-store');

redis.on('message', function (channel, message) {
    console.log('Message recieved:'+ message);
    console.log('Channel:'+ channel);
    message = JSON.parse(message);
    io.emit(channel + ':' + message.event, message.data)
});

http.listen(3000, function () {
    console.log('listening on Port: 3000');
});
