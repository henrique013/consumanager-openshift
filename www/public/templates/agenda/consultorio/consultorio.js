$(function () {

    $('.input-group.date')
        .datetimepicker({
            locale: 'pt-br',
            tooltips: bottle.container.datepicker_tooltips,
            format: 'DD/MM/YYYY',
            focusOnShow: false,
            showClose: true,
            showTodayButton: true
        })
        .on('dp.change', function (e) {

            var dt = e.date.format('YYYY-MM-DD');

            window.location.href = '/agenda/' + dt + '/consultorio/' + $('#ipt-consultorio-id').val();
        })
    ;
});