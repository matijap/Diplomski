$( document ).ready(function() {
    var windowWidth       = getScreenWidth();
    var clickOrTouchstart = getClickOrTouchstart();

    applyResizeFunctions();
    $( window ).resize(function() {
        applyResizeFunctions();
    });

    initializeWidgetSortable();
    initUploadButtonsChange();


    $(document).on('focus', '.post-comment-textarea', function() {
        $(this).css('height', '100px');
    });
    $(document).on('blur', '.post-comment-textarea', function() {
        $(this).css('height', '25px');
    });

    $('select').select2();

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
            $('.modal-body .mc').append($('.' + data.template).html());
            if (data.template == 'new-widget-template') {
                var randomNumber = getRandomNumber();
                $('.modal-body .mc').find('#replace-list-element').attr('id', 'owls' + randomNumber);
                initializeSimpleSortable('#owls' + randomNumber);

                $('.modal-body .mc').find('#replace-table-element').attr('id', 'owt' + randomNumber);
                initializeSimpleSortable('#owt' + randomNumber);

                $('.modal-body .mc').find('#replace-list-section').attr('id', 'wls' + randomNumber);
                $('.modal-body .mc').find('.widget-settings .all-widget-types').each(function() {
                    initializeSimpleSortable('#' + $(this).attr('id'));
                });
            }
            addIDAndInitializeSortable();
            //initUploadButtonsChange();
        }, 000);
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
        $('.modal-body .mc').find('.all-widget-types').hide();
        $('.modal-body .mc').find('.' + data.widgetType).show();
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
        var data = $(this).closest('.widget-marker').data();
        $(this).closest('.widget-marker').append($('.' + data.template).html());
        var sortableID = $(this).closest('.widget-marker').attr('id');
        $(this).remove();
        initializeSimpleSortable('#' + sortableID, true);
    });

    // $(document).on(clickOrTouchstart, '.add-list-section', function(e) {
    //     e.preventDefault();
    //     var widgetList = $(this).closest('form').find('.widget-settings').find('div.widget-list');
    //     if (widgetList.find('.one-widget-list-section').size() == 1) {
    //         widgetList.find('.one-widget-list-section').find('.widget-list-section-title').after('<i class="fa fa-times remove-section"></i>');
    //     }
    //     widgetList.append($('.list-section-template').html());
    //     var randomNumber = getRandomNumber();

    //     $('.modal-body .mc').find('#replace-list-element').attr('id', 'owls' + randomNumber);
    //     initializeSimpleSortable('#owls' + randomNumber);

    //     $('.modal-body .mc').find('#replace-list-section').attr('id', 'wls' + randomNumber);
    //     $('.modal-body .mc').find('.widget-settings .all-widget-types').each(function() {
    //         initializeSimpleSortable('#' + $(this).attr('id'), true);
    //     });
    //     recalculateModalHeight();
    // });

    $(document).on(clickOrTouchstart, '.choose-upload', function(e) {
        var data = $(this).data();
        $('#' + data.trigger).trigger('click');
    });

    $('input.datepicker').Zebra_DatePicker();

    $(document).on(clickOrTouchstart, '.dream-team-sport .fa', function(e) {
        $(this).closest('.dream-team-display').find('.dream-team-display-team').toggle();
        $(this).closest('.dream-team-display').find('.fa').toggle();
    });

    $(document).on(clickOrTouchstart, '.dream-team-sport p', function(e) {
        $(this).closest('.dream-team-display').find('.dream-team-display-team').toggle();
        $(this).closest('.dream-team-display').find('.fa').toggle();
    });

    $(document).on(clickOrTouchstart, '.widget-table .fa-cog', function(e) {
        var element = $(this);

        var htmlToAppend = '<div class="widget-table-option-configure">';
        $('.modal #widget-table-data input[type="checkbox"]').each(function() {
            if ($(this).is(':checked')) {
                var data      = $(this).data();
                htmlToAppend += '<input type="text" placeholder="' + data.placeholder + '">';
            }
        });
        htmlToAppend += "<i class='fa fa-check cursor-pointer'></i><i class='fa fa-times cursor-pointer'></i></div>";
        element.closest('.modal-element').append(htmlToAppend);
    });

    $(document).on(clickOrTouchstart, '.widget-table-option-configure .fa-times', function(e) {
        $(this).parent().remove();
    });

    $(document).on(clickOrTouchstart, '.add-new-widget-table-data', function(e) {
        var htmlToAppend = '<label>';
        var fullName     = $(this).parent().find('#widget-table-data-full').val();
        var shortName    = $(this).parent().find('#widget-table-data-short').val();
        htmlToAppend    += fullName + ' (' + shortName + ')';
        htmlToAppend    += '<input type="checkbox" checked="checked" data-placeholder="' + shortName + '"></label>';

        $('.modal #widget-table-data').find('.main-div').append(htmlToAppend);
        $('.modal #widget-table-data').find('.main-div').find('label').last().find('input').data('placeholder', shortName);
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

    $(document).on('change', '.list-option-avatar', function(e) {
        var id           = $(this).attr('id');
        var divWithImage = $('.upload-' + id);
        
        divWithImage.html('');
        divWithImage.append('<img src="' + URL.createObjectURL(e.target.files[0]) + '">');
        divWithImage.addClass('height-50px');
    });
    
});