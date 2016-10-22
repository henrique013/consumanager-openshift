$(function () {

    $('body')
        .on('shown.bs.modal', '#modal-paciente', function () {

            // inicializa o modal de paciente

            var $modal = $(this);


            $modal.find('#ipt-pac-nascimento').mask('00/00/0000', {
                clearIfNotMatch: true,
                onKeyPress: function (val, event, field) {
                    // evita que o usuário insira manualmente a data
                    $(field).val('');
                }
            });


            bottle.container.mask_telefone($modal.find('#ipt-pac-telefone'));
            bottle.container.mask_telefone($modal.find('#ipt-pac-telefone2'));


            $modal.find('.input-group.date').datetimepicker({
                locale: 'pt-br',
                tooltips: bottle.container.datepicker_tooltips,
                format: 'DD/MM/YYYY',
                focusOnShow: false,
                showClose: true,
                showTodayButton: true
            });
        })
        .on('shown.bs.modal', '#modal-resp-consulta', function () {

            // inicializa o modal de responsável consulta

            var $modal = $(this);

            bottle.container.mask_telefone($modal.find('#ipt-resp-telefone'));
        })
        .on('shown.bs.modal', '#modal-consulta', function () {

            // inicializa o modal de consulta


            var $modal = $(this);


            // campo auto-complete de PACIENTE
            $modal.find('#ipt-pac-nome').typeahead({
                items: 10,
                ajax: {
                    url: '/ajax/cadastro/consulta/pacientes',
                    timeout: 300,
                    triggerLength: 3,
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


            // campo auto-complete de ALUNO RESPONSÁVEL
            $modal.find('#ipt-resp-nome').typeahead({
                items: 10,
                ajax: {
                    url: '/ajax/cadastro/consulta/responsaveis',
                    timeout: 300,
                    triggerLength: 3,
                    method: "get",
                    preDispatch: function (query) {
                        return {
                            nome: query
                        }
                    }
                },
                onSelect: function (item) {

                    var resp = JSON.parse(item.value);

                    $('#ipt-resp-id').val(resp.id);

                    $('.resp-nome').text(resp.nome);
                    $('.resp-telefones').text(resp.telefones);
                }
            });
        })
        .on('hidden.bs.modal', '.modal', function () {

            // destroi o modal completamente ao fechá-lo

            $(this).remove();
        })
        .on('click', '#modal-consulta .novo, #modal-consulta .atualizar', function () {

            // valida campos especiais no formulário de consulta

            var $form = $(this).closest('.modal').find('form');

            if (!$form.find('#ipt-pac-id').val())
            {
                $form.find('#ipt-pac-nome').val('');
            }
        })
        .on('click', '#modal-usuario .novo, #modal-usuario .atualizar', function () {

            // valida campos especiais no formulário de usuário

            var $form = $(this).closest('.modal').find('form');
            var $s1 = $form.find('#usr_senha');
            var $s2 = $form.find('#usr_senha2');

            if ($s1.val() !== $s2.val())
            {
                $s1.val('');
                $s2.val('');
            }
        })
        .on('click', '.modal .novo, .modal .atualizar', function () {

            // valida o formulário e faz um POST ou PUT

            var $btn = $(this);
            var $form = $btn.closest('.modal').find('form');
            if (!bottle.container.valida_form($form))
            {
                return false;
            }

            var action = $btn.data('action');
            var method = $btn.hasClass('novo') ? 'POST' : 'PUT';
            $.ajax({
                url: action,
                method: method,
                data: $form.serialize(),
                success: function () {
                    bottle.container.modals['sucesso'].modal('show');
                },
                error: function () {
                    bottle.container.modals['erro'].modal('show');
                },
                complete: function () {
                    $btn.closest('.modal').modal('hide');
                }
            });
        })
        .on('click', '.modal .remover', function () {

            // exibe o 'modal de atenção' quando o usuário tenta fazer uma deleção

            var $btn = $(this);
            var action = $btn.data('action');

            sessionStorage.setItem('modal_remove_action', action);

            $btn.closest('.modal').modal('hide');
            bottle.container.modals['atencao'].modal('show');
        })
        .on('click', '#modal-atencao .continuar', function () {

            // faz um DELETE quando o usuário clica em 'continuar' no 'modal de atenção'

            var action = sessionStorage.getItem('modal_remove_action');
            $.ajax({
                url: action,
                method: 'DELETE',
                success: function () {
                    bottle.container.modals['sucesso'].modal('show');
                },
                error: function () {
                    bottle.container.modals['erro'].modal('show');
                }
            });
        })
    ;


    // ao clicar para abrir um modail de cadastro (pessoa, usuário, ...)
    $('.dropdown-menu.cadastro').on('click', 'a', function (e) {
        e.preventDefault();
        var target = $(this).data('target');
        $.get(target, function (html) {
            $(html).modal('show');
        });
    });


    // ao clicar para fazer o logout do sistema
    $('.btn-logout').on('click', function () {
        var btn = $(this);
        $.ajax({
            url: btn.data('action'),
            success: function () {
                window.location.href = btn.attr('href');
            }
        });
    });

});
