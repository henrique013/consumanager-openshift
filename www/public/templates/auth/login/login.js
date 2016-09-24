$(function () {

    $('#form-login').submit(function (e) {

        e.preventDefault();

        var $form = $(this);
        var $box = $('.box').removeClass('fail');

        $.ajax({
            url: '/auth/login',
            method: 'POST',
            data: $form.serialize(),
            success: function () {
                window.location.href = '/agenda';
            },
            error: function () {
                $box.addClass('fail');
            }
        });
    });
});
