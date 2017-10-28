$(document).ready(function(){
    if ($('#tab-1')[0].labels[0].innerHTML != 'Sign In') {
        $('#ua').addClass('checked');
        $('#en').removeClass('checked');
    } else {
        $('#en').addClass('checked');
        $('#ua').removeClass('checked');
    }
});

function changeLanguage(elem) {
    if (!$(elem).hasClass('checked')) {
        if (elem.innerHTML == 'UA') {
            sendLanguage('ua');
        } else {
            sendLanguage('en');
        }
    }
}

function sendLanguage(lan) {
    $.ajax({
        url: 'function/language',
        type: 'post',
        data: {language: lan},
        success: function(response) {
            if (response === 'ok'){
                location.reload()
            }
        }
    });
}