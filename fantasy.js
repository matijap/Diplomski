$( document ).ready(function() {
    $(document).on('change', '.fantasy-formation-change', function(e) {
        var formation      = $(this).val();

        var pitchWidth     = 400;
        var playerWidth    = 65;

        var strikersTop    = 130;
        var midfieldersTop = 270;
        var defendersTop   = 450;
        var goalkeeperTop  = 550;

        var currentEQ = 1;
        for (var i = 0, len = formation.length; i < len; i++) {

            var playersTotalWidth                = formation[i] * playerWidth;
            var remainingWidth                   = pitchWidth - playersTotalWidth;
            var availableRemainingSpacePerPlayer = remainingWidth / formation[i];

            var tempLeft = availableRemainingSpacePerPlayer / 2;
            for (var j = 0, l = formation[i]; j < formation[i]; j++) {
                var tempTop            = 0;
                var shouldBeInPosition = '';
                var currentPlayer      = $('.one-fantasy-pitch-player').eq(currentEQ);

                switch(i) {
                    case 0:
                        tempTop            = defendersTop;
                        shouldBeInPosition = 'defender';
                        break;
                    case 1:
                        tempTop            = midfieldersTop;
                        shouldBeInPosition = 'midfielder';
                        break;
                    case 2:
                        tempTop            = strikersTop;
                        shouldBeInPosition = 'striker';
                        break;
                }
                var currentPlayerData = currentPlayer.data();
                if (currentPlayerData.playerPosition != shouldBeInPosition) {
                    currentPlayer.addClass('fantasy-invalid-position');
                } else {
                    currentPlayer.removeClass('fantasy-invalid-position');
                }
                currentPlayer.attr('data-position', shouldBeInPosition);
                currentPlayer.data('position', shouldBeInPosition);
                
                // $('.one-fantasy-pitch-player').eq(currentEQ).css('top', tempTop + 'px');
                // $('.one-fantasy-pitch-player').eq(currentEQ).css('left', tempLeft + 'px');
                currentPlayer.animate({
                    'top': tempTop + 'px',
                    'left':  tempLeft + 'px'
                }, 200, function() {
                });
                currentEQ++;
                tempLeft += availableRemainingSpacePerPlayer + playerWidth;
            }
        }
        // $('.one-fantasy-pitch-player').eq(0).css('top', goalkeeperTop + 'px');
        // $('.one-fantasy-pitch-player').eq(0).css('left', '165px');
        $('.one-fantasy-pitch-player').eq(0).animate({
            'top': goalkeeperTop + 'px',
            'left': '165px'
        }, 200, function() {
        });
    });
    $('.fantasy-formation-change').trigger('change');

    $(document).on('change', '.fantasy-preffered-squad', function(e) {
        $('.pitch-container').prepend('<div class="pitch-overlay"><i class="fa-spinner fa-spin fa"></i></div>');
        var val = $(this).val()
        setTimeout(function() {

            formation = val;

            gk       = [];
            gk.push('Goalkeeper');

            var defs = [];
            var mids = [];
            var sts  = [];
            for (var i = 0, len = val.length; i < len; i++) {
                for (var j = 0, l = val[i]; j < formation[i]; j++) {
                    switch(i) {
                        case 0:
                            defs.push('Defender ' + (j + 1));
                            break;
                        case 1:
                            mids.push('Midfielder ' + (j + 1));
                            break;
                        case 2:
                            sts.push('Striker ' + (j + 1));
                            break;
                    }
                }
            }
            var reserves        = new Object;

            var reservesGK      = new Object;
            reservesGK[0]       = 'Reserve Goalkeeper';
            reserves.goalkeeper = reservesGK;

            var reservesDF      = new Object;
            reservesDF[0]       = 'Reserve Defender';
            reserves.defender = reservesDF;

            var reservesMD      = new Object;
            reservesMD[0]       = 'Reserve Midfielder';
            reserves.midfielder = reservesMD;

            var reservesST      = new Object;
            reservesST[0]       = 'Reserve Striker';
            reserves.striker = reservesST;
            

            var all        = new Object;
            all.goalkeeper = gk;
            all.defender   = defs;
            all.midfielder = mids;
            all.striker    = sts;
            all.reserves   = reserves;
            all.formation  = formation;

            var jsoned    = JSON.stringify(all);
            var converted = $.parseJSON(jsoned);

            $('.fantasy-formation-change').val(converted.formation);

            $('.one-fantasy-pitch-player').remove();

            $.each( converted, function( position, players ) {
                if (position != 'formation' && position != 'reserves') {
                    $.each( players, function( index, onePlayer ) {
                        $('.pitch-container').append('<div class="position-absolute one-fantasy-pitch-player" style="top: 450px; left: 20px;" data-player-position="' + position + '" data-position="' + position + '" ><img class="display-block" style="margin: 0 auto;" src="shirt.png" /><p class="m-t-5 text-align-center">' + onePlayer + '</p></div>');
                    });
                }
            });

            $('.fantasy-picked-player-list').each(function() {
                $(this).find('p').remove();
            });

            $.each( converted.reserves, function( position, players ) {
                $.each( players, function( index, onePlayer ) {
                    $( ".fantasy-picked-player-list[data-position='" + position + "']" ).append('<p><span>' + onePlayer + '</span><i class="fa fa-times float-right"></i></p>');
                });
            });
            
            
            $('.fantasy-formation-change').trigger('change');

            $('.pitch-container').find('.pitch-overlay').remove();
            showFantasyCancel();
        }, 200);
    });
    
    $(document).on(clickOrTouchstart, '.one-fantasy-pitch-player', function(e) {
        var data = $(this).data();
    
        //if we want to unselect
        if ($(this).hasClass('one-fantasy-pitch-player-selected')) {
            $('.one-fantasy-pitch-player').removeClass('one-fantasy-pitch-player-selected');  
            $('.fantasy-picked-player-list').each(function() {
                $(this).show();
            }); 
            return false;         
        }

        $('.one-fantasy-pitch-player').removeClass('one-fantasy-pitch-player-selected');

        $(this).addClass('one-fantasy-pitch-player-selected');

        //make sure to show them all before hiding
        $('.fantasy-picked-player-list').each(function() {
            $(this).show();
        });

        $('.fantasy-picked-player-list').each(function() {
            var playerData = $(this).data();
            if (playerData.position != data.position) {
                $(this).hide();
            }
        });
        showFantasyCancel();
    });

    $(document).on(clickOrTouchstart, '.fantasy-cancel', function(e) {
        // $('.one-fantasy-pitch-player').removeClass('one-fantasy-pitch-player-selected');
        // $('.fantasy-picked-player-list').each(function() {
        //     $(this).show();
        // });
        // $(this).hide();
        location.reload();
    });

    $(document).on(clickOrTouchstart, '.fantasy-picked-player-list p', function(e) {
        if (!doesExists('.one-fantasy-pitch-player-selected')) {
            return;
        }
        var newPlayer = $(this).find('span').text();
        var oldPlayer = $('.one-fantasy-pitch-player-selected').find('p').text();
        $('.one-fantasy-pitch-player-selected').find('p').text(newPlayer);
        $(this).find('span').text(oldPlayer);
        $('.one-fantasy-pitch-player').removeClass('one-fantasy-pitch-player-selected');
        $('.one-fantasy-pitch-player').removeClass('fantasy-invalid-position');
        $('.fantasy-picked-player-list').each(function() {
            $(this).show();
        });
        showFantasyCancel();
    });

    $(document).on(clickOrTouchstart, '.fantasy-buy', function(e) {
        var button = $(this);
        var loader = button.find('.fa');
        if (loader.is(':visible')) {
            return false;
        }
        
        button.find('span').hide();
        loader.show();
        
        setTimeout(function() {

            // Just for HTML demo. To be removed.
            var response = new Object;
            var rand = getRandomNumber(1,10);

            if (rand < 5) {

                response.status  = 0;
                var rand = getRandomNumber(1,3);
                switch(rand) {
                    case 1:
                        response.message = 'You do not have enough funds for this transfer.';
                        break;
                    case 2:
                        response.message = 'Your team already has 15 players.';
                        break;
                    case 3:
                        response.message = 'You can have up to 5 defenders.';
                        break;
                }
            } else {
                response.status  = 1;
                response.message = 'Player bought successfully.';
                var player       = new Object();
                player.name      = 'New Player';
                player.value     = '10.000.000';
                player.position  = 'defender';
                response.player  = player;
            }
            responseJsoned     = JSON.stringify(response);
            // demo purpose end

            var parsedResponse = $.parseJSON(responseJsoned);
            
            callNotification(parsedResponse.message, parsedResponse.status == 0 ? 'error' : 'success');

            if (parsedResponse.status) {
                $('.fantasy-picked-player-list[data-position="' + parsedResponse.player.position + '"]').append('<p class="m-b-5"><span>' + parsedResponse.player.name + '</span><i class="fa fa-times float-right"></i></p>')
                $('.remaining-budget').text('90,000,000');
            }

            button.find('span').show();
            loader.hide();
        }, 1000);
    });

    $(document).on(clickOrTouchstart, '.fantasy-picked-player-list .fa-times', function(e) {
        var button = $(this);
        button.hide();
        button.closest('p').append('<i class="fa fa-spinner fa-spin float-right">')
        setTimeout(function() {
            button.closest('.p').remove();
            // Just for HTML demo. To be removed.
            var response     = new Object;
            response.status  = 1;
            response.message = 'Player sold successfully';
            responseJsoned   = JSON.stringify(response);
            // demo purpose end
            
            var parsedResponse = $.parseJSON(responseJsoned);
            
            callNotification(parsedResponse.message, parsedResponse.status == 0 ? 'error' : 'success');

            if (parsedResponse.status) {
                button.closest('p').remove();
            } else {
                button.show();
                button.closest('p').find('.fa-spinner').remove();
            }
        }, 1000);
    });
});