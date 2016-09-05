$(function () {

    $('main .dt').datetimepicker({
        locale: 'pt-br',
        tooltips: window.util.datepicker.tooltips,
        format: 'DD/MM/YYYY',
        focusOnShow: false,
        showClose: true,
        showTodayButton: true
    });
});