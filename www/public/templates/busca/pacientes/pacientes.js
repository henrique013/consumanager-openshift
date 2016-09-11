$(function () {

    $('.table-hover tr').on('click', function () {

        window.location.href = $(this).data('url');
    });


    $('.btn-busca').on('click', function () {

        $('form.buscas').trigger('submit');
    });

});
