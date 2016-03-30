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

                function hours(secondsTime){
                    var seconds = secondsTime;
                    var minutes = seconds / 60;
                    var hours = minutes / 60;
                    minutes = minutes % 60;
                    return Math.trunc(hours) + 'h' + Math.trunc(minutes)
                }

                for (var i in data.films) {
                    film = tableBody.append(
                        '<tr><td>' + data.films[i].title + '</td><br><br>' +
                        '<td>' + data.films[i].first_name+' '+data.films[i].last_name + '</td><br><br>' +
                        '<td>' + data.films[i].gender + '</td><br><br>' +
                        '<td>' + data.films[i].year + '</td><br><br>' +
                        '<td>' + data.films[i].synopsis + '</td><br><br>' +
                        '<td>' + hours(data.films[i].duration) + '</td><br><br></tr>'
                    );
                }
            },
            error: function (data, status, error) {
                var showErrors = '';
                data = JSON.parse(data.responseText);
                for (var d in data.errors) {
                    showErrors += d + ' :' + data.errors[d] + '<br>';
                }
            }
        });
        return false;
    });
});