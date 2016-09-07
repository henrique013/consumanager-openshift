$(function () {

    var $form = $('form.paciente');
    var $modalAten = $('.modal.atencao');
    var $modalSucs = $('.modal.sucesso');
    var $modalErro = $('.modal.erro');


    $('#ipt-pac-nascimento').mask('00/00/0000', {
        clearIfNotMatch: true,
        onKeyPress: function (val, event, field) {

            // evita que o usuário insira manualmente a data
            $(field).val('');
        }
    });


    $('#ipt-pac-telefone').mask(bottle.container.mask_telefone, {
        clearIfNotMatch: true
    });


    $('#ipt-pac-telefone2').mask(bottle.container.mask_telefone, {
        clearIfNotMatch: true
    });


    $('.input-group.date').datetimepicker({
        locale: 'pt-br',
        tooltips: bottle.container.datepicker_tooltips,
        format: 'DD/MM/YYYY',
        focusOnShow: false,
        showClose: true,
        showTodayButton: true
    });


    $form
        .on('click', '.novo:submit', function () {

            $.ajax({
                url: '/cadastro/paciente',
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
                url: '/cadastro/paciente/' + $form.data('paciente_id'),
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
            url: '/cadastro/paciente/' + $form.data('paciente_id'),
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

        window.location.href = "/cadastro/paciente";
    });


    $(':submit').on('click', function (e) {

        e.preventDefault();

        if (!bottle.container.valida_form($form))
        {
            e.stopPropagation();
        }
    });

});