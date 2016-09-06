$(function () {

    var $form = $('form.usuario');
    var $mdAtencao = $('.modal.atencao');
    var $mdSucesso = $('.modal.sucesso');
    var $mdErro = $('.modal.erro');


    $('.sucesso .btn.ok').on('click', function () {

        window.location.href = "/cadastro/usuario";
    });


    $('.btn.remover').on('click', function () {

        $mdAtencao.modal('show');
    });


    $('.btn.continuar').on('click', function () {

        $.ajax({
            url: '/cadastro/usuario/' + $form.data('usuario_id'),
            method: 'DELETE',
            success: function () {
                $mdSucesso.modal('show');
            },
            error: function () {
                $mdErro.modal('show');
            }
        });
    });


    $('.btn.atualizar').on('click', function () {

        $.ajax({
            url: '/cadastro/usuario/' + $form.data('usuario_id'),
            method: 'PUT',
            data: $form.serialize(),
            success: function () {
                $mdSucesso.modal('show');
            },
            error: function () {
                $mdErro.modal('show');
            }
        });
    });


    $('.btn.novo').on('click', function () {

        $.ajax({
            url: '/cadastro/usuario',
            method: 'POST',
            data: $form.serialize(),
            success: function () {
                $mdSucesso.modal('show');
            },
            error: function () {
                $mdErro.modal('show');
            }
        });
    });
});