$('.btn-logout').on('click', function (ev) {

    ev.preventDefault();

    var btn = $(this);

    $.ajax({
        url: btn.data('action'),
        success: function () {
            window.location.href = btn.attr('href');
        }
    });
});