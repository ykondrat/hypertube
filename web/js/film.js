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
