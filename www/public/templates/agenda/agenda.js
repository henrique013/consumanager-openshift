$(function () {

    $('main .dt')
        .datetimepicker({
            locale: 'pt-br',
            tooltips: window.util.datepicker.tooltips,
            format: 'DD/MM/YYYY',
            focusOnShow: false,
            showClose: true,
            showTodayButton: true
        })
        .on('dp.change', function (e) {

            window.location.href = '/agenda/' + e.date.format('YYYY-MM-DD');
        })
    ;

});