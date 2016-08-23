$(function() {
    $('.professionals, .url-professionals').on('click', '.remove-professional', function() {
        $(this).closest('.professional-element').animate({
            opacity: 0
        }, 1).delay(500).queue(function(nxt) {
            $(this).remove();
        });
        var removedProfessionalId = $(this).data('professional-id');
        if (typeof removedProfessionalId !== 'undefined') {
            $('.professional-removed-id').append('<input type="hidden" value="' + removedProfessionalId + '" name="removeMonthlyProfessionalId[]">');
        }
    });

    $('.themes-this-month, .all-themes').on('click', '.remove-theme', function() {
        $(this).closest('.theme-element').animate({
            opacity: 0
        }, 1).delay(500).queue(function(nxt) {
            $(this).remove();
        });
        var removedThemeEnId = $(this).data('theme-en-id');
        var removedThemeViId = $(this).data('theme-vi-id');
        var removedMonthlyThemeId = $(this).data('monthly-theme-id');
        if (typeof removedThemeEnId !== 'undefined') {
            $('.theme-removed-id').append('<input type="hidden" value="' + removedThemeEnId + '" name="removedMonthlyThemeId[en][]">');
            $('.theme-removed-id').append('<input type="hidden" value="' + removedThemeViId + '" name="removedMonthlyThemeId[vi][]">');
            $('.monthly-theme-removed-id').append('<input type="hidden" value="' + removedMonthlyThemeId + '" name="removedMonthlyThemesId[]">');
        }
    });
});
