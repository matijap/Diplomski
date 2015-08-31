$( document ).ready(function() {
    var clickOrTouchstart = getClickOrTouchstart();

    $(document).on(clickOrTouchstart, '.show-in-widget-favorite', function(e) {
        var element = $(this);
        element.parent().append('<i class="fa fa-spin fa-spinner"></i>');
        element.toggle();
        setTimeout(function() {
            callNotification('Page will no longer appear in favourite pages widget.', 'success');
            element.parent().find('.fa-spinner').remove();
            element.toggle();
        }, 300);
    });

    $(document).on(clickOrTouchstart, '.unfavorite-page', function(e) {
        var element = $(this);
        element.append('<i class="fa fa-spin fa-spinner"></i>');
        element.find('.fa-heartbeat').toggle();
        setTimeout(function() {
            callNotification('Page unfavourited successfully.', 'success');
            element.find('.fa-spinner').remove();
            element.find('.fa-heartbeat').toggle();
            element.closest('tr').remove();
            $('.ex-favourite-pages').find('table').append('<tr><td><a href="page.html">Some Page</a></td><td><a class="blue-button" href="#"><i class="fa fa-heart"></i></a></td></tr>')
        }, 300);
    });

    $(document).on(clickOrTouchstart, '.favourite-page-back', function(e) {
        var element = $(this);
        element.append('<i class="fa fa-spin fa-spinner"></i>');
        element.find('.fa-heart').toggle();
        setTimeout(function() {
            callNotification('Page favourited successfully.', 'success');
            element.find('.fa-spinner').remove();
            element.find('.fa-heart').toggle();
            element.closest('tr').remove();
            $('.current-favourite-pages').find('table').append('<tr>' +
                                                                '<td><a href="page.html">BC Red Star Belgrade</td>' +
                                                                '<td><a class="blue-button" href="javascript:void(0)"><i class="fa fa-cog"></i></a></td>' +
                                                                '<td><a class="blue-button unfavorite-page" href="javascript:void(0)"><i class="fa fa-heartbeat"></i></a></td>' +
                                                                '<td><input class="show-in-widget-favorite cursor-pointer" type="checkbox"></td>' +
                                                                '</tr>');
        }, 300);
    });
});