$( document ).ready(function() {
    var clickOrTouchstart = getClickOrTouchstart();
    var appurl = getAppUrl();
    
    $(document).on(clickOrTouchstart, '.lwb-save', function(e) {
        var widgetOptionID = $('.widgetOptionID').val();
        var itemID         = $('.itemID').val();
        var obj            = {};
        var main           = {};
        var left           = {};
        var right          = {};
        var additional     = {};
        // if (widgetOptionID == 'new') {
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
        $('.customize-list-with-edit-button-options[data-item="' + itemID + '"]').parent().append('<input name="lweb[data][' + itemID + ']" class="hidden-json" type="hidden" value="' + escapeHtml(jsoned) + '">');
        // } else {

        // }
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
        var appurl = getAppUrl();
        $.ajax({
           url: appurl + "/widget/edit-lweb-data",
           data: {'widgetOptionID': widgetOptionID, 'item': item, 'tempData': tempData},
           success: function(result) {
               $('body').append(result);
               $('select').select2();
               element.toggle();
               spinner.toggle();
               addIDAndInitializeSortable();
           }
        });
    });

    $(document).on(clickOrTouchstart, '.add-list-with-button-customize-option', function(e) {
        var picked           = $('.add-new-lweb-data').val();
        if (picked != '') {
            $('.add-new-lweb-data').val('');
            $.ajax({
                url: appurl + "/widget/save-lweb-data",
                data: {'data': picked, 'userID': getUserID()},
                success: function(result) {
                    
                }
            }); 
        } else {
            picked           = $('.list-with-button-select').val();
        }
        var randomNumber     = getRandomNumber();
        var elementRandom    = getRandomNumber();
        var name             = spacesToUnderScoreAndLowercaseWordFilter(picked);

        nameMain       = 'main[' + elementRandom + '][]';
        nameAdditional = 'additional[' + elementRandom + '][]';

        var htmlToAppendLeft =  '<div class="list-with-button-settings-item to-be-removed">' + 
                                    '<label class="truncate-string">' + picked + '</label>' + 
                                    '<i data-closest="lwbsm-left" data-item="item-' + randomNumber + '" class="fa fa-times remove-item float-right m-l-5"></i>' +
                                    '<input type="text" name="' + nameMain + '">' + 
                                '</div>';
        var htmlToAppendRight=  '<div class="list-with-button-settings-item to-be-removed">' + 
                                    '<label class="truncate-string">' + picked + '</label>' + 
                                    '<i data-closest="lwbsm-right" data-item="item-' + randomNumber + '" class="fa fa-times remove-item float-right m-l-5"></i>' +
                                    '<input type="text" name="' + nameMain + '">' + 
                                '</div>';

        var htmlToAppendAdditional = '<div class="list-with-button-settings-item to-be-removed">' +
                                        '<label class="truncate-string">' + picked + '</label>' +
                                        '<i data-closest="list-with-button-settings-additional" class="fa fa-times remove-item float-right m-l-5"></i>' +
                                        '<input type="text" name="' + nameAdditional + '">' +
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
        $('.' + otherElement).find('i[data-item="' + item + '"]').closest('.to-be-removed').remove();
    });

    $(document).on(clickOrTouchstart, '.close-list-with-button-settings-panel', function(e) {
        $(this).closest('.add-list-with-button-settings-panel').remove();
    });

    $(document).on(clickOrTouchstart, '.widget-table .fa-cog', function(e) {
        var element = $(this);
        var input   = element.parent().find('input[type="hidden"]').val();
        if (input != '') {
            input       = $.parseJSON(input);
        }

        var htmlToAppend = '<div class="widget-table-option-configure">';
        $('.modal #widget-table-data input[type="checkbox"]').each(function() {
            if ($(this).is(':checked')) {
                var data      = $(this).data();
                var val       = '';
                if (input != '') {
                    val       = (data.originalShort in input) ? input[data.originalShort] : '';
                }
                htmlToAppend += '<input data-original-short="' + data.originalShort + '" type="text" placeholder="' + data.placeholder + '" value="' + val + '">';
            }
        });
        htmlToAppend += "<i class='fa fa-check cursor-pointer'></i><i class='fa fa-times cursor-pointer'></i></div>";
        element.closest('.modal-element').append(htmlToAppend);
    });

    $(document).on(clickOrTouchstart, '.remove-table-data', function(e) {
        e.stopPropagation();
        e.preventDefault();
        var element = $(this);
        var data    = element.data();
        element.hide();
        element.parent().find('input').before('<i style="margin-right: 90px; margin-left:0; font-size: 9px; margin-top: 5px;" class="fa fa-spin fa-spinner pull-right"></i>');
        $.ajax({
            url: appurl + "/widget/delete-table-data",
            data: {'id': data.id },
            success: function(result) {
                if (result.status) {
                    element.parent().remove();
                } else {
                    element.show();
                    element.parent().find('.fa-spin').remove();
                }
                callNotification(result.message, 'success')
            }
        }); 
    });

    $(document).on(clickOrTouchstart, '.widget-table-option-configure .fa-times', function(e) {
        $(this).parent().remove();
    });
    $(document).on(clickOrTouchstart, '.widget-table-option-configure .fa-check', function(e) {
        var element = $(this);
        element.closest('.modal-element').find('input[type="hidden"]').remove();
        var arr     = {};
        element.parent().find('input').each(function() {
            var input  = $(this);
            var data   = input.data();
            var val    = input.val();
            var short  = data.originalShort;
            arr[short] = val;
        });
        var input  = element.closest('.modal-element').find('input');
        var data   = input.data();
        arr        = escapeHtml(JSON.stringify(arr));
        var hidden = '<input type="hidden" value="' + arr +  '" name="table[' + data.id + '][value_2]">';
        element.closest('.modal-element').find('.main-div').append(hidden);
        element.parent().remove();
    });

    $(document).on(clickOrTouchstart, '.add-new-widget-table-data', function(e) {
        var element    = $(this);
        var userID     = getUserID();
        var longInput  = element.parent().find('#widget-table-data-full');
        var shortInput = element.parent().find('#widget-table-data-short');
        
        var fullName     = longInput.val();
        var shortName    = shortInput.val();
        if (fullName == '' || shortName == '') return false;
        element.hide();
        element.before('<i class="fa fa-spin fa-spinner" style="font-size: 13px;"></i>');
        $.ajax({
            url: appurl + "/widget/add-table-data",
            data: {'long': fullName, 'short':shortName, 'user_id': userID},
            success: function(result) {
                element.parent().find('.fa-spin').remove();
                element.show();
                if (result.status == 'success') {
                    longInput.val('');
                    shortInput.val('');

                    var htmlToAppend = '<label>';
                    htmlToAppend    += fullName + ' (' + shortName + ')';
                    htmlToAppend    += '<i data-id="' + result.id + '" class="fa fa-times cursor-pointer remove-table-data"></i>';
                    htmlToAppend    += '<input data-original-short="' + shortName + '" type="checkbox" checked="checked" data-placeholder="' + shortName + '"></label>';

                    $('.modal #widget-table-data').find('.main-div').append(htmlToAppend);
                    $('.modal #widget-table-data').find('.main-div').find('label').last().find('input').data('placeholder', shortName);
                }
                callNotification(result.message, result.status)
            }
        });
    });

    $(document).on(clickOrTouchstart, '.widget-type-selector', function(e) {
        var val = $(this).val();
        widgetType = 'widget-';
        switch(val) {
            case 'PLAIN':
                widgetType += 'plain';
                break;
            case 'LIST':
                widgetType += 'list';
                break;
            case 'TABLE':
                widgetType += 'table';
                break;
            case 'LIST_WEB':
                widgetType += 'list-with-edit-button';
                break;
        }
        $('.modal-body .mc').find('.all-widget-types').hide();
        $('.modal-body .mc').find('.' + widgetType).show();
        initUploadButtonsChange();
    });
});