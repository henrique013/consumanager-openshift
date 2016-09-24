$(function () {

    window.bottle = new Bottle();


    // ============================================================== //
    // *************************** VALUES *************************** //
    // ============================================================== //

    bottle.value('datepicker_tooltips', {
        today: 'Ir para Hoje',
        clear: 'Limpar Seleção',
        close: 'Fechar',
        selectMonth: 'Selecionar Mês',
        prevMonth: 'Mês Anterior',
        nextMonth: 'Próximo Mês',
        selectYear: 'Selecionar Ano',
        prevYear: 'Ano Anterior',
        nextYear: 'Próximo Ano',
        selectDecade: 'Selecionar Década',
        prevDecade: 'Década Anterior',
        nextDecade: 'Próxima Década',
        prevCentury: 'Século Anterior',
        nextCentury: 'Próximo Século'
    });


    bottle.value('modals', {
        "sucesso": $('#modal-sucesso').remove(),
        "atencao": $('#modal-atencao').remove(),
        "erro": $('#modal-erro').remove()
    });


    // ============================================================== //
    // ************************** SERVICES ************************** //
    // ============================================================== //

    bottle.service('mask_telefone', function () {

        return function (val) {
            return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
        };
    });


    bottle.service('valida_form', function () {

        return function ($form) {

            $form
                .find('.form-group')
                .removeClass('has-error')
            ;

            $form
                .find(':input')
                .each(function () {

                    var $input = $(this);
                    var val = $input.val();

                    if (_.isNull(val) || _.isUndefined(val)) val = '';

                    $input.val(val.trim());
                })
            ;

            var $invalids = $form.find(':invalid');

            $invalids
                .closest('.form-group')
                .addClass('has-error')
            ;

            return ($invalids.length === 0);
        };
    });

});