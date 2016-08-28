$(document).ready(function () {

    $('#form-login').submit(function (ev) {

        ev.preventDefault();


        //TODO: validar campos obrigatórios


        var form = $(this);

        $.ajax({
            url: form.attr('action'),
            method: form.attr('method'),
            data: form.serialize(),
            success: function () {

                window.location.href = '/agenda';
            },
            error: function () {

                //TODO: implementar!

                window.alert('Dados de acesso incorretos.');
            }
        });
    });
});