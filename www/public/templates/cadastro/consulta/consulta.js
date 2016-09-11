$(function () {

    $('.input-group.date').datetimepicker({
        locale: 'pt-br',
        tooltips: bottle.container.datepicker_tooltips,
        format: 'DD/MM/YYYY',
        focusOnShow: false,
        showClose: true,
        showTodayButton: true
    });


    $('.typeahead').typeahead({
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

            $(':hidden[name="paciente"]').val(pac.id);

            $('.pac-nome').text(pac.nome);
            $('.pac-endereco').text(pac.endereco);
            $('.pac-telefones').text(pac.telefones);
        }
    });

});