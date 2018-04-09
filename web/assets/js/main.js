$(document).ready(function () {
    var login;

    $('#search-go').on('click', function () {
        $('#search-results').html('');
        $('#search-error>h2').html('');
        login = $('#search-field').val();
        if (login != '') {
            $.ajax({
                url: '/?r=vacation/manage',
                type: 'POST',
                data: {'login': login},
                success: function (res) {
                    $('#search-results').html(res);
                },
                error: function () {
                    location.reload();
                }
            });
        }
    });
    $('#search-results').on('click', '.btn-manage', function () {
        var status = $(this).data('status');
        var vacation_id = $(this).data('id');
        $.ajax({
            url: '/?r=vacation/update',
            type: 'POST',
            data: {'id': vacation_id, 'status': status, 'login': login},
            success: function (res) {
                $('#search-results').html(res);
            },
            error: function () {
                location.reload();
            }
        });
    });

    $('#vacations-statuses button.btn-manage').on('click', function () {
        var row = $(this).parent().parent();
        var vacation_id = $(this).data('id');
        $.ajax({
            url: '/?r=vacation/delete',
            type: 'POST',
            data: {'id': vacation_id},
            success: function (res) {
                row.remove();
            },
            error: function () {
                location.reload();
            }
        });
    });

    $('#logout-link').click(function () {
        document.logout_form.submit();
        return false;
    });
});