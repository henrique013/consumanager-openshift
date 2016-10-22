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
        // remove os modais do DOM e guarda eles nas variáveis abaixo:
        "sucesso": $('#modal-sucesso').remove(),
        "atencao": $('#modal-atencao').remove(),
        "erro": $('#modal-erro').remove()
    });


    // ============================================================== //
    // ************************** SERVICES ************************** //
    // ============================================================== //

    bottle.service('mask_telefone', function () {

        return function ($input) {

            $input.on('blur', function () {

                var tel = $input.val();

                // se o valor do input não bater com o padrão ele é limpo
                if (/^(\(\d\d\)\s)?\d{4,5}-\d{4}$/.test(tel) === false)
                {
                    tel = '';
                }

                $input.val(tel);
            });


            $input.on('keyup', function (e) {

                // se a tecla precionada foi um backspace ou delete
                if ([8, 46].indexOf(e.keyCode) >= 0)
                {
                    $input.keypress();
                }
            });


            $input.on('keypress', function (e) {

                var tel = $input.val() + e.key;

                // retira a máscara e deixa somente os números
                tel = tel.replace(/\D/g, '').substr(0, 11);


                var len = tel.length;
                var p1, p2, p3;


                if (len > 4 && len <= 9)
                {
                    // Máscaras:
                    // 99999-9999
                    //  9999-9999

                    p1 = tel.substr(0, len - 4);
                    p2 = tel.substr(len - 4, len);
                    tel = p1 + '-' + p2;
                }
                else if (len >= 10)
                {
                    // Máscaras:
                    // (31) 99999-9999
                    //  (31) 9999-9999

                    p1 = tel.substr(0, 2);
                    p2 = tel.substr(2, len - 6);
                    p3 = tel.substr(len - 4, len);
                    tel = '(' + p1 + ') ' + p2 + '-' + p3;
                }


                $input.val(tel);

                return false;
            });
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