function changeLanguage(language) {
    $.ajax({
        url: 'site/index',
        type: 'post',
        data: {language:language},
    });
}