$( document ).ready(function() {
    var clickOrTouchstart = getClickOrTouchstart();

    var commentGalleryClass = '.galery-overlay';
    $(document).on(clickOrTouchstart, '.gallery a', function(e) {
        $(commentGalleryClass).show();
        var toClear = setInterval(function() {
            var id = $('.slbCaption').html().trim();
            if (typeof id != undefined) {
                pullCommentsForPicture(id);
                clearInterval(toClear);
            }
        }, 100);
        
    });
    
    $("body").keydown(function(e) {
        if (e.which == 37 || e.which == 39) {
            var id = $('.slbCaption').html().trim();
            pullCommentsForPicture(id);    
        }
    });

    $(document).on(clickOrTouchstart, '.slbCloseBtn', function(e) {
        $(commentGalleryClass).hide();
    });

    $(document).on(clickOrTouchstart, '.slbArrow', function(e) {
        var id = $('.slbCaption').html().trim();
        pullCommentsForPicture(id);
    });

    function pullCommentsForPicture(pictureID) {
        $('.galery-comment-holder').prepend('<i class="fa fa-spinner fa-spin"></i>');
        $('.slbArrow').hide();
        $('.post-comment-list').html('');
        $('.post-comment-textarea').hide();
        $('.submit-comment').hide();
        setTimeout(function() {
            $.ajax({url: "ajax/galeryComments.php", success: function(result){
                $('.post-comment-list').html(result);
                adjustCommentTextWidth();
            }});
            $('.fa-spin').remove();
            $('.slbArrow').show();
            $('.post-comment-textarea').show();
            $('.submit-comment').show();
        }, 300);
    }

    $('.gallery a').simpleLightbox({
        appendTarget: $('.galery-image'),
        closeOnOverlayClick: false,
        nextOnImageClick: false,
    });
});