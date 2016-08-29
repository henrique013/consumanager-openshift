$(function () {

    $('#md-paciente .input-group.date').datetimepicker({
        locale: 'pt-br',
        tooltips: window.settings.datepicker.tooltips,
        format: 'DD/MM/YYYY',
        focusOnShow: false,
        showClose: true,
        showTodayButton: true
    });
});


// lima os formulários ao fechar os modais
$('body')
    .on('click', '.btn-logout', function (ev) {

        ev.preventDefault();

        var btn = $(this);

        $.ajax({
            url: btn.data('action'),
            success: function () {
                window.location.href = btn.attr('href');
            }
        });
    })
    .on('click', '.modal .close', function () {

        $(this).closest('.modal-content').find('form').trigger("reset");
    })
;