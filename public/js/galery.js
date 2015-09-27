$( document ).ready(function() {
    var clickOrTouchstart = getClickOrTouchstart();

    var commentGalleryClass = '.galery-overlay';
     
    $('.gallery.album a').simpleLightbox({
        appendTarget: $('.galery-image'),
        closeOnOverlayClick: false,
        nextOnImageClick: false,
    });

    
    $(document).on(clickOrTouchstart, '.gallery.album a', function(e) {
        $(commentGalleryClass).show();
        var toClear = setInterval(function() {
            var id = $('.slbCaption').html().trim();
            if (typeof id != undefined) {
                pullCommentsForPicture(id);
                clearInterval(toClear);
                $(commentGalleryClass).find('form').find('#commented_image_id').val(id);
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
        appurl = getAppUrl();
        $.ajax({
            url: appurl + "/galery/get-image-comments",
            data: {'imageID': pictureID },
            success: function(result) {
                $('.post-comment-list').html(result);
                $('.post-comment-list').append('<div class="show-more-post-comments">' + 
                                                '<i class="fa fa-toggle-down"></i>' +
                                                '</div>')
                adjustCommentTextWidth();
                $('.fa-spin').remove();
                $('.slbArrow').show();
                $('.post-comment-textarea').show();
                $('.submit-comment').show();
            }
        });
    }

    $('#fileupload').fileupload({
        dataType: 'json',
        done: function (e, data) {
            $('.choose-upload').hide();
            $.each(data.result.files, function (index, file) {
                $('<p class="m-t-10" />').text(file.name).appendTo('.galery-upload');
            });
            setTimeout(function() {
                location.reload();
            }, 700);
        },
        progressall: function (e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#progress .bar').css(
                'width',
                progress + '%'
            );
        }
    });

    $(document).on(clickOrTouchstart, '.remove-galery-item .fa', function(e) {
        e.stopPropagation();
    });
    
});