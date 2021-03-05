$(document).ready(function () {
    var panel = $('.js_panel');
    $(document).on('click', '.js_panel_toggle', function () {
        if (panel.hasClass('panel-opened')) //if open do close
        {
            panel.removeClass('panel-opened tooltiphide');
            $(this).addClass('glyphicon-option-vertical').removeClass('glyphicon-option-horizontal');
        }
        else //if closed do open
        {
            panel.addClass('panel-opened tooltiphide');
            $(this).removeClass('glyphicon-option-vertical').addClass('glyphicon-option-horizontal');
        }
    });

    $("[data-toggle='tooltip']").tooltip();
});