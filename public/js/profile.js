$(function(){
    var flag = true;
    $('#add-skill').on('click', function (){
        var n = $('.skill-element').length;
        if (n <= maxSkillNumber) {
            $('.skills').append(aSkill).children(':last').animate({
                opacity: 1
            }, 1);
            autoCompleteSkills();
        }
    });
    $('.user-skill-panel').on('click', '.remove-skill', function (){
        var cointainer = $(this).closest('.fader');
        var skillId = cointainer.attr('id');
        if(skillId == undefined){
            $(this).closest('.skill-element').animate({
                opacity: 0
            }, 1).delay(500).queue(function(nxt) {
                $(this).remove();
            });
        } else {
            cointainer.remove();
            $('<input>').attr({
                type: 'hidden',
                name: 'removedSkills[]',
                value: skillId.substr(5)
            }).appendTo('form');
        }
    });
    if ($.isFunction($.fn.dropzone)) {
        var avatarUploader = $('#avatar-uploader');
        avatarUploader.dropzone({
            url: baseURL + '/images/upload',
            maxFilesize: MAX_IMAGE_SIZE,
            paramName: 'image',
            acceptedFiles: 'image/*',
            headers: {'X-CSRF-Token': $('meta[name="_token"]').attr('content')},
            sending: function(file, xhr, formdata) {
                formdata.append('username', avatarUploader.attr('alt'));
            },
            init: function() {
                this.on('success', function(file, message) {
                    window.location.replace(baseURL + '/profiles/update');
                });
                this.on('error', function(file, response) {
                    var errorMessage = response;
                    if (typeof (response.status) !== 'undefined' && response.status === 'error') {
                        errorMessage = response.message;
                    }
                    swal(errorMessage);
                });
            }
        });
    }
    $('#avatar-delete').on('click', function() {
        var url = $(this).data('url');
        var message = $(this).data('message');
        var title = $(this).data('labels');
        var confirmDelete = $(this).data('delete');
        var success = $(this).data('success');
        var fail = $(this).data('fail');
        var buttonOk = $(this).data('ok');
        swal({
            title: title,
            text: message,
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: 'btn-confirm',
            confirmButtonText: confirmDelete,
            cancelButtonText: noBtn,
            closeOnConfirm: false
        },
        function() {
            $.ajax ({
                type: 'POST',
                url: url,
                success: function(response) {
                    swal({
                        title: success,
                        text: response.message,
                        type: "success",
                        confirmButtonClass: "btn-success",
                        confirmButtonText: buttonOk
                    },
                    function() {
                        window.location.replace(baseURL + '/profiles/update');
                    });
                },
                error: function(response) {
                    var res = response.responseJSON;
                    swal(fail, res.message, "error");
                }
            });
        });
    });

    $('.user-skill-panel').on('click', '.add-sub-skill', function (){
        var element = $(this).find('i');
        var categoryId = $(this).attr('id');
        $(this).addClass('remove-sub-skill');
        $(this).removeClass('add-sub-skill');
        element.addClass('fa-minus');
        element.removeClass('fa-plus');
        if(categoryId == undefined){
            $(this).closest('.skill-cointainer').find('.skill-list-cointainer').removeClass('hidden');
        }
        $('#skillListCointainer' + categoryId).removeClass('hidden');
    });

    $('.user-skill-panel').on('click', '.remove-sub-skill', function (){
        var element = $(this).find('i');
        var categoryId = $(this).attr('id');
        $(this).removeClass('remove-sub-skill');
        $(this).addClass('add-sub-skill');
        element.removeClass('fa-minus');
        element.addClass('fa-plus');
        if(categoryId == undefined){
            $(this).closest('.skill-cointainer').find('.skill-list-cointainer').addClass('hidden');
        }
        $('#skillListCointainer' + categoryId).addClass('hidden');
    });

    $('.user-skill-panel').on('click', '.add-skill-category', function (){
        var cnt = $('.skill-cointainer').length;
        $.ajax ({
            type: 'POST',
            url: baseURL + '/user_skills',
            data: { type : 'category', cnt : cnt},
            success: function(response) {
                if (response != 'false') {
                    var element = $('.user-skill-panel .skill-cointainer:last').before(response);
                }
            },
            error: function(response) {
                var res = response.responseJSON;
                swal(fail, res.message, "error");
            }
        })
    });

    $('.user-skill-panel').on('click', '.add-new-sub-skill', function (){
        var cointainer = $(this).closest('.skill-cointainer');
        var categoryId = cointainer.attr('id');
        var cnt = $('.skill-cointainer').length -1;
        if(categoryId == undefined){
            $.ajax ({
                type: 'POST',
                url: baseURL + '/user_skills',
                data: { type : 'skill', cnt : cnt},
                success: function(response) {
                    if (response != 'false') {
                        cointainer.find('.skill-list').append(response);
                    }
                },
                error: function(response) {
                    var res = response.responseJSON;
                    swal(fail, res.message, "error");
                }
            })
        } else {
            $.ajax ({
                type: 'POST',
                url: baseURL + '/user_skills',
                data: { type : 'categorySkill', categoryId : categoryId.substr(8)},
                success: function(response) {
                    if (response != 'false') {
                        cointainer.find('.skill-list').append(response);
                    }
                },
                error: function(response) {
                    var res = response.responseJSON;
                    swal(fail, res.message, "error");
                }
            })
        }
    });

    $('.user-skill-panel').on('click', '.remove-skill-category', function (){
        var categoryId = $(this).attr('id');
        var element = $(this).closest('.skill-cointainer');
        var alertMessage = $(this).data('message');
        var alertTitle = $(this).data('labels');
        var confirmText = $(this).data('confirm');
        swal({
            title: alertTitle,
            text: alertMessage,
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: 'btn-confirm',
            confirmButtonText: confirmText,
            closeOnConfirm: true
        },
        function() {
            element.remove();
            if(categoryId != undefined) {
                $('<input>').attr({
                    type: 'hidden',
                    name: 'removedCategories[]',
                    value: categoryId
                }).appendTo('form');
            }
        })
    });

    $('form.form-update-profile').submit(function (e) {
        var newEmail = $('.user-email').val();
        var newWorkEmail = $('.user-work-email').val();
        var changEmail = false;
        var changWorkEmail = false;

        if (newEmail != currentEmail) {
            changEmail = true;
        }
        if (newWorkEmail != currentWorkEmail) {
            changWorkEmail = true;
        }

        if (changEmail && !changWorkEmail) {
            e.preventDefault();
            if (newEmail == '') {
                showMsg(warningRemovePrivateEmail);
            } else {
                if (newEmail != currentWorkEmail) {
                    showMsg(warningChangeEmail);
                } else {
                    showMsg(warningChangeEmail1);
                }
            }
        }
        if (!changEmail && changWorkEmail) {
            e.preventDefault();
            if (newWorkEmail == '') {
                showMsg(warningRemoveWorkEmail);
            } else {
                if (newWorkEmail != currentEmail) {
                    showMsg(warningChangeWorkEmail);
                } else {
                    showMsg(warningChangeWorkEmail1);
                }
            }
        }
        if (changEmail && changWorkEmail) {
            e.preventDefault();
            if ((newEmail == '') && (newWorkEmail != '')) {
                if (newWorkEmail == currentEmail) {
                    showMsg(warningChangeWorkEmail1);
                } else {
                    showMsg(warningChangeWorkEmail);
                }
            } else if ((newEmail != '') && (newWorkEmail == '')) {
                if (newEmail == currentWorkEmail) {
                    showMsg(warningChangeEmail1);
                } else {
                    showMsg(warningChangeEmail);
                }
            } else if ((newEmail == '') && (newWorkEmail == '')) {
                showMsg(emptyPrivateWorkEmail);
            } else {
                showMsg(warningChangeBothEmails);
            }
        }
    });
    if (typeof(cityCountry) != "undefined" && cityCountry !== null) {
        $('.city-country').select2({
            theme: 'bootstrap',
            placeholder: cityCountry,
            ajax: {
                url: baseURL + '/profiles/getSuggestCities',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        keyword: params.term,
                    };
                },
                processResults: function (data, params) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.description,
                                id: item.place_id
                            }
                        })
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) {
                return markup;
            },
            minimumInputLength: 2
        });
    }

    $('.city-country').on('select2:select', function() {
        $('.city-country-description').val($('.select2-selection__rendered').attr('title'));
    });
    if (typeof(skills) != "undefined" && skills !== null) {
        autoCompleteSkills();
    }
});

function autoCompleteSkills() {
    $('.skill-input').typeahead({
        source: skills,
        autoSelect: true
    });
}
function showMsg(msg) {
    swal({
        title: titleConfirm,
        text: msg,
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: yesBtn,
        cancelButtonText: noBtn,
        closeOnConfirm: false,
    },
    function (isConfirm) {
        if (isConfirm) {
            $('form.form-update-profile').unbind('submit').submit();
        } else {
            return false;
        }
    });
}
