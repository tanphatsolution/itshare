$(function() {
    var coverImgCropPositionStr = $('#cover-img-crop-position').val();
    var profileImgCropPositionStr = $('#profile-img-crop-position').val();

    var currentCoverImgCropPosition = (typeof coverImgCropPositionStr != 'undefined'
        && coverImgCropPositionStr.length > 0) ? JSON.parse(coverImgCropPositionStr) : null;
    var currentProfileImgCropPosition = (typeof profileImgCropPositionStr != 'undefined'
        &&profileImgCropPositionStr.length > 0) ? JSON.parse(profileImgCropPositionStr) : null;

    // Init Cover and Profile Picture
    $('.images-group img').attr('src', coverImage);
    $('.img-viblo img').attr('src', profileImage);

    // Init Drag and Crop for Cover and Profile Image
    initDragAndCrop(true, currentCoverImgCropPosition);
    initDragAndCrop(false, currentProfileImgCropPosition);

    // Cover Image Save and Cancel
    $('.save-cover-image').click(function(e) {
        e.preventDefault();

        // Save current cover crop position
        var currentPosition = $('.cropable-cover-image').dragncrop('getPosition');
        currentCoverImgCropPosition = currentPosition;

        $('#cover-img-crop-position').val(currentCoverImgCropPosition ? JSON.stringify(currentCoverImgCropPosition) : "");
        $('.cover-img-action').hide();
    });

    $('.cancel-cover-image').click(function(e) {
        e.preventDefault();

        // Revert previous save position
        initDragAndCrop(true, currentCoverImgCropPosition, false);
        $('.cover-img-action').hide();
    });

    // Profile Image Save and Cancel
    $('.save-profile-image').click(function(e) {
        e.preventDefault();

        // Save current profile crop position
        var currentPosition = $('.cropable-profile-image').dragncrop('getPosition');
        currentProfileImgCropPosition = currentPosition;

        $('#profile-img-crop-position').val(currentProfileImgCropPosition ? JSON.stringify(currentProfileImgCropPosition) : "");
        $('.profile-img-action').hide();
    });

    $('.cancel-profile-image').click(function(e) {
        e.preventDefault();

        // Revert previous save position
        initDragAndCrop(false, currentProfileImgCropPosition, false);
        $('.profile-img-action').hide();
    });

    // Submit edit form
    $('#submit-edit-group-form').click(function(e) {
        e.preventDefault();

        var currentCoverPosition = $('.cropable-cover-image').dragncrop('getPosition');
        var currentProfilePosition = $('.cropable-profile-image').dragncrop('getPosition');

        $('#cover-img-crop-position').val(currentCoverPosition ? JSON.stringify(currentCoverPosition) : "");
        $('#profile-img-crop-position').val(currentProfilePosition ? JSON.stringify(currentProfilePosition) : "");

        $('#edit-group').submit();
    });

});

function initDragAndCrop(forCoverFlag, position, forInitFlag) {
    var forInitFlag = (typeof forInitFlag === 'undefined') ? true : forInitFlag;
    var className = forCoverFlag ? 'cropable-cover-image' : 'cropable-profile-image';

    if (!forInitFlag) {
        $('.' + className).dragncrop('destroy');
    };

    $('.' + className).dragncrop({
        position: position,
        centered: position ? false : true,
        start: function(e, pos) {
            var actionClassName = forCoverFlag ? 'cover-img-action' : 'profile-img-action';
            $('.' + actionClassName).show();
        }
    });

}