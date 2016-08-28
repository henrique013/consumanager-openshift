$(function () {

    $('#datetimepicker1').datetimepicker({
        locale: 'pt-br',
        tooltips: window.settings.datepicker.tooltips,
        format: 'DD/MM/YYYY',
        focusOnShow: false,
        showClose: true,
        showTodayButton: true
    });
});