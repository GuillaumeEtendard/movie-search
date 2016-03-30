$(function () {
    $('form').submit(function () {
        $.ajax({
            url: 'index/search',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',

            success: function (data) {
                var film = '';
                var tableBody = $('tbody');
                var filmTable = $('#filmTable');
                tableBody.html(filmTable);

                for (var i in data.films) {
                    S = data.films[i].duration;
                    M = S / 60;
                    S = S % 60;
                    H = M / 60;
                    M = M % 60;

                    film = tableBody.append(
                        '<tr><td>' + data.films[i].title + '</td><br><br>' +
                        '<td>' + data.films[i].first_name+' '+data.films[i].last_name + '</td><br><br>' +
                        '<td>' + data.films[i].gender + '</td><br><br>' +
                        '<td>' + data.films[i].year + '</td><br><br>' +
                        '<td>' + data.films[i].synopsis + '</td><br><br>' +
                        '<td>' + Math.trunc(H) + 'h' + Math.trunc(M) + '</td><br><br></tr>'
                    );

                }

            },

            error: function (data, status, error) {
                var showErrors = '';
                data = JSON.parse(data.responseText);
                for (var d in data.errors) {
                    showErrors += d + ' :' + data.errors[d] + '<br>';
                }
                $('#errorBlock').html(showErrors);
            }
        });
        return false;

    });

});