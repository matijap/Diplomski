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

var quizData    = [];
var onlineUsers = [];

var tempArray   = [];

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

        //online disconect
        // we must find here position of user in array in order to remove it
        var userID = -1;
        for (var i in onlineUsers) {
            if (onlineUsers[i].id == client.id) {
                index  = i;
                userID = onlineUsers[i].userID;
            }
        }
        // if found in array, will have index > -1
        if (index > -1 ) {
            // remove it from array. it will become useless anyhow
            onlineUsers.splice(index, 1);
            //but, do not notify view that something is changed - yet. perhaps user navigated to another page and is still online
            setTimeout(function() {
                //after 5 seconds, look again into onlineUsers array and check if there is user id that matches with
                //id that was removed from array (because new item with userID, but different client.id will be added on refresh)
                index = -1;
                for (var i in onlineUsers) {
                    if (onlineUsers[i].userID == userID) {
                        index  = i;
                    }
                }
                //if we cannot find it after 5 seconds, it means that user is really offline, and notify the view
                if (index < 0) {
                    io.emit('set_online', onlineUsers);
                }
            }, 3000);
        }
    });

    client.on("next_question_get", function(data) {
        io.emit('next_question', data);
    });

    // will trigger it when person loads the page
    client.on("person_browse", function(data) {
        var data = JSON.parse(data);
        var index = -1;
        for (var i in onlineUsers) {
            if (onlineUsers[i].userID == data.userID) {
                index = i;
            }
        }
        
        if (index < 0 ) {
            var obj    = new Object;
            obj.id     = client.id;
            obj.userID = data.userID;
            onlineUsers.push(obj);
            io.emit('set_online', onlineUsers);
        }
    });
});
