var coverElementIdName = 'group-cover-image-cropper',
    profileElementIdName = 'group-profile-image-cropper',
    coverPositionInputElementIdName = 'cover-img-crop-position',
    profilePositionInputElementIdName = 'profile-img-crop-position',
    groupDetailPageFlag = (typeof groupDetailPageFlag == 'undefined') ? false : groupDetailPageFlag,
    currentCoverImgCropPosition = getInputCropPosition(true),
    currentProfileImgCropPosition = getInputCropPosition(false);

$(function() {
    var drags = document.querySelectorAll('.cropit-image-preview'),
        mouseDownFlag = false;
    [].forEach.call(drags, function(drag) {
        drag.addEventListener('mousedown', function(e) {
            mouseDownFlag = true;
        }, false);

        drag.addEventListener('mouseup', function(e) {
            if (mouseDownFlag) {
                showImageAction(this);
                mouseDownFlag = false;
            };
        }, false);

        drag.addEventListener('mouseleave', function(e) {
            if (mouseDownFlag) {
                showImageAction(this);
                mouseDownFlag = false;
            };
        }, false);
    })

    // Init Crop for Cover and Profile Image
    initCropItImage(true, initCropImageStateObj(coverImage, currentCoverImgCropPosition));
    initCropItImage(false, initCropImageStateObj(profileImage, currentProfileImgCropPosition));

    // Cover Image Save and Cancel
    $('.save-cover-image').click(function(e) {
        e.preventDefault();

        // Save current cover crop position
        currentCoverImgCropPosition = getCropItImageStateInfo(true);
        setInputCropPosition(true, currentCoverImgCropPosition);

        $(this).parent().hide();
    });

    $('.cancel-cover-image').click(function(e) {
        e.preventDefault();

        // Revert previous save position
        setCropPosition(true, currentCoverImgCropPosition);
        setInputCropPosition(true, currentCoverImgCropPosition);

        $(this).parent().hide();
    });

    // Profile Image Save and Cancel
    $('.save-profile-image').click(function(e) {
        e.preventDefault();

        // Save current profile crop position
        currentProfileImgCropPosition = getCropItImageStateInfo(false);
        setInputCropPosition(false, currentProfileImgCropPosition);

        $(this).parent().hide();
    });

    $('.cancel-profile-image').click(function(e) {
        e.preventDefault();

        // Revert previous save position
        setCropPosition(false, currentProfileImgCropPosition);
        setInputCropPosition(false, currentProfileImgCropPosition);

        $(this).parent().hide();
    });

    $('.cropit-image-zoom-input').on('change', function(e) {
        showImageAction(this);
    });
});

function initCropImageStateObj(imageUrl, position) {
    if (imageUrl.length == 0) {
        return null;
    };

    var imageStateObj = {
        src: imageUrl
    }

    if ((typeof position == 'object') && (!$.isEmptyObject(position))) {
        $.extend(imageStateObj,position);
    };

    return imageStateObj;
}

function initCropItImage(forCoverFlag, imageStateObjOrUrl, forInitFlag) {
    var forInitFlag = (typeof forInitFlag === 'undefined') ? true : forInitFlag,
        elementIdName = forCoverFlag ? coverElementIdName : profileElementIdName,
        imageStateObj = (typeof imageStateObjOrUrl == 'object') ? imageStateObjOrUrl : {src: imageStateObjOrUrl};

    $('#' + elementIdName).cropit({
        smallImage: 'stretch',
        minZoom: forCoverFlag ? 'fill' : 'fit',
        maxZoom: 2,
        imageBackground: true,
        imageBackgroundBorderWidth: 15,
        imageState: imageStateObj,
        onImageLoaded: function(e) {
            $('#' + elementIdName).find('.img-action').hide();

            setInputCropPosition(forCoverFlag, getCropItImageStateInfo(forCoverFlag));

            if (forCoverFlag) {
                currentCoverImgCropPosition = getCropItImageStateInfo(true);
            } else {
                currentProfileImgCropPosition = getCropItImageStateInfo(false);
            }
        }
    });

    if (groupDetailPageFlag) {
        $('#' + elementIdName).cropit('disable');
    };
}

function getCropItImageStateInfo(forCoverFlag) {
    var elementIdName = forCoverFlag ? coverElementIdName : profileElementIdName;

    return {
        offset: $('#' + elementIdName).cropit('offset'),
        zoom: $('#' + elementIdName).cropit('zoom')
    }
}

function setInputCropPosition(forCoverFlag, position) {
    var elementIdName = forCoverFlag ? coverPositionInputElementIdName : profilePositionInputElementIdName;

    $('#' + elementIdName).val(position ? JSON.stringify(position) : "");
}

function setCropPosition(forCoverFlag, position) {
    var elementIdName = forCoverFlag ? coverElementIdName : profileElementIdName;

    if (!$.isEmptyObject(position)) {
        $('#' + elementIdName).cropit('zoom', position.zoom);
        $('#' + elementIdName).cropit('offset', position.offset);
    };
}

function getInputCropPosition(forCoverFlag, typeReturn) {
    var elementIdName = forCoverFlag ? coverPositionInputElementIdName : profilePositionInputElementIdName,
        typeReturn = (typeof typeReturn == 'undefined') ? 'object' : typeReturn,
        positionStr = $('#' + elementIdName).val();

    if (typeReturn == 'object') {
        var positionObj = (typeof positionStr != 'undefined' && positionStr.length > 0) ? JSON.parse(positionStr) : null;

        if (!$.isEmptyObject(positionObj) && !positionObj.hasOwnProperty('zoom')) {
            positionObj = null;
            setInputCropPosition(forCoverFlag, positionObj);
        };

        return positionObj;
    };

    return positionStr;
}

function showImageAction(previewElement) {
    $(previewElement).parents('.edit-picture').find('.img-action').show();
}
