let searchValue = '';
let limit = 1;
let sort_value = '';
let filter_value  = '';
let genre = "All";

$(document).ready(function(){
    $.ajax({
        url: 'main/get_look_for',
        type: 'post',
        dataType: 'json',
        success: function (response) {
            console.log(response);

            if (response[2].searchValue != "") {
                $('#search-film').val(response[2].searchValue);
                searchValue = response[2].searchValue;
                limit = parseInt(response[0].limit);
                sort_value = response[3].sort_value;
                filter_value = response[4].filter_value;

                openPrev();
                var data = {
                    searchValue: searchValue,
                    limit: limit - 1,
                    sort_value: sort_value,
                    filter_value: filter_value
                };
                sendDataAndGet('main/sort_filter', data);
            } else {
                genre = response[1].genre;
                limit = parseInt(response[0].limit);
                sort_value = response[3].sort_value;
                filter_value = response[4].filter_value;

                openPrev();
                var data = {
                    Genre: response[1].genre,
                    limit: parseInt(response[0].limit) - 1,
                    sort_value: sort_value,
                    filter_value: filter_value
                };
                sendDataAndGet('main/sort_filter', data);
            }
        }
    });
});

function sendData(genre, search, limit, sort_value, filter_value) {
    $.ajax({
        url: 'main/send_look_for',
        type: 'post',
        data: {
            genre: genre,
            searchValue: search,
            limit: limit,
            sort_value: sort_value,
            filter_value: filter_value
        }
    });
}

$('#search-film').on('keyup', function(event){
    if (event.keyCode == 13) {
        $('#movies div').remove();
        searchValue = this.value;
        limit = 1;
        sort_value = '';
        filter_value = '';
        openPrev();
        sendData('', this.value, limit, sort_value, filter_value);
        var data = {
            searchValue: searchValue,
            limit: limit - 1,
            sort_value: sort_value,
            filter_value: filter_value
        };
        sendDataAndGet('main/sort_filter', data);
    }
});

$('.next').on('click', function(){
    if (searchValue) {
        limit++;
        sendData('', searchValue, limit, sort_value, filter_value);
        openPrev();
        $('#movies div').remove();
        var data = {
            Genre: genre,
            limit: limit - 1,
            sort_value: sort_value,
            filter_value: filter_value
        };
        sendDataAndGet('main/sort_filter', data);
    } else {
        $('#movies div').remove();
        limit++;
        sendData(genre, '', limit, sort_value, filter_value);
        openPrev();
        var data = {
            Genre: genre,
            limit: limit - 1,
            sort_value: sort_value,
            filter_value: filter_value
        };
        sendDataAndGet('main/sort_filter', data);
    }
});

$('.prev').on('click', function(){
    if (searchValue) {
        $('#movies div').remove();

        if (limit > 1) {
            limit--;
            sendData('', searchValue, limit, sort_value, filter_value);
            openPrev();
            var data = {
                Genre: genre,
                limit: limit - 1,
                sort_value: sort_value,
                filter_value: filter_value
            };
            sendDataAndGet('main/sort_filter', data);
        }
    } else {
        $('.next').css('display', 'block');
        if (limit > 1) {
            $('#movies div').remove();
            limit--;
            sendData(genre, '', limit, sort_value, filter_value);
            openPrev();
            var data = {
                Genre: genre,
                limit: limit - 1,
                sort_value: sort_value,
                filter_value: filter_value
            };
            sendDataAndGet('main/sort_filter', data);
        }
    }
});

function setGenre(Genre) {
    $('#movies div').remove();
    $('.next').css('display', 'block');
    sort_value = '';
    filter_value = '';
    limit = 1;
    genre = Genre.innerHTML;
    sendData(genre, '', limit, sort_value, filter_value);
    $('#search-film').val('');
    // console.log(Genre.innerHTML)
    var data = {
        Genre: Genre.innerHTML,
        limit: limit - 1,
        sort_value: sort_value,
        filter_value: filter_value
    };
    sendDataAndGet('main/sort_filter', data);

}


/** Sort films */

function setSort(str){
    sort_value = str;
    sendData(genre, searchValue, limit, sort_value, filter_value);
    var data = {
        sort: str,
        limit: limit - 1,
        filter: ''
    };
    $('#movies div').remove();
    sendDataAndGet('main/sort_filter', data);
}

/** End of sort */

/** Filter films */

function setFilter() {
    let year = $('#w1-slider .tooltip-inner')[0].innerHTML.split(' : ');
    let reating = $('#w2-slider .tooltip-inner')[0].innerHTML.split(' : ');

    filter_value = 'Year,' + year[0] + ',' + year[1] + ',imdbRating,' + reating[0] + ',' + reating[1];
    sendData(genre, searchValue, limit, sort_value, filter_value);
    var data = {
        limit: limit - 1,
        sort: sort_value,
        filter: filter_value
    };
    $('#movies div').remove();
    sendDataAndGet('main/sort_filter', data);
}



/** Sender function */

function sendDataAndGet(url, data) {
    $.ajax({
        url: url,
        type: 'post',
        data: data,
        dataType: 'json',
        success: function(response) {
            let end = response[response.length - 1];

            if (end == "END") {
                $('.next').css('display', 'none');
            } else {
                $('.next').css('display', 'block');
            }
            let movies = response.slice(0, response.length - 1);
            movies.forEach(movie => {
                addMovie(movie);
            });
        }
    });
}

/** End of Sender */


/** Support function */

$('.btn-edit').on('click', function(){
    $('input').each(function(){
        $(this).removeAttr('readonly');
    });
    $('.btn-save').css('visibility', 'visible');
});

$('.btn-save').on('click', function() {
    let input = $('input')
    for (let i = 0; i < 6; i++) {
        $(input[i]).attr('readonly', true);
    }
    $('.btn-save').attr('visibility', 'hidden');
});

function openFilm(elem) {
    location.href = 'http://localhost:8080/hypertube/web/film/film_page?id=' + elem.dataset.id;
}

function openPrev() {
    if (limit > 1) {
        $('.prev').css('display', 'block');
    } else {
        $('.prev').css('display', 'none');
    }
}

function openNav() {
    $("#search-nav").css('width', '285px');
}

function closeNav() {
    $("#search-nav").css('width', '0');
}

function changeLanguage(elem) {
    if (!$(elem).hasClass('checked')) {
        $(elem).addClass('checked');
        if (elem.innerHTML == 'UA') {
            $($('.language-span')[1]).removeClass('checked');
        } else {
            $($('.language-span')[0]).removeClass('checked');
        }
    }
}

function addMovie(movie){
    if (movie) {
        if (movie.Poster && movie.Poster != 'N/A') {
            $(`
                <div class="col-sm-6 col-md-4 col-lg-4 col-xl-3" data-id=${movie.imdbID} onclick='openFilm(this)'>
                    <img src=${movie.Poster} alt=${movie.Title} class="img-fluid">
                </div>
            `).appendTo($('#movies'));
        }
    }
}

function getData(url) {
    return new Promise(function(resolve, reject) {
        $.ajax({
            type: 'GET',
            url: url,
            dataType: 'json',
            success: function(response) {
                resolve(response.Search);
            },
            error: function(error){
                reject(error);
            }
        });
    });
}