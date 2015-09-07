$( document ).ready(function() {
    var clickOrTouchstart = getClickOrTouchstart();

    $(document).on(clickOrTouchstart, '.lwb-save', function(e) {
        var widgetOptionID = $('.widgetOptionID').val();
        var itemID         = $('.itemID').val();
        var obj            = {};
        var main           = {};
        var left           = {};
        var right          = {};
        var additional     = {};
        if (widgetOptionID == 'new') {
            $('.add-list-with-button-settings-panel .lwbsm-left').find('input').each(function() {
                var name      = $(this).attr('name');
                var tempObj   = {};
                tempObj.label = $(this).parent().find('label').text();
                tempObj.value = $(this).val();
                left[name]    = tempObj;
            });
            $('.add-list-with-button-settings-panel .lwbsm-right').find('input').each(function() {
                var name      = $(this).attr('name');
                var tempObj   = {};
                tempObj.label = $(this).parent().find('label').text();
                tempObj.value = $(this).val();
                right[name]   = tempObj;
            });
            $('.list-with-button-settings-additional').find('input').each(function() {
                var name         = $(this).attr('name');
                var tempObj      = {};
                tempObj.label    = $(this).parent().find('label').text();
                tempObj.value    = $(this).val();
                additional[name] = tempObj;
            });
            
            $('.close-list-with-button-settings-panel').trigger('click');
            main.left      = left;
            main.right     = right;
            obj.main       = main;
            obj.additional = additional;
            var jsoned     = JSON.stringify(obj);
            $('.customize-list-with-edit-button-options[data-item="' + itemID + '"]').parent().find('.hidden-json').remove();
            $('.customize-list-with-edit-button-options[data-item="' + itemID + '"]').parent().append('<input class="hidden-json" type="hidden" value="' + escapeHtml(jsoned) + '">');
        }
    });

    $(document).on('change', '.upload-change-it', function(e) {
        var id           = $(this).attr('id');
        var divWithImage = $('.upload-' + id);
        
        divWithImage.html('');
        divWithImage.append('<img src="' + URL.createObjectURL(e.target.files[0]) + '">');
        divWithImage.addClass('height-50px');
    });

    $(document).on(clickOrTouchstart, '.customize-list-with-edit-button-options', function(e) {
        var element        = $(this);
        var spinner        = element.parent().find('.fa-spin');
        var data           = element.data();
        var widgetOptionID = data.wiod;
        var item           = data.item;
        element.toggle();
        spinner.toggle();

        var tempData = '';
        if (widgetOptionID == 'new') {
            var tempHidden = element.parent().find('.hidden-json');
            if (tempHidden) {
                tempData = tempHidden.val();
            }
        }
        
        setTimeout(function() {
            $.ajax({
               url: "ajax/customizeListOptions.php",
               data: {'widgetOptionID': widgetOptionID, 'item': item, 'tempData': tempData},
               success: function(result) {
                   $('body').append(result);
                    $('select').select2();
               }
            });
            element.toggle();
            spinner.toggle();
        }, 300);
    });

    $(document).on(clickOrTouchstart, '.add-list-with-button-customize-option', function(e) {
        var picked           = $('.list-with-button-select').val();
        var randomNumber     = getRandomNumber();
        var elementRandom    = getRandomNumber();
        var name             = spacesToUnderScoreAndLowercaseWordFilter(picked);

        var htmlToAppendLeft =  '<div class="list-with-button-settings-item to-be-removed">' + 
                                    '<label class="truncate-string">' + picked + '</label>' + 
                                    '<i data-closest="lwbsm-left" data-item="item-' + randomNumber + '" class="fa fa-times remove-item float-right m-l-5"></i>' +
                                    '<input type="text" name="'+ name + '_' + elementRandom + '[]">' + 
                                '</div>';
        var htmlToAppendRight=  '<div class="list-with-button-settings-item to-be-removed">' + 
                                    '<label class="truncate-string">' + picked + '</label>' + 
                                    '<i data-closest="lwbsm-right" data-item="item-' + randomNumber + '" class="fa fa-times remove-item float-right m-l-5"></i>' +
                                    '<input type="text" name="'+ name + '_' + elementRandom + '[]">' + 
                                '</div>';

        var htmlToAppendAdditional = '<div class="list-with-button-settings-item to-be-removed">' +
                                        '<label class="truncate-string">' + picked + '</label>' +
                                        '<i data-closest="list-with-button-settings-additional" class="fa fa-times remove-item float-right m-l-5"></i>' +
                                        '<input type="text" name="'+ name + '_' + elementRandom + '">' +
                                     '</div>';

        var whereTo = $('.list-with-button-select-where-to').val();

        if (whereTo == 'main') {
            $('.list-with-button-settings-main').find('.lwbsm-left').find('.insert-here').append(htmlToAppendLeft);
            $('.list-with-button-settings-main').find('.lwbsm-right').find('.insert-here').append(htmlToAppendRight);
        } else {
            $('.list-with-button-settings-additional').find('.bb').append(htmlToAppendAdditional);
        }
    });

    $(document).on('change keyup paste', '.list-with-button-settings-option-name', function(e) {
        var val = $(this).val();
        if (val != '') {
            $(this).parent().prev().text(val);
        } else {
            $(this).parent().prev().text('Option Name');
        }
    });

    $(document).on(clickOrTouchstart, '.list-with-button-settings-main .remove-item', function(e) {
        var data    = $(this).data();
        var closest = data.closest;
        var item    = data.item;

        var otherElement = closest == 'lwbsm-left' ? 'lwbsm-right' : 'lwbsm-left';
        $('.' + otherElement).find('i[data-item="' + item + '"]').trigger('click');
    });

    $(document).on(clickOrTouchstart, '.close-list-with-button-settings-panel', function(e) {
        $(this).closest('.add-list-with-button-settings-panel').remove();
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
});