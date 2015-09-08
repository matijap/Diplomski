$( document ).ready(function() {
    var clickOrTouchstart = getClickOrTouchstart();

    $(document).on(clickOrTouchstart, '.add-dream-team', function() {
        var pickedSport = $('.add-dream-team-sport').val();
        var teamName    = $('.add-dream-team-name').val();

        if (teamName != '') {
            var htmlToAppend = '<div class="personal-settings-container favorite to-be-removed">' + 
                                    '<h3 class="text-align-center">' + teamName + '</h3>' +
                                    '<i data-closest="personal-settings-container" class="fa fa-times remove-item remove-dream-team"></i>' +
                                    '<div>';
            for(i = 0; i < pickedSport; i++) {
                htmlToAppend += '<input type="text">';
            }
            htmlToAppend += '</div></div>';
            $('.one-personal-settings-section.dream-team').append(htmlToAppend);
        }
    });

    $(document).on('change', '.personal-info-visibility, .posts-visibility', function() {
        var element = $(this);
        var val     = element.val();
        element.closest('.personal-settings-container').find('.privacy-friends-list').hide();
        element.closest('.personal-settings-container').find('.privacy-friends').hide();

        if (val == 'friends_list') {
            element.closest('.personal-settings-container').find('.privacy-friends-list').show();
        }
        if (val == 'specific_friends') {
            element.closest('.personal-settings-container').find('.privacy-friends').show();
        }
    });
});