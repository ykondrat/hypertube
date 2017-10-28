$(document).ready(function(){
    if ($('#lan')[0].innerText != 'Main page ') {
        $('#ua').addClass('checked');
        $('#en').removeClass('checked');
    } else {
        $('#en').addClass('checked');
        $('#ua').removeClass('checked');
    }
});

function changeLanguage(elem) {
    //console.log(elem)
    if (!$(elem).hasClass('checked')) {
        //$(".language-span").removeClass('checked');
        //$(elem).addClass('checked');
        if (elem.innerHTML == 'UA') {
            sendLanguage('ua');
            //$($('.language-span')[1]).removeClass('checked');
        } else {
            sendLanguage('en');
            //$($('.language-span')[0]).removeClass('checked');
        }
    }
}


function sendLanguage(lan) {
    $.ajax({
        url: 'http://localhost:8080/hypertube/web/function/language',
        type: 'post',
        data: {language: lan},
        success: function(response) {
            if (response === 'ok'){
                location.reload()
            }
        }
    });
}

$('#sendcomment').on('click', function(){
    if ($('#comment_text').val().trim()) {
        let string = replaceString($('#comment_text').val())
        $.ajax({
            url: 'addcomment',
            type: 'post',
            data: {Text: string, imdbID: $('#imdbID').text()},
            dataType: 'json',
            success: function (response) {

            }
        });
        $('#comment_text').val('');
        $('#comments').addClass('active');
        $('#add').removeClass('active');
        $('.nav-item a[href="#comments"]').addClass('active');
        $('.nav-item a[href="#add"]').removeClass('active');

        $.pjax.reload({container: '#some_pjax_id', async: false});
    }
});

function replaceString(str) {
    let left = /</gi;
    let right = />/gi;

    let newString = str.replace(left, '&lt;');
    newString = newString.replace(right, '&gt;');

    return (newString);
}

setInterval(() => {
    $.pjax.reload({ container: '#some_pjax_id', async: false });
}, 1000);

function sendTorrent(film) {
    $.ajax({
        url: 'send_to_node',
        type: 'post',
        data: { film_data: film },
        dataType: 'json',
        success: function (response) {
            if (response === 'OK') {
                location.href = 'http://localhost:3000';
            }
        }
    })
}