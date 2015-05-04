
function getScreenWidth() {
    return $(window).width();
}

function applyResizeFunctions() {
    adjustMainPanelWidth();
    adjustCommentTextWidth();
    adjustFantasyManageRightPanel();

    var toBeCleared = setInterval( function() {
        var ifExists = doesExists('#select2-player-market-container')
        if (ifExists) {
            $('#select2-player-market-container').closest('.select2-container').addClass('float-left');
            clearInterval(toBeCleared);
        }
    }, 50);
}

function getElementWidth(element, outerWidth) {
    return element == 'window' ? getScreenWidth() :
    typeof(outerWidth) === 'undefined' ? $(element).width() : $(element).outerWidth();
}

function adjustMainPanelWidth() {
    var windowWidth          = getElementWidth('window');
    var chatWindowWidth      = getElementWidth('.chat-sidebar', true);
    var leftWidgetAreaWidth  = getElementWidth('#left-widget-area', true);
    var rightWidgetAreaWidth = getElementWidth('#right-widget-area', true);

    var acumulated = chatWindowWidth + leftWidgetAreaWidth + rightWidgetAreaWidth;
    $('#main-panel').css('width', windowWidth - acumulated + 'px');

    var startingLeft = chatWindowWidth + leftWidgetAreaWidth + 10;
    $('#main-panel').css('left', startingLeft + 'px');
}

function adjustCommentTextWidth() {
    $('.one-post-comment').each(function( index ) {
        var comment      = $(this);
        var commentWidth = getElementWidth(comment);
        var remaining    = commentWidth - 55;
        comment.find('.one-post-comment-text').width(remaining);
        comment.find('.post-count-and-date').width(remaining);
    });
}

function adjustFantasyManageRightPanel() {
    var mainPanelWidth      = getElementWidth('#main-panel', true);
    var pitchContainerWidth = getElementWidth('.pitch-container');
    var rightPanelWidth     = mainPanelWidth - pitchContainerWidth - 10;
    $('.pitch-container').next().css('max-width', rightPanelWidth + 'px');
    $('.pitch-container').next().css('width', (rightPanelWidth - 30) + 'px');
}

function getClickOrTouchstart() {
    return desktopOrHandheld() ? 'touchstart' : 'click';
}

function desktopOrHandheld() {
    return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
}

function toggleBetweenClasses(element, class1, class2) {
    $(element).toggleClass(class1);
    $(element).toggleClass(class2);
}

function stripDotSlashToUnderScoreFilter(value) {
    var filteredValue = value.replace(/\./g, '');
    filteredValue     = filteredValue.replace(/\-/g, '_');
    return filteredValue;
}

function doesExists(element) {
    return $(element).size();
}

function afterWidgetSort() {
    var leftArray  = [];
    var rightArray = [];
    $('.column').each(function() {
        var itemorder = [];
        $(this).find('.one-widget').each(function() {
            itemorder.push($(this).attr('id'));
        });
        var columnId  = $(this).attr('id');
        if (columnId == 'left-widget-area') {
            leftArray.push(itemorder.toString())
        } else {
            rightArray.push(itemorder.toString())
        }
    });
    var mainJSON = JSON.stringify({left: leftArray, right: rightArray});
    console.log(mainJSON);
}

function initializeWidgetSortable() {
    if (doesExists('#left-widget-area') && doesExists('#right-widget-area')) {
        $("#left-widget-area").sortable({
            group: "widgets",
            onEnd: function() {
                afterWidgetSort();
            }
        });

        $("#right-widget-area").sortable({ group: "widgets",
            onEnd: function() {
                afterWidgetSort();
            }
        });
    }
    if (!doesExists('#left-widget-area') && doesExists('#right-widget-area')) {
        $("#right-widget-area").sortable({
            group: "widgets",
            onEnd: function() {
                afterWidgetSort();
            }
        });
    }
}

function initializeSimpleSortable(element, destroyBeforeInitialize) {
    if (typeof(destroyBeforeInitialize) != 'undefined') {
        var sort = $(element).sortable("widget");
        sort.sortable('destroy');
    }
    var s = $(element).sortable({sort: true});
}

function getRandomNumber(floor, ceil) {
    var start  = typeof(floor) == 'undefined' ? 1 : floor;
    var finish = typeof(ceil) == 'undefined' ? 10000000 : ceil;
    return Math.floor((Math.random() * finish) + start);
}

function showFantasyCancel() {
    $('.fantasy-cancel').show();
}

function callNotification(text, status) {
    $(function() {
        new PNotify({
            text: text,
            type: status,
            delay: 5000
        });
    });
}

