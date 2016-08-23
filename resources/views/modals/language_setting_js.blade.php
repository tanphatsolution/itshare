<script type="text/javascript">
    function requestToServer(element, form, typeHidden) {
        $.ajax({
            type: 'POST',
            url: baseURL + '/languages/settings/filterPostLanguages',
            data: form.append(typeHidden).serialize(),
            success: function(response) {
                if (response.error) {
                    form.parents('.language-box')
                        .find('.alert-info')
                        .addClass('alert-danger')
                        .empty()
                        .append('{{ trans("messages.user.fail") }}');
                } else {
                    form.parents('.language-box')
                        .find('.alert-info')
                        .addClass('alert-success')
                        .empty()
                        .append('{{ trans("messages.user.success") }}');
                    form.find('.languages').empty().append(response.html);
                }
            },
            error: function(response) {
                form.parents('.language-box')
                    .find('.alert-info')
                    .addClass('alert-danger')
                    .empty()
                    .append('{{ trans("messages.user.fail") }}');
            },
        });
    }

    function callAjax(element, lang, type, url, btnClass, thisBtn) {
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                lang: lang,
                type: type,
            },
            success: function(response) {
                if (response.error) {
                    element.find('.alert-info')
                           .addClass('alert-danger')
                           .empty()
                           .append('{{ trans("messages.user.fail") }}');
                } else {
                    element.find('.alert-info')
                           .addClass('alert-success')
                           .empty()
                           .append('{{ trans("messages.user.success") }}');
                }
                if ((btnClass == 'btn btn-black btn-sm') || (btnClass == 'btn btn-black btn-sm original-btn-black')) {
                    thisBtn.addClass('original-btn-black');
                }
            },
            error: function(response) {
                element.find('.alert-info')
                       .addClass('alert-danger')
                       .empty()
                       .append('{{ trans("messages.user.fail") }}');
                if ((btnClass == 'btn btn-black btn-sm') || (btnClass == 'btn btn-black btn-sm original-btn-black')) {
                    thisBtn.addClass('original-btn-black');
                }
            },
        });
    }
</script>
