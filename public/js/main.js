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
        event.preventDefault();
        var element = $(this);
        $('.modal-header-title p').text('');
        $( ".modal.fade" ).toggle();
        $( ".modal.fade" ).fadeTo( "fast" , 1, function() {});
        $('.mcl').show();
        $('.modal-footer').find('.submit-modal-form').remove();
        var appurl = getAppUrl();
        var data   = element.data();
        $.ajax({
            url: appurl + data.url,
            data: {},
            success: function(result) {
                $('.mcl').hide();
                $('.modal-body .mc').append(result);
                $('.modal-body .mc').show();
                var title    = $('#modal_title').val();
                var template = doesExists('#is_delete') ? 'delete' : 'submit'
                var button   = $('.' + template + '-modal-template').html();
                $('.modal-footer').append(button);
                if (doesExists('#hide_submit')) {
                    $('.submit-modal-form').hide();
                }
                $('.modal-header-title p').text(title);
                applyInitFunctions();
                recalculateModalHeight();
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
        var chars = (closest.indexOf('d') == 0);


        //hack to enable selectors that does not start with dot (.), like div.some-class
        c = chars ? closest : '.' + closest;
        var element = $(this).closest(c);

        if (typeof find != 'undefined') {
            element = element.find('.' + find);
        }
        element.find('.append-into').first().append(html);
        
        recalculateModalHeight();

        //small hack #1
        var selector = '';
        if ($('div.widget-list-with-edit-button').is(':visible')) {
            selector = '.modal div.widget-list-with-edit-button';
            var rand1 = getRandomNumber(1, 100000);
            var rand2 = getRandomNumber(1, 100000);
            var rand3 = getRandomNumber(1, 100000);
            var rand4 = getRandomNumber(1, 100000);
            $('.new-widget-modal .change_1').data('trigger', 'ap_' + rand1);
            $('.new-widget-modal .change_2').data('trigger', 'ap_' + rand2);
            $('.new-widget-modal .change_1').attr('data-trigger', 'ap_' + rand1);
            $('.new-widget-modal .change_2').attr('data-trigger', 'ap_' + rand2);
            var input   = $('.modal .change_1').closest('.one-widget-list-section').find('input[type="text"]');
            var inputID = input.attr('id');
            var did     = '';
            if (inputID == 'list-section-title-1') {
                input.attr('data-id', rand4);
                input.attr('name', 'lweb[' + rand4 + '][title_' + rand4 + ']');
                did = rand4;
            } else {
                var d = input.data(); 
                did   = d.id;
            }

            $('.new-widget-modal .change_1').parent().find('.fa-cog').attr('data-item', rand3);
            
            $('.new-widget-modal .change_1').attr('name', 'lweb[' + did + '][' + rand3 + '][images][1]');
            $('.new-widget-modal .change_2').attr('name', 'lweb[' + did + '][' + rand3 + '][images][2]');
            $('.new-widget-modal .change_1').attr('id', rand1);
            $('.new-widget-modal .change_2').attr('id', rand2);

            $('.new-widget-modal .h1').attr('name', 'lweb[' + did + '][' + rand3 + ']');
            $('.new-widget-modal .h1').removeClass('h1');

            $('.new-widget-modal .change_1').removeClass('change_1');
            $('.new-widget-modal .change_2').removeClass('change_2');
            $('.new-widget-modal a.change_1').removeClass('change_1');
            $('.new-widget-modal a.change_2').removeClass('change_2');
            
            $('.new-widget-modal .c1').addClass('upload-ap_' + rand1);
            $('.new-widget-modal .c2').addClass('upload-ap_' + rand2);
            $('.new-widget-modal .c1').removeClass('c1');
            $('.new-widget-modal .c2').removeClass('c2');
            initUploadButtonsChange();
            if ($(this).hasClass('blue-button')) {
                $('i[data-closest="div.widget-list-with-edit-button"]').show();
            } else {
                $(this).closest(c).find('.fa-times').show();
            }
        }
        if ($('div.widget-list').is(':visible')) {
            selector = '.modal .widget-list';
            var fileTrigger = getRandomNumber(1, 100000);
            var sectionID   = getRandomNumber(1, 100000);
            var optionID    = getRandomNumber(1, 100000);

            var input   = $('.modal .t1').closest('.one-widget-list-section').find('.modal-element').first().find('input[type="text"]');
            var inputID = input.attr('id');
            var did     = '';
            if (inputID == 'list-section-title-1') {
                input.attr('data-id', sectionID);
                input.attr('name', 'list[' + sectionID + '][title_' + sectionID + ']');
                did = sectionID;
            } else {
                var d = input.data(); 
                did   = d.id;
            }

            $('.new-widget-modal .change_1').data('trigger', 'ap_' + fileTrigger);
            $('.new-widget-modal .change_1').attr('id', fileTrigger);
            $('.new-widget-modal .change_1').attr('name', 'list[' + did + '][image_' + did + ']');
            $('.new-widget-modal .c1').addClass('upload-ap_' + fileTrigger);
            $('.new-widget-modal .c1').removeClass('c1');
            $('.new-widget-modal .t1').attr('name', 'list[' + did + '][' + optionID + '][value_1]');
            $('.new-widget-modal .t2').attr('name', 'list[' + did + '][' + optionID + '][value_2]');
            $('.new-widget-modal .t1').removeClass('t1');
            $('.new-widget-modal .t2').removeClass('t2');

            $('.new-widget-modal .change_1').removeClass('change_1');
            $('.new-widget-modal a.change_1').removeClass('change_1');

            initUploadButtonsChange();
            if ($(this).hasClass('blue-button')) {
                $('i[data-closest="div.widget-list"]').show();
            } else {
                $(this).closest(c).find('.fa-times').show();
            }
        }
        if ($('div.widget-table').is(':visible')) {
            var id = getRandomNumber(1, 100000);
            $('.new-widget-modal .c1').attr('data-id', id);
            $('.new-widget-modal .c1').data('id', id);
            $('.new-widget-modal .c1').attr('name', 'table[' + id + '][value_1]');
            $('.new-widget-modal .c2').attr('name', 'table[' + id + '][value_2]');
            $('.new-widget-modal .c1').removeClass('c1');
            $('.new-widget-modal .c2').removeClass('c2');
        }
        if (selector != '') {
            if ($(selector).find('.remove-list-section').size() > 1) {
                // $(selector).find('.remove-list-section').show();
            } else {
                $(selector).find('.remove-list-section').hide();
            }
        } else {
            $(this).closest(c).find('.fa-times').show();
        }
        recalculateModalHeight();
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
        if ($('div.widget-list-with-edit-button').is(':visible')) {
            selector = '.modal div.widget-list-with-edit-button';
        }
        if ($('div.widget-list').is(':visible')) {
            selector = '.modal div.widget-list';
        }

        if (selector != '') {
            if ($(selector).find('.remove-list-section').size() < 2) {
                $(selector).find('.remove-list-section').hide();
            }
        }
    });

    var d      = new Object;
    d.userID   = getUserID();
    var data   = JSON.stringify(d);
    socket.emit('person_browse', data);
    
    socket.on('set_online', function(data) {
        $('.one-chat-person').each(function(i, k) {
            var element    = $(this);
            var id         = element.attr('id');
            var addedClass = false;
            $.each(data, function(o, onePerson) {
                if (id == onePerson.userID) {
                    element.addClass('online');
                    addedClass = true;
                }
            });
            if (!addedClass) {
                element.removeClass('online');
            }
        });
    });
    
    
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
                if (element.find('.fa').hasClass('fa-thumbs-up')) {
                    //parent() here refers to a link holding icon, while parent variable is comment holder
                    parent.find('.fa-thumbs-down').parent().show();
                } else {
                    parent.find('.fa-thumbs-up').parent().show();
                }
                parent.find('.fa-spin').remove();
                element.closest('.one-post-comment').find('.like-count').text(result.message);
            }
        });
    });

    $(document).on(clickOrTouchstart, '.one-post-actions-holder .favorite-or-unfavorite-comment', function(e) {
        var element   = $(this);
        var parent    = element.parent();
        var commentID = parent.attr('id');
        var userID    = getUserID();
        element.hide();
        element.after('<i class="fa fa-spin fa-spinner"></i>');
        $.ajax({
            url: appurl + '/comment/favorite-or-unfavorite-comment',
            data: {'commentID': commentID, 'userID': userID},
            success: function(result) {
                var sufix = element.find('.fa').hasClass('fa-heart') ? 'beat': '';
                element.parent().find('.fa-spinner').remove();
                var e = element.parent().find('.favorite-or-unfavorite-comment .fa-heart' + sufix).parent();
                e.show();
                callNotification(result.message, 'success');
            }
        });
    });

    $(document).on(clickOrTouchstart, '.post-header .like-or-unlike-post', function(e) {
        var element = $(this);
        var postID  = element.closest('.one-post').attr('id');
        var userID  = getUserID();
        element.hide();
        element.after('<i class="fa fa-spin fa-spinner"></i>');
        $.ajax({
            url: appurl + '/index/like-or-unlike-post',
            data: {'postID': postID, 'userID': userID},
            success: function(result) {
                var sufix = element.hasClass('fa-thumbs-up') ? 'down': 'up';
                element.parent().find('.fa-spinner').remove();
                var e = element.parent().find('.like-or-unlike-post.fa-thumbs-' + sufix);
                e.show();
                callNotification(result.message, 'success');
            }
        });
    });

    $(document).on(clickOrTouchstart, '.post-header .favorite-or-unfavorite-post', function(e) {
        var element = $(this);
        var postID  = element.closest('.one-post').attr('id');
        var userID  = getUserID();
        element.hide();
        element.after('<i class="fa fa-spin fa-spinner"></i>');
        $.ajax({
            url: appurl + '/index/favorite-or-unfavorite-post',
            data: {'postID': postID, 'userID': userID},
            success: function(result) {
                var sufix = element.hasClass('fa-heart') ? 'beat': '';
                element.parent().find('.fa-spinner').remove();
                var e = element.parent().find('.favorite-or-unfavorite-post.fa-heart' + sufix);
                e.show();
                callNotification(result.message, 'success');
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
        recalculateModalHeight();
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