$( document ).ready(function() {
    var windowWidth       = getScreenWidth();
    var clickOrTouchstart = getClickOrTouchstart();

    applyResizeFunctions();
    $( window ).resize(function() {
        applyResizeFunctions();
    });
    
    $('.column').sortable({
        connectWith: '.column',
        handle: '.main-label',
        cursor: 'move',
        placeholder: 'placeholder',
        forcePlaceholderSize: true,
        opacity: 0.4,
        stop: function(event, ui) {
            var leftArray  = [];
            var rightArray = [];
            $('.column').each(function() {
                var itemorder = $(this).sortable('toArray');
                var columnId  = $(this).attr('id');
                if (columnId == 'left-widget-area') {
                    leftArray.push(itemorder.toString())
                } else {
                    rightArray.push(itemorder.toString())
                }
            });
            
            var mainJSON = JSON.stringify({left: leftArray, right: rightArray});
            console.log(mainJSON)
            /*Pass sortorder variable to server using ajax to save state*/
        }
    })
    .disableSelection();

    $(document).on('focus', '.post-comment-textarea', function() {
        $(this).css('height', '100px');
    });
    $(document).on('blur', '.post-comment-textarea', function() {
        $(this).css('height', '25px');
    });

    $(document).on(clickOrTouchstart, '.show-more-post-comments', function() {
        var element      = $(this);
        var loadMoreIcon = element.find('.fa-toggle-down');
        var spinner      = element.find('.fa-spinner');
        loadMoreIcon.addClass('display-none');
        spinner.removeClass('display-none');
        setTimeout(function() {
            var clone = $('.one-post-comment').eq(0).html();
            element.prev().before('<div class="one-post-comment">' + clone + '</div>');
            loadMoreIcon.removeClass('display-none');
            spinner.addClass('display-none');
        }, 1000);
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
        setTimeout(function() {
            $('.mcl').toggle();
            // just for the html purposes. remove in php!
            var data = element.data();
            $('.modal-body .mc').html($('.' + data.template).html());


    
    $('.column').sortable({
        connectWith: '.column',
        handle: '.modal-element',
        cursor: 'move',
        placeholder: 'placeholder',
        forcePlaceholderSize: true,
        opacity: 0.4,
        stop: function(event, ui) {
            console.log('stop')
            var leftArray  = [];
            var rightArray = [];
            $('.column').each(function() {
                var itemorder = $(this).sortable('toArray');
                var columnId  = $(this).attr('id');
                if (columnId == 'left-widget-area') {
                    leftArray.push(itemorder.toString())
                } else {
                    rightArray.push(itemorder.toString())
                }
            });
            
            var mainJSON = JSON.stringify({left: leftArray, right: rightArray});
            console.log(mainJSON)
            /*Pass sortorder variable to server using ajax to save state*/
        }
    })

        }, 1000);
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

    $(document).on('change', '#post-selection', function() {
        var val = $(this).val();
        $('#image-url').closest('.modal-element').hide();
        $('#image-upload').closest('.modal-element').hide();
        $('#video-url').closest('.modal-element').hide();
        if (val == 'image') {
            $('#image-url').closest('.modal-element').show();
            $('#image-upload').closest('.modal-element').show();
        }
        if (val == 'video') {
            $('#video-url').closest('.modal-element').show();
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

    $(document).on(clickOrTouchstart, '.submit-modal-form', function(e) {
        e.preventDefault();
        var element = $(this);
        element.closest('.modal').find('form').submit();
    });

    $(document).on(clickOrTouchstart, '.widget-type-selector', function(e) {
        var data = $(this).data();
        $('.all-widget-types').hide();
        $('.' + data.widgetType).show();
    });

    $(document).on(clickOrTouchstart, '.remove-section', function(e) {
        if ($(this).hasClass('remove-list-option')) {
            classToRemove = '.modal-element';
            
            var alreadyAddedPlus = false;
            // if we try to remove row that contains plus, then plus must be moved one row before current
            if ($(this).closest(classToRemove).find('.add-list-option').size() > 0) {
                $(this).closest(classToRemove).prev().find('.clear').before('<i class="fa fa-plus m-l-5 add-list-option"></i>');
                alreadyAddedPlus = true;
            }

            // if there are 2 list option fields (total of 4 elements, along with avatar and title),
            // we need to make sure to remove plus elements from each of them, so that only one plus remain
            if ($(this).closest('.one-widget-list-section').find('.modal-element').size() == 4) {
                $(this).closest(classToRemove).prev().find('.remove-section').remove();
                $(this).closest(classToRemove).next().find('.remove-section').remove();
                if (!alreadyAddedPlus) {
                    $(this).closest(classToRemove).prev().find('.clear').before('<i class="fa fa-plus m-l-5 add-list-option"></i>');
                }
            }
        } else {
            classToRemove = '.one-widget-list-section';
            var widgetList = $(this).closest('form').find('.widget-settings').find('div.widget-list');
            if (widgetList.find('.one-widget-list-section').size() == 2) {
                $(this).closest(classToRemove).prev().find('.widget-list-section-title').next().remove();
                $(this).closest(classToRemove).next().find('.widget-list-section-title').next().remove();
            }
        }
        $(this).closest(classToRemove).remove();
    });

    $(document).on(clickOrTouchstart, '.add-list-option', function(e) {
        //if this list option does not have remove, that means it is only one in section and should have it after adding new option
        if ($(this).closest('.modal-element').find('.remove-section').size() == 0) {
            $(this).closest('.modal-element').find('.add-list-option').before('<i class="fa fa-times remove-section remove-list-option"></i>');
        }
        $(this).closest('.one-widget-list-section').append($('.list-option-template').html());
        $(this).remove();
    });

    $(document).on(clickOrTouchstart, '.add-list-section', function(e) {
        e.preventDefault();
        var widgetList = $(this).closest('form').find('.widget-settings').find('div.widget-list');
        if (widgetList.find('.one-widget-list-section').size() == 1) {
            widgetList.find('.one-widget-list-section').find('.widget-list-section-title').after('<i class="fa fa-times remove-section"></i>');
        }
        widgetList.append($('.list-section-template').html());
    });
});