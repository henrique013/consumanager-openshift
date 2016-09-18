$(function () {

    $('body')
        .on('hidden.bs.modal', '#modal-sucesso', function () {
            location.reload();
        })
    ;


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


    $('.panel-footer .btn').on('click', function (e) {
        e.preventDefault();
        var target = $(this).attr('href');
        $.get(target, function (html) {
            $(html).modal('show');
        });
    });
});