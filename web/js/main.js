let searchValue;
let limit = 1;
let genre = "All";

$(document).ready(function(){
    $.ajax({
        url: 'main/get_look_for',
        type: 'post',
        dataType: 'json',
        success: function (response) {
            if (response[2].searchValue != "") {
                $('#search-film').val(response[2].searchValue);
                searchValue = response[2].searchValue;
                limit = parseInt(response[0].limit);
                openPrev();
                $.ajax({
                    url: 'main/returntitle',
                    type: 'post',
                    data: {searchValue: searchValue,limit: limit - 1},
                    dataType: 'json',
                    success: function(response){
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
                })
            } else {
                genre = response[1].genre;
                limit = parseInt(response[0].limit);
                openPrev();
                $.ajax({
                    url: 'main/returngenre',
                    type: 'post',
                    data: { Genre: response[1].genre, limit: parseInt(response[0].limit) - 1},
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
        }
    });
});

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
    $("#search-nav").css('width', '250px');
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

function sendData(genre, search, limit) {
    $.ajax({
        url: 'main/send_look_for',
        type: 'post',
        data: {genre: genre, searchValue: search,limit: limit},
        dataType: 'json'
    });
}

$('#search-film').on('keyup', function(event){
    if (event.keyCode == 13) {
        $('#movies div').remove();
        searchValue = this.value;
        limit = 1;
        openPrev();
        sendData('', this.value, limit);
        $.ajax({
            url: 'main/returntitle',
            type: 'post',
            data: {searchValue: searchValue,limit: limit - 1},
            dataType: 'json',
            success: function(response){
                console.log(response);
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
        })
    }
});

$('.next').on('click', function(){
    if (searchValue) {
        limit++;
        sendData('', searchValue, limit);
        openPrev();
        $('#movies div').remove();
        $.ajax({
            url: 'main/returntitle',
            type: 'post',
            data: {searchValue: searchValue,limit: limit - 1},
            dataType: 'json',
            success: function(response){
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
        })
    } else {
        $('#movies div').remove();
        limit++;
        sendData(genre, '', limit);
        openPrev();
        $.ajax({
            url: 'main/returngenre',
            type: 'post',
            data: {Genre: genre, limit: limit - 1},
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
});

$('.prev').on('click', function(){
    if (searchValue) {
        $('#movies div').remove();
        if (limit > 1) {
            limit--;
            sendData('', searchValue, limit);
            openPrev();
            $.ajax({
                url: 'main/returngenre',
                type: 'post',
                data: {Genre: genre, limit: limit - 1},
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
    } else {
        $('.next').css('display', 'block');
        if (limit > 1) {
            $('#movies div').remove();
            limit--;
            sendData(genre, '', limit);
            openPrev();
            $.ajax({
                url: 'main/returngenre',
                type: 'post',
                data: {Genre: genre, limit: limit - 1},
                dataType: 'json',
                success: function (response) {
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
    }
});

function setGenre(Genre) {
    $('#movies div').remove();
    $('.next').css('display', 'block');
    limit = 1;
    genre = Genre.innerHTML;
    sendData(genre, '', limit);
    $('#search-film').val('');

    $.ajax({
        url: 'main/returngenre',
        type: 'post',
        data: {Genre: Genre.innerHTML, limit: limit - 1},
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
