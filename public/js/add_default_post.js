$(document).ready(function () {
    $('.post-body pre code').each(function () {
        if (parseInt(($(this).find('span.label:first')).size()) == 0) {
            var stringLanguage = $(this).attr('class');
            var languageName = 'Default';
            if (stringLanguage != undefined) {
                languageName = stringLanguage.slice(9, stringLanguage.length);
            }
            $(this).prepend('<span class="label label-default">' + languageName + '</span><br/>');
        }
    })
});