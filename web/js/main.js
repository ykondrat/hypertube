let searchValue;
let limit = 1;
let genre = 'all';

$(document).ready(function(){
    console.log('take film from server');
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

// function openFilm(elem) {
//     $.ajax({
//         type: 'POST',
//         url: 'localhost',
//         dataType: 'json',
//         data: {id: elem.dataset.id}
//         success: function(response) {
//             if (response == 'Error') {
//                 $.ajax({
//                     type: 'GET',
//                     url: 'http://www.omdbapi.com/?apikey=8f911d74&i=' + elem.dataset.id,
//                     dataType: 'json',
//                     success: function(response) {
//                         $.ajax({
//                             type: 'POST',
//                             url: 'localhost',
//                             dataType: 'json',
//                             data: response
//                         });
//                     }
//                 });
//             }
//         }
//     });
// }

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

$('#search-film').on('keyup', function(event){
    if (event.keyCode == 13) {
        $('#movies div').remove();
        searchValue = this.value;
        getData('http://www.omdbapi.com/?apikey=8f911d74&type=movie&s=' + ﻿this.value)
    .then(movies => {
            if (movies) {
                if (movies.length < 10) {
                    $('.next').css('display', 'none');
                } else {
                    $('.next').css('display', 'flex');
                }
                movies.forEach(movie => {
                    if (movie) {
                        addMovie(movie);
                    }
                })
            }
        })
    .catch(error => console.error(error))
    }
});

$('.next').on('click', function(){
    if (searchValue) {
        limit++;
        openPrev();
        $('#movies div').remove();
        getData('http://www.omdbapi.com/?apikey=8f911d74&type=movie&s=' + ﻿searchValue + '&page=' + limit)
    .then(movies => {
            if (movies) {
                let counter = 0;
                movies.forEach(movie => {
                    if (movie) {
                        addMovie(movie);
                    }
                    if (movie.Poster == 'N/A') {
                    counter++;
                }
            })
                if (counter > 8) {
                    $('.next').css('display', 'none');
                }
            }
        })
    .catch(error => console.error(error))
    }
});

$('.prev').on('click', function(){
    if (searchValue) {
        $('#movies div').remove();
        if (limit > 1) {
            limit--;
            openPrev();
            getData('http://www.omdbapi.com/?apikey=8f911d74&type=movie&s=' + ﻿searchValue + '&page=' + limit)
        .then(movies => {
                if (movies) {
                    $('.next').css('display', 'flex');
                    movies.forEach(movie => {
                        if (movie) {
                            addMovie(movie);
                        }
                    })
                }
            })
        .catch(error => console.error(error))
        }
    }
});



