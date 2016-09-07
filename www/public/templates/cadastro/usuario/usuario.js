$(function () {

    var $form = $('form.usuario');
    var $modalAten = $('.modal.atencao');
    var $modalSucs = $('.modal.sucesso');
    var $modalErro = $('.modal.erro');


    $form
        .on('click', '.novo:submit', function () {

            $.ajax({
                url: '/cadastro/usuario',
                method: 'POST',
                data: $form.serialize(),
                success: function () {
                    $modalSucs.modal('show');
                },
                error: function () {
                    $modalErro.modal('show');
                }
            });
        })
        .on('click', '.atualizar:submit', function () {

            $.ajax({
                url: '/cadastro/usuario/' + $form.data('usuario_id'),
                method: 'PUT',
                data: $form.serialize(),
                success: function () {
                    $modalSucs.modal('show');
                },
                error: function () {
                    $modalErro.modal('show');
                }
            });
        })
        .on('click', '.remover:submit', function () {

            $modalAten.modal('show');
        })
    ;


    $modalAten.on('click', '.btn.continuar', function () {

        $.ajax({
            url: '/cadastro/usuario/' + $form.data('usuario_id'),
            method: 'DELETE',
            success: function () {
                $modalSucs.modal('show');
            },
            error: function () {
                $modalErro.modal('show');
            }
        });
    });


    $modalSucs.on('click', '.btn.ok', function () {

        window.location.href = "/cadastro/usuario";
    });


    $(':submit').on('click', function (e) {

        e.preventDefault();

        var $s1 = $form.find('#usr_senha');
        var $s2 = $form.find('#usr_senha2');

        if ($s1.val() !== $s2.val())
        {
            $s1.val('');
            $s2.val('');
        }

        if (!bottle.container.valida_form($form))
        {
            e.stopPropagation();
        }
    });

});