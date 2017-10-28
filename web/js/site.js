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