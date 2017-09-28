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