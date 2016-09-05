$(function () {

    var coID = $('#ipt-consultorio-id').val();


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

            var dt = e.date.format('YYYY-MM-DD');

            window.location.href = '/agenda/' + dt + '/consultorio/' + coID;
        })
    ;
});