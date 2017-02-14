$(document).ready(function () {
    $('#viewMovie').click(function () {
        $.ajax({
            url: $('#viewMovie').data('path'),
            type: 'POST',
            data: {movie: $('#viewMovie').data('movie'), user: $('#viewMovie').data('user')},
            success: function (data) {
                if (data == 'active') {
                    $('#viewMovie').attr('class', 'rm addM isSel');
                } else if (data == 'sp_active') {
                    $('#viewMovie').attr('class', 'rm addM isSel');
                    $('#wishMovie').attr('class', 'rm addM');
                } else {
                    $('#viewMovie').attr('class', 'rm addM');
                }

            }
        });
    });
    $('#wishMovie').click(function () {
        $.ajax({
            url: $('#wishMovie').data('path'),
            type: 'POST',
            data: {movie: $('#wishMovie').data('movie'), user: $('#wishMovie').data('user')},
            success: function (data) {
                if (data == 'active') {
                    $('#wishMovie').attr('class', 'rm addM isSel');
                } else {
                    $('#wishMovie').attr('class', 'rm addM');
                }
            }
        });
    });
});