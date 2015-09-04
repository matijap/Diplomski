var app = require('express')();
var http = require('http').Server(app);
var request = require('request');

//var io = require('socket.io')(http);
var io = require('/usr/local/lib/node_modules/socket.io')(http);

app.get('/', function(req, res){
  res.sendFile(__dirname + '/quiz-game.html');
});
var count = 0;

var socket = http.listen(3000, function(){
  console.log('listening on *:3000');
});

var quizData = [];
io.on("connection", function (client) {
    client.on("quiz_starting", function(data) {
        io.emit('quiz_starting', data);
    });

    client.on("quiz_join", function(data) {
        request('http://local.dip/ajax/playerData.php', function (error, response, body) {
            var parsed = JSON.parse(data);
            body.clientID  = client.id;
            var obj        = new Object;
            obj.quizID     = parsed.quizID;
            obj.playerData = body;
            obj.clientID   = client.id;
            

            var player = new Object;
            player.id      = client.id;
            player.data    = body;
            player.quizID  = parsed.quizID;
            quizData.push(player);
            obj.allPlayers = quizData;
            io.emit('player_join', obj);
        });
    });

    client.on("disconnect", function() {
        io.emit('player_disconnected', client.id);
        var index = -1;
        for (var i in quizData) {
            if (quizData[i].id == client.id) {
                index = i;
            }
        }
        if (index > -1 ) {
            quizData.splice(index, 1);
        }
    });

    client.on("next_question_get", function(data) {
        io.emit('next_question', data);
    });

    client.on("test", function(data) {
        console.log(data);
    }
});
