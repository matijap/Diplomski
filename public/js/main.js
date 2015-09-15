$( document ).ready(function() {
    var windowWidth       = getScreenWidth();
    var clickOrTouchstart = getClickOrTouchstart();
    var appurl            = getAppUrl();

    applyResizeFunctions();
    $( window ).resize(function() {
        applyResizeFunctions();
    });

    initializeWidgetSortable();
    initUploadButtonsChange();
    addIDAndInitializeSortable();
    addRequiredToPostCommentsTextArea();

    var socket = getIOConnection();

    var format = $('.datePickerFormat').val();
    $('input.datepicker').Zebra_DatePicker(
        {format: format}
    );
    $('select').select2();

    var printNotificationMessage = doesExists('.notification-status');
    if (printNotificationMessage) {
        var status  = $('.notification-status').val();
        var message = $('.notification-message').val();
        callNotification(message, status);
    }


    $(document).on('focus', '.post-comment-textarea', function() {
        $(this).css('height', '100px');
    });

    $(document).on('blur', '.post-comment-textarea', function() {
        $(this).css('height', '25px');
    });

    $(document).on(clickOrTouchstart, '.sublink-li-2', function(e) {
        e.stopPropagation();
        var href        = $(this).find('a').attr('href');
        window.location = href;
    });

    $(document).on(clickOrTouchstart, '.sublink-li-1', function() {
        var href = $(this).find('a').attr('href');
        window.location = href;
    });

    $(document).on(clickOrTouchstart, '.show-more-post-comments', function() {
        var element      = $(this);
        var loadMoreIcon = element.find('.fa-toggle-down');
        var spinner      = element.find('.fa-spinner');
        loadMoreIcon.addClass('display-none');
        spinner.removeClass('display-none');
        var commentID    = element.parent().find('.one-post-comment').last().find('.one-post-actions-holder').attr('id');
        $.ajax({
            url: appurl + '/comment/load-more-comments',
            data: {'commentID': commentID},
            success: function(result) {
                loadMoreIcon.removeClass('display-none');
                spinner.addClass('display-none');
                element.closest('.post-comment-list').find('.one-post-comment').last().after(result);
            }
        });
    });

    $(document).on(clickOrTouchstart, 'html', function(event) {
        var element =  $(event.target);
        if (!element.hasClass('.post-header-close')) {
            $('.post-header-close').find('.post-actions').hide();
        }
    });

    $(document).on(clickOrTouchstart, '.post-header-close', function(event) {
        var element = $(this);
        element.find('.post-actions').toggle();
        event.stopPropagation();
    });

    $(document).on(clickOrTouchstart, '.modal-header-close, .cls', function(event) {
        $( ".modal.fade" ).fadeTo( "fast" , 0, function() {
            $( ".modal.fade" ).toggle();
            $('.mcl').toggle();
            $('.modal-body .mc').html('');
        });
    });

    $(document).on(clickOrTouchstart, '.modal-open', function(event) {
        var element = $(this);
        $( ".modal.fade" ).toggle();
        $( ".modal.fade" ).fadeTo( "fast" , 1, function() {});
        $('.mcl').toggle();
        var appurl = getAppUrl();
        var data   = element.data();
        $.ajax({
            url: appurl + data.url,
            data: {},
            success: function(result) {
                $('.modal-body .mc').append(result);
                var title = $('#modal_title').val();
                $('.modal-header-title p').text(title);
                applyInitFunctions();
            }
        });
    });

    $('.search-person-chat').on('input', function() {
        var element    = $(this);
        var val        = element.val();
        var chatParent = element.parent();
        if (val != '') {
            chatParent.find('.fa-search').hide();
            chatParent.find('.fa-times').show();
        }
        $('.chat-person-name').each(function() {
            var nameElement = $(this);
            var parent      = nameElement.closest('.one-chat-person');
            var person      = nameElement.text().trim();
            var match       = person.match(new RegExp(val, 'gi'));
            if (match) {
                parent.show();
            } else {
                parent.hide();
            }
        });
    });

    $(document).on(clickOrTouchstart, '.chat-search .fa-times', function(event) {
        $('.search-person-chat').val('');
        $('.search-person-chat').trigger('input');
        var chatParent = $(this).parent();
        chatParent.find('.fa-search').toggle();
        chatParent.find('.fa-times').toggle();
    });

    $(document).on('change', '#post_type', function() {
        var val = $(this).val();
        $('#image_url').closest('.modal-element').hide();
        $('#image_upload').closest('.modal-element').hide();
        $('#video').closest('.modal-element').hide();
        if (val == 'IMAGE') {
            $('#image_url').closest('.modal-element').show();
            $('#image_upload').closest('.modal-element').show();
        }
        if (val == 'VIDEO') {
            $('#video').closest('.modal-element').show();
        }
    });

    $(document).on(clickOrTouchstart, '.send-friend-request', function() {
        var element = $(this);
        element.hide();
        toggleBetweenClasses('#about .fa-spinner', 'display-none', 'display-block');
        setTimeout(function() {
            toggleBetweenClasses('#about .fa-spinner', 'display-none', 'display-block');
            element.html('Sent<i class="fa fa-check-circle"></i>');
            element.show();
        }, 1000);
    });

    $(document).on(clickOrTouchstart, '.choose-upload', function(e) {
        var data = $(this).data();
        $('#' + data.trigger).trigger('click');
    });

    $(document).on(clickOrTouchstart, '.dream-team-sport .fa', function(e) {
        $(this).closest('.dream-team-display').find('.dream-team-display-team').toggle();
        $(this).closest('.dream-team-display').find('.fa').toggle();
    });

    $(document).on(clickOrTouchstart, '.dream-team-sport p', function(e) {
        $(this).closest('.dream-team-display').find('.dream-team-display-team').toggle();
        $(this).closest('.dream-team-display').find('.fa').toggle();
    });

    $(document).on(clickOrTouchstart, '.add-new-item', function(e) {
        e.preventDefault();
        var data    = $(this).data();
        var html    = $('.' + data.htmlTemplate).html();
        var closest = data.closest;
        var find    = data.find;

        var element = $(this).closest('.' + closest);

        if (typeof find != 'undefined') {
            element = element.find('.' + find);
        }
        element.find('.append-into').first().append(html);
        
        recalculateModalHeight();

        //small hack #1
        var selector = '';
        if ($('.widget-list-with-edit-button').is(':visible')) {
            selector = '.modal .widget-list-with-edit-button';
            var rand1 = getRandomNumber(1, 100000);
            var rand2 = getRandomNumber(1, 100000);
            $('.modal #change_1').data('trigger', 'ap_' + rand1);
            $('.modal #change_2').data('trigger', 'ap_' + rand2);
            $('.modal #change_1').attr('data-trigger', 'ap_' + rand1);
            $('.modal #change_2').attr('data-trigger', 'ap_' + rand2);
            $('.modal #change_1').attr('id', rand1);
            $('.modal #change_2').attr('id', rand2);
            $('.modal .c1').addClass('upload-ap_' + rand1);
            $('.modal .c2').addClass('upload-ap_' + rand2);
            $('.modal .c1').removeClass('c1');
            $('.modal .c2').removeClass('c2');
            initUploadButtonsChange();
        }
        if ($('.widget-list').is(':visible')) {
            selector = '.modal .widget-list';
        }
        if (selector != '') {
            // $('.remove-list-section').hide();
            if ($(selector).find('.remove-list-section').size() > 1) {
                $(selector).find('.remove-list-section').show();
            } else {
                $(selector).find('.remove-list-section').hide();
            }
            $(this).closest('.' + closest).find('.fa-times').show();
        } else {
            $(this).closest('.' + closest).find('.fa-times').show();
        }
    });

    $(document).on(clickOrTouchstart, '.remove-item', function(e) {
        var data    = $(this).data();
        var closest = data.closest;
        if ($(this).closest('.' + closest).find('.to-be-removed').size() == 2) {
            $(this).closest('.' + closest).find('.remove-item').hide();
        }
        $(this).closest('.to-be-removed').remove();

        //small hack #1 (related)
        var selector = '';
        if ($('.widget-list-with-edit-button').is(':visible')) {
            selector = '.modal .widget-list-with-edit-button';
        }
        if ($('.widget-list').is(':visible')) {
            selector = '.modal .widget-list';
        }
        if (selector != '') {
            if ($(selector).find('.remove-list-section').size() < 2) {
                $(selector).find('.remove-list-section').hide();
            }
        }
    });

    // var d      = new Object;
    // d.userID   = getUserID();
    // var data   = JSON.stringify(d);
    // socket.emit('person_browse', data);
    
    // socket.on('set_online', function(data) {
    //     $('.one-chat-person').each(function(i, k) {
    //         var element    = $(this);
    //         var id         = element.attr('id');
    //         var addedClass = false;
    //         $.each(data, function(o, onePerson) {
    //             if (id == onePerson.userID) {
    //                 element.addClass('online');
    //                 addedClass = true;
    //             }
    //         });
    //         if (!addedClass) {
    //             element.removeClass('online');
    //         }
    //     });
    // });
    // 
    
    $(document).on(clickOrTouchstart, '.like-or-unlike-comment', function(e) {
        var element = $(this);
        var parent  = element.parent();
        var id      = parent.attr('id');
        element.hide();
        element.after('<i class="fa fa-spinner fa-spin"></i>');
        $.ajax({
            url: appurl + '/comment/like-or-unlike-comment',
            data: {'commentID': id},
            success: function(result) {
                if (element.find('.fa').hasClass('fa-heart')) {
                    //parent() here refers to a link holding icon, while parent variable is comment holder
                    parent.find('.fa-heartbeat').parent().show();
                } else {
                    parent.find('.fa-heart').parent().show();
                }
                parent.find('.fa-spin').remove();
                element.closest('.one-post-comment').find('.like-count').text(result.message);
            }
        });
    });

    $(document).on(clickOrTouchstart, '.reply-to-comment', function(e) {
        var element = $(this);
        var parent  = element.parent();
        var id      = parent.attr('id');
        var form = element.closest('.one-post').find('form');
        form.find('.form_commentID').val(id);
        form.find('textarea').trigger('focus');
    });

    $(document).on('change', '.upload-change-it', function(e) {
        var data         = $(this).data();
        var id           = data.trigger;
        var divWithImage = $('.upload-' + id);
        divWithImage.html('');
        if ($(this).hasExtension(['.jpg', '.png', '.gif'])) {
            divWithImage.append('<img src="' + URL.createObjectURL(e.target.files[0]) + '">');
            divWithImage.addClass('height-50px');
        }
        divWithImage.parent().find('ul').remove();
    });

    $(document).on(clickOrTouchstart, '.submit-modal-form', function(e) {
        e.preventDefault();

        var form      = $(this).closest('.modal').find('form');
        var submitUrl = form.attr('action');
        $('.modal .mc').hide();
        $('.mcl').show();
        form.ajaxSubmit({type: 'POST', url: submitUrl,
            success: function(data) {
                if (typeof data.status == 'undefined') {
                    $('.modal .mc').html(data);
                    $('.modal .mc').show();
                    $('.mcl').hide();
                    applyInitFunctions();
                    if (doesExists('#video')) {
                        addVideoToModalElement($('#video'));
                    }
                } else {
                    if (data.status) {
                        window.location = data.url;
                    } else {
                        $('.modal .mc').show();
                        $('.mcl').hide();
                        callNotification(data.message, 'error');
                    }
                }
            }
        });
    });

    $.fn.hasExtension = function(exts) {
        return (new RegExp('(' + exts.join('|').replace(/\./g, '\\.') + ')$')).test($(this).val());
    }

    $(document).on('keyup paste', '#video', function(e) {
        addVideoToModalElement($(this));
    });

    function addVideoToModalElement(element) {
        var parent           = element.parent();
        var youtubeContainer = parent.find('.video-container').size();
        if (!youtubeContainer) {
            var regex = /^.*(?:(?:youtu\.be\/|v\/|vi\/|u\/\w\/|embed\/)|(?:(?:watch)?\?v(?:i)?=|\&v(?:i)?=))([^#\&\?]*).*/;
            var val   = element.val();
            var id    = val.match(regex);
            if (id) {
                parent.find('.video-container').remove();
                parent.append('<div class="video-container">' + 
                                '<iframe width="300" height="200"'+
                                    'src="http://www.youtube.com/embed/' + id[1] + '">'+
                                '</iframe>' +
                              '</div>');
            }
        }
    }
    
});