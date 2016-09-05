$(function () {

    var form = $('form.usuario');


    $('.btn.remover')
        .on('click', function () {

            //$.ajax({
            //    url: '/cadastro/usuario/' + form.data('usuario_id'),
            //    method: 'DELETE',
            //    success: function () {
            //        window.location.href = '/cadastro/usuario';
            //    }
            //});
        })
    ;

    $('.btn.atualizar')
        .on('click', function () {

            //$.ajax({
            //    url: '/cadastro/usuario/' + form.data('usuario_id'),
            //    method: 'PUT',
            //    data: form.serialize(),
            //    success: function () {
            //        window.location.href = '/cadastro/usuario';
            //    }
            //});
        })
    ;

    $('.btn.novo')
        .on('click', function () {

            //$.ajax({
            //    url: '/cadastro/usuario',
            //    method: 'POST',
            //    data: form.serialize(),
            //    success: function () {
            //        window.location.href = '/cadastro/usuario';
            //    }
            //});
        })
    ;
});