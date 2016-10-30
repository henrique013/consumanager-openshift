$(function () {

    $('body')
        .on('hidden.bs.modal', '#modal-sucesso', function () {

            location.reload();
        })
    ;


    $('.table-hover tr').on('click', function () {

        var url = $(this).data('url');

        $.get(url, function (html) {
            $(html).modal('show');
        });
    });


    $('.btn-busca').on('click', function () {

        $('form.buscas').trigger('submit');
    });

});
