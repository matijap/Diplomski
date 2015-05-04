
function getScreenWidth() {
    return $(window).width();
}

function applyResizeFunctions() {
    adjustMainPanelWidth();
    adjustCommentTextWidth();    
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


