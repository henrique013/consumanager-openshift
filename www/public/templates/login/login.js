$(document).ready(function () {

    $('#form-login').submit(function (ev) {

        ev.preventDefault();


        //TODO: validar campos obrigatórios


        var form = $(this);
        var btn = form.find('.btn-login');

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function () {

                window.location.href = btn.data('redirect');
            },
            error: function () {

                //TODO: implementar!

                window.alert('Dados de acesso incorretos.');
            }
        });
    });
});