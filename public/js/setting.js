$(function() {
    $('.bootstrap-switch-input').each(function() {
        var me = $(this);
        var isYesNoText = me.attr('name').indexOf('receive') !== -1;
        var onText = isYesNoText ? textYes : textShow;
        var offText = isYesNoText ? textNo : textHide;
        me.bootstrapSwitch({
            size: 'mini',
            offColor: 'warning',
            onText: onText,
            offText: offText
        });
    });

    $('#add-language').on('click', function (){
        var n = $('.language-element').length;
        if (n <= maxLanguages) {
            $('.skills').append(aLanguage).children(':last').animate({
                opacity: 1
            }, 1);
        }

        var lang = $('.skills li');
        var list = $('.skills li').find('select');
        var selectbefore = $(lang[lang.length - 2]).find('select');
        var selectObj = lang.last().find('select');
        var size = selectbefore.find('option').size();
        var idx = selectbefore.find(':selected').index();
        var sPosition = idx + 1;
        if (idx == size) {
            sPosition = size;
        }
        selectObj.prop('selectedIndex',sPosition);
    });
    
    $('.skills').on('click', '.remove-language', function () {
        $(this).closest('.skill-element').animate({
            opacity: 0
        }, 1).delay(500).queue(function(nxt) {
            $(this).remove();
        });
    });
});
