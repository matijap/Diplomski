$( document ).ready(function() {
    var clickOrTouchstart = getClickOrTouchstart();

    $(document).on(clickOrTouchstart, '.show-in-widget-favorite', function(e) {
        var element = $(this);
        element.parent().append('<i class="fa fa-spin fa-spinner"></i>');
        element.toggle();
        appurl      = getAppUrl();
        var data    = element.data();
        var pageID  = data.id;
        $.ajax({
            url: appurl + "/favorites/show-or-hide-in-favorite-pages-widget",
            data: {'pageID': pageID},
            success: function(result) {
                callNotification(result.message, 'success');
                element.parent().find('.fa-spinner').remove();
                element.toggle();
           }
        });    
        
    });

    $(document).on(clickOrTouchstart, '.unfavorite-page', function(e) {
        var element = $(this);
        element.append('<i class="fa fa-spin fa-spinner"></i>');
        element.find('.fa-heartbeat').toggle();
        appurl      = getAppUrl();
        var data    = element.data();
        var pageID  = data.id;
        $.ajax({
            url: appurl + "/favorites/like-or-unlike-page",
            data: {'pageID': pageID},
            success: function(result) {
                callNotification(result.message, 'success');
                element.find('.fa-spinner').remove();
                element.find('.fa-heartbeat').toggle();
                element.closest('tr').remove();
                $('.ex-favourite-pages').find('table').append(result.html)
           }
        });
    });

    $(document).on(clickOrTouchstart, '.favourite-page-back', function(e) {
        var element = $(this);
        element.append('<i class="fa fa-spin fa-spinner"></i>');
        element.find('.fa-heart').toggle();
        appurl      = getAppUrl();
        var data    = element.data();
        var pageID  = data.id;
        $.ajax({
            url: appurl + "/favorites/like-or-unlike-page",
            data: {'pageID': pageID},
            success: function(result) {
                callNotification(result.message, 'success');
                element.find('.fa-spinner').remove();
                element.find('.fa-heart').toggle();
                element.closest('tr').remove();
                $('.current-favourite-pages').find('table').append(result.html);
           }
        });
    });
});