$(function () {

    var $form = $('form.consulta');
    var $modalAten = $('.modal.atencao');
    var $modalSucs = $('.modal.sucesso');
    var $modalErro = $('.modal.erro');


    $('.input-group.date').datetimepicker({
        locale: 'pt-br',
        tooltips: bottle.container.datepicker_tooltips,
        format: 'DD/MM/YYYY',
        focusOnShow: false,
        showClose: true,
        showTodayButton: true
    });


    $('#ipt-pac-nome').typeahead({
        items: 10,
        ajax: {
            url: '/cadastro/consulta/pacientes',
            timeout: 300,
            triggerLength: 1,
            method: "get",
            preDispatch: function (query) {
                return {
                    nome: query
                }
            }
        },
        onSelect: function (item) {

            var pac = JSON.parse(item.value);

            $('#ipt-pac-id').val(pac.id);

            $('.pac-nome').text(pac.nome);
            $('.pac-endereco').text(pac.endereco);
            $('.pac-telefones').text(pac.telefones);
        }
    });


    $form
        .on('click', '.novo:submit', function () {

            $.ajax({
                url: '/cadastro/consulta',
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
                url: '/cadastro/consulta/' + $form.data('consulta_id'),
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
        .on('click', '.remover:button', function () {

            $modalAten.modal('show');
        })
    ;


    $modalAten.on('click', '.btn.continuar', function () {

        $.ajax({
            url: '/cadastro/consulta/' + $form.data('consulta_id'),
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

        window.location.href = "/agenda/" + $('#ipt-con-data').val() + "/consultorio/" + $('#ipt-co-id').val();
    });


    $(':submit').on('click', function (e) {

        e.preventDefault();

        if (!$('#ipt-pac-id').val())
        {
            $('#ipt-pac-nome').val('');
        }

        if (!bottle.container.valida_form($form))
        {
            e.stopPropagation();
        }
    });

});