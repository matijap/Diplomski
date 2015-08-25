$( document ).ready(function() {
    var clickOrTouchstart = getClickOrTouchstart();

    $(document).on(clickOrTouchstart, '.add-new-item', function(e) {

        var data = $(this).data();
        var html = $('.' + data.htmlTemplate).html();
        var closest = data.closest;

        $(this).closest('.' + closest).find('.append-into').append(html);
        $(this).closest('.' + closest).find('.fa-times').show();
    });

    $(document).on(clickOrTouchstart, '.remove-item', function(e) {
        var data    = $(this).data();
        var closest = data.closest;
        if ($(this).closest('.' + closest).find('.to-be-removed').size() == 2) {
            $(this).closest('.' + closest).find('.remove-item').hide();
        }
        $(this).closest('.to-be-removed').remove();
    });
});