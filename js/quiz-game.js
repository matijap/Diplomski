$( document ).ready(function() {
    var clickOrTouchstart = getClickOrTouchstart();
    
    var playerID = getClientID();
    var quizID   = $('.quizID').val();
    var socket   = io.connect("localhost:3000");

    var d = new Object;
    d.playerID = playerID;
    d.quizID   = quizID;;
    var data   = JSON.stringify(d);
    
    socket.emit('quiz_join', data);

    socket.on('quiz_starting', function(data) {
        if (data.quiz_id == quizID) {
            $('.quiz-start-date').hide();
            $("#hms_timer").countdowntimer({
                seconds : 5,
                timeUp : timeisUp
            });
        }
    });

    socket.on('next_question', function(data) {
        if (data.quizID == quizID) {
            $('.quiz-loader').hide();
            $('.lead-table').html(data.leaderboard);
            $('.one-quiz-question').html(data.question);
            $('.one-quiz-question').show();
            $('.quiz-submit').show();
            $('#countdowntimer').show();
            $("#hms_timer").countdowntimer({
                seconds : data.seconds,
                timeUp : timeisUp
            });
        }
    });

    function timeisUp() {
        $('.quiz-loader').show();
        $('#countdowntimer').hide();
        $('.quiz-submit').hide();
        $('.one-quiz-question').hide();
    }

    socket.on('player_join', function(data) {
        if (data.quizID == quizID) {
            var parsed = $.parseJSON(data.playerData);
            $('.quiz-joined-players').parent().append('<p data-node-client-id="' + data.clientID + '">' + parsed.first_name + ' ' + parsed.last_name + '</p>');

            $.each(data.allPlayers, function( index, value ) {
                if (!doesExists( "p[data-node-client-id='" + value.id + "']")) {
                    var parsed = $.parseJSON(value.data);
                    $('.quiz-joined-players').parent().append('<p data-node-client-id="' + value.id + '">' + parsed.first_name + ' ' + parsed.last_name + '</p>');
                }
            });
        }
    });

    socket.on('player_disconnected', function(data) {
        $( "p[data-node-client-id='" + data + "']" ).remove();
    });

    $(document).on(clickOrTouchstart, '.quiz-submit button', function(e) {
        e.preventDefault();
    }); 
});