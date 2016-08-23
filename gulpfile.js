var gulp = require('gulp');
var minify = require('gulp-minify');
var minifyCss = require('gulp-minify-css');
var uglifyJs = require('gulp-uglifyjs');
var runSequence = require('run-sequence');
var concat = require('gulp-concat');
var del = require('del');
var autoprefixer = require('gulp-autoprefixer');
var Promise = require('es6-promise').Promise;

gulp.task('compress', function () {
    gulp.src('public/js/*.js')
        .pipe(minify({
            exclude: ['tasks'],
            ignoreFiles: ['-min.js']
        }))
        .pipe(gulp.dest('public/dist/js'));
    gulp.src('public/css/!*.css')
        .pipe(minifyCss({compatibility: 'ie8'}))
        .pipe(gulp.dest('public/dist/css'));
});

gulp.task('minify_build', function () {
    runSequence('build-clean', [
        'module_layouts',
        'module_account',
        'module_categories',
        'module_categoryrequests',
        'module_contests',
        'module_errors',
        'module_feedback',
        'module_groups',
        'module_groupseries',
        'module_home',
        'module_image',
        'module_modals',
        'module_monthlythemes',
        'module_notification',
        'module_oauth',
        'module_post',
        'module_profile',
        'module_report',
        'module_role',
        'module_search',
        'module_server',
        'module_setting',
        'module_themes',
        'module_user',
        'module_userskills',
        'module_language',
        'home_sign_up',
        'codemirror',
        'codemirror_home'
    ]);
});

gulp.task('build-clean', function () {
    return del(['public/css_min/*', 'public/js_min/*']);
});

gulp.task('module_layouts', function () {
    //For app/views/layouts/includes/head.blade.php
    gulp.src(['public/css/font-google.css',
        'public/css/bootstrap-335.min.css',
        'public/css/bootstrap-theme-335.min.css',
        'public/css/font-awesome.css',
        'public/css/bootstrap-social.css',
        'public/css/jquery-ui.css',
        'public/css/animate.css',
        'public/css/common.css',
        'public/css/common-v2.css',
        'public/css/responsive.css',
        'public/css/header-res.css',
        'public/css/custom.css',
        'public/css/select-language.css',
        'public/css/see-more.css',
        'public/css/groups.css'])
        .pipe(minifyCss())
        .pipe(concat('layout_head.min.css'))
        .pipe(gulp.dest('public/css_min'));
    gulp.src(['public/js/jquery-1.11.1.min.js',
        'public/js/jquery-ui.min.js',
        'public/js/bootstrap-335.min.js',
        'public/js/bootbox.min.js',
        'public/js/bootstrap-growl.min.js',
        'public/js/admin.js',
        'public/js/jquery.lazyload.min.js',
        'public/js/jquery.mobile-events.min.js',
        'public/js/jquery.touchSwipe.js'])
        .pipe(uglifyJs('layout_head.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));
    gulp.src(['public/js/all.js',
        'public/js/app.js',
        'public/js/ga.js',
        'public/js/groups.js',
        'public/js/language-settings.js',
        'public/js/logscreen.js',
        'public/js/custom.js'])
        .pipe(uglifyJs('layout_head2.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));

    //For app/views/layouts/group.blade.php
    gulp.src(['public/css/group-common.css',
        'public/css/jquery.fancybox.css',
        'public/css/see-more-groups.css',
        'public/css/group-detail.css',
        'public/css/group-detail-extra.css',
        'public/css/sweet-alert.css',
        'public/css/drag-and-crop.css'])
        .pipe(minifyCss())
        .pipe(concat('layouts_group.min.css'))
        .pipe(gulp.dest('public/css_min'));
    gulp.src(['public/js/groups/approve-post.js',
        'public/js/groups/approve-user.js',
        'public/js/groups/group-member.js',
        'public/js/groups/process-description.js',
        'public/js/jquery.fancybox.js',
        'public/js/jquery.cropit.js',
        'public/js/sweet-alert.min.js',
        'public/js/groups/image-cropit.js'])
        .pipe(uglifyJs('layouts_group.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));
});

gulp.task('module_account', function () {
    //For app/views/account/create_account.blade.php
    gulp.src(['public/css/setting.css',
        'public/css/admin.css'])
        .pipe(minifyCss())
        .pipe(concat('account_create.min.css'))
        .pipe(gulp.dest('public/css_min'));
});

gulp.task('module_contests', function () {
    //For app/views/contests/create.blade.php
    gulp.src(['public/css/contests_create.css'])
        .pipe(minifyCss())
        .pipe(concat('contests_create.min.css'))
        .pipe(gulp.dest('public/css_min'));
    gulp.src(['public/js/contest.js'])
        .pipe(uglifyJs('contest.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));

});

gulp.task('module_categories', function () {
    //For app/views/categories/create.blade.php
    gulp.src(['public/css/setting.css',
        'public/css/bootstrap-switch.min.css',
        'public/css/admin.css'])
        .pipe(minifyCss())
        .pipe(concat('categories_create.min.css'))
        .pipe(gulp.dest('public/css_min'));
    gulp.src(['public/js/bootstrap-switch.min.js',
        'public/js/setting.js',
        'public/js/category.js'])
        .pipe(uglifyJs('categories_create.min.js', {
            outSourceMap: true
        }))
        .pipe(gulp.dest('public/js_min'));

    //For app/views/categories/edit.blade.php
    gulp.src(['public/css/admin.css',
        'public/css/category.css',
        'public/css/bootstrap-switch.min.css'])
        .pipe(minifyCss())
        .pipe(concat('categories_edit.min.css'))
        .pipe(gulp.dest('public/css_min'));

    //For app/views/categories/index.blade.php
    gulp.src(['public/css/list-category.css',
        'public/css/post_header_author_v2.css'])
        .pipe(minifyCss())
        .pipe(concat('categories_index.min.css'))
        .pipe(gulp.dest('public/css_min'));
    gulp.src(['public/js/category-follow.js',
        'public/js/category.js'])
        .pipe(uglifyJs('categories_index.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));

    //For app/views/categories/new.blade.php and recent.blade.php
    gulp.src('public/css/category.css')
        .pipe(minifyCss())
        .pipe(concat('categories_new.min.css'))
        .pipe(gulp.dest('public/css_min'));
    gulp.src('public/js/category-follow.js')
        .pipe(uglifyJs('categories_new.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));

    //For app/views/categories/show.blade.php
    gulp.src(['public/css/category.css',
        'public/css/category-detail.css',
        'public/css/group.css',
        'public/css/group-detail.css',
        'public/css/post_header_author.css',
        'public/css/post_header_author_v2.css'])
        .pipe(minifyCss())
        .pipe(concat('categories_show.min.css'))
        .pipe(gulp.dest('public/css_min'));

    //For app/views/categories/view.blade.php
    gulp.src(['public/css/setting.css',
        'public/css/category.css',
        'public/css/bootstrap-switch.min.css',
        'public/css/sweet-alert.css',
        'public/css/admin.css'])
        .pipe(minifyCss())
        .pipe(concat('categories_view.min.css'))
        .pipe(gulp.dest('public/css_min'));
    gulp.src(['public/js/sweet-alert.min.js',
        'public/js/bootstrap-switch.min.js',
        'public/js/setting.js',
        'public/js/category.js'])
        .pipe(uglifyJs('categories_view.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));

    //For app/views/categories/export.blade.php and import.blade.php
    gulp.src(['public/css/setting.css',
        'public/css/bootstrap-switch.min.css',
        'public/css/admin.css'])
        .pipe(minifyCss())
        .pipe(concat('categories_export_import.min.css'))
        .pipe(gulp.dest('public/css_min'));
    gulp.src(['public/js/bootstrap-switch.min.js',
        'public/js/setting.js',
        'public/js/category.js'])
        .pipe(uglifyJs('categories_export_import.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));
});

gulp.task('module_categoryrequests', function () {
    //For app/views/categoryrequests/create.blade.php
    gulp.src(['public/css/setting.css',
        'public/css/bootstrap-switch.min.css'])
        .pipe(minifyCss())
        .pipe(concat('categoryrequest_create.min.css'))
        .pipe(gulp.dest('public/css_min'));
    gulp.src(['public/js/bootstrap-switch.min.js',
        'public/js/categoryrequest_create.min.js',
        'public/js/category-request.js'])
        .pipe(uglifyJs('categoryrequest_create.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));

    //For app/views/categoryrequests/edit.blade.php
    gulp.src(['public/css/setting.css',
        'public/css/category.css',
        'public/css/bootstrap-switch.min.css'])
        .pipe(minifyCss())
        .pipe(concat('categoryrequest_edit.min.css'))
        .pipe(gulp.dest('public/css_min'));

    //For app/views/categoryrequests/view.blade.php
    gulp.src(['public/css/setting.css',
        'public/css/category.css',
        'public/css/bootstrap-switch.min.css',
        'public/css/sweet-alert.css',
        'public/css/admin.css'])
        .pipe(minifyCss())
        .pipe(concat('categoryrequest_view.min.css'))
        .pipe(gulp.dest('public/css_min'));
    gulp.src(['public/js/sweet-alert.min.js',
        'public/js/setting.js',
        'public/js/category-request.js'])
        .pipe(uglifyJs('categoryrequest_view.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));
});

gulp.task('module_errors', function () {
    //For app/views/errors/404.blade.php
    gulp.src('public/css/notfound.css')
        .pipe(minifyCss())
        .pipe(concat('errors_404.min.css'))
        .pipe(gulp.dest('public/css_min'));

    //For app/views/errors/usernotfound.blade.php
    gulp.src('public/css/user.css')
        .pipe(minifyCss())
        .pipe(concat('errors_user.min.css'))
        .pipe(gulp.dest('public/css_min'));
});

gulp.task('module_feedback', function () {
    //For app/views/feedback/create.blade.php
    gulp.src('public/css/feedback.css')
        .pipe(minifyCss())
        .pipe(concat('feedback_create.min.css'))
        .pipe(gulp.dest('public/css_min'));
    gulp.src('public/js/feedback.js')
        .pipe(uglifyJs('feedback_create.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));

    //For app/views/feedback/index.blade.php
    gulp.src(['public/css/setting.css',
        'public/css/feedback.css',
        'public/css/admin.css'])
        .pipe(minifyCss())
        .pipe(concat('feedback_index.min.css'))
        .pipe(gulp.dest('public/css_min'));

    //For app/views/feedback/show.blade.php
    gulp.src(['public/css/setting.css',
        'public/css/feedback.css',
        'public/css/admin.css'])
        .pipe(minifyCss())
        .pipe(concat('feedback_show.min.css'))
        .pipe(gulp.dest('public/css_min'));
});

gulp.task('module_groups', function () {
    //For app/views/groups/category_posts.blade.php
    gulp.src(['public/css/category.css',
        'public/css/category-detail.css',
        'public/css/themes.css',
        'public/css/group_category_posts.css'])
        .pipe(minifyCss())
        .pipe(concat('groups_category_posts.min.css'))
        .pipe(gulp.dest('public/css_min'));
    gulp.src('public/js/groups/category-post.js')
        .pipe(uglifyJs('groups_category_posts.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min/groups'));

    //For app/views/groups/create.blade.php
    gulp.src(['public/css/bootstrap-tagsinput.css',
        'public/css/sweet-alert.css',
        'public/css/post-detail.css',
        'public/css/group-detail.css',
        'public/css/see-more-groups.css',
        'public/css/drag-and-crop.css'])
        .pipe(minifyCss())
        .pipe(concat('groups_create.min.css'))
        .pipe(gulp.dest('public/css_min'));
    gulp.src(['public/js/groups/group.js',
        'public/js/groups/group-create.js',
        'public/js/sweet-alert.min.js',
        'public/js/dropzone.js',
        'public/js/bootstrap-tagsinput/bootstrap-tagsinput.min.js',
        'public/js/bootstrap3-typeahead.min.js',
        'public/js/jquery.cropit.js',
        'public/js/groups/image-cropit.js'])
        .pipe(uglifyJs('groups_create.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));

    //For app/views/groups/edit.blade.php
    gulp.src(['public/css/bootstrap-tagsinput.css',
        'public/css/sweet-alert.css',
        'public/css/post-detail.css',
        'public/css/bootstrap-editable.css',
        'public/css/group-detail.css',
        'public/css/see-more-groups.css',
        'public/css/drag-and-crop.css'])
        .pipe(minifyCss())
        .pipe(concat('groups_edit.min.css'))
        .pipe(gulp.dest('public/css_min'));
    gulp.src(['public/js/bootstrap-editable.js',
        'public/js/groups/group.js',
        'public/js/groups/group-edit.js',
        'public/js/sweet-alert.min.js',
        'public/js/dropzone.min.js',
        'public/js/bootstrap-tagsinput/bootstrap-tagsinput.min.js',
        'public/js/bootstrap3-typeahead.min.js',
        'public/js/jquery.cropit.js',
        'public/js/groups/image-cropit.js',
        'public/js/groups/group-detail.js'])
        .pipe(uglifyJs('groups_edit.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));

    //For app/views/groups/index.blade.php
    gulp.src(['public/css/see-more-groups.css',
        'public/css/group.css',
        'public/css/sweet-alert.css',
        'public/css/group-detail.css'])
        .pipe(minifyCss())
        .pipe(concat('groups_index.min.css'))
        .pipe(gulp.dest('public/css_min'));
    gulp.src(['public/js/groups/group.js', 'public/js/sweet-alert.min.js'])
        .pipe(uglifyJs('groups_index.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));

    //For app/views/groups/search.blade.php
    gulp.src('public/css/group-detail-content.css')
        .pipe(minifyCss())
        .pipe(concat('groups_search.min.css'))
        .pipe(gulp.dest('public/css_min'));
    gulp.src('public/js/groups/group-search.js')
        .pipe(uglifyJs('groups_search.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));

    //For app/views/groups/show.blade.php
    gulp.src('public/css/group-detail-content.css')
        .pipe(minifyCss())
        .pipe(concat('groups_show.min.css'))
        .pipe(gulp.dest('public/css_min'));
    gulp.src('public/js/groups/group-detail.js')
        .pipe(uglifyJs('groups_show.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min/groups'));
});

gulp.task('module_groupseries', function () {
    //For app/views/groupseries/create.blade.php
    gulp.src(['public/css/bootstrap-tagsinput.css',
        'public/css/sweet-alert.css',
        'public/css/group-detail.css',
        'public/css/see-more-new.css',
        'public/css/group-post-new.css',
        'public/css/group-series-new.css'])
        .pipe(minifyCss())
        .pipe(concat('groupseries_create.min.css'))
        .pipe(gulp.dest('public/css_min'));
    gulp.src(['public/js/groups/group-series.js',
        'public/js/sweet-alert.min.js',
        'public/js/dropzone.js',
        'public/js/bootstrap-tagsinput/bootstrap-tagsinput.min.js',
        'public/js/bootstrap3-typeahead.min.js',
        'public/js/groups/group-detail.js'])
        .pipe(uglifyJs('groupseries_create.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));

    //For app/views/groupseries/edit.blade.php
    gulp.src(['public/js/groups/group-series.js',
        'public/js/sweet-alert.min.js',
        'public/js/dropzone.js',
        'public/js/bootstrap-tagsinput/bootstrap-tagsinput.min.js',
        'public/js/bootstrap3-typeahead.min.js'])
        .pipe(uglifyJs('groupseries_edit.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));

    //For app/views/groupseries/show.blade.php
    gulp.src(['public/js/groups/join-group.js',
        'public/js/groups/group-series.js',
        'public/js/sweet-alert.min.js',
        'public/js/dropzone.js',
        'public/js/bootstrap-tagsinput/bootstrap-tagsinput.min.js',
        'public/js/bootstrap3-typeahead.min.js',
        'public/js/groups/group-detail.js',
        'public/js/groups/view_count_series.js'])
        .pipe(uglifyJs('groupseries_show.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));
});

gulp.task('module_home', function () {
    //For app/views/home/index.blade.php
    gulp.src('public/css/home.css')
        .pipe(minifyCss())
        .pipe(concat('home_index.min.css'))
        .pipe(gulp.dest('public/css_min'));
    gulp.src(['public/js/scroll-reveal.min.js',
        'public/js/home.js'])
        .pipe(uglifyJs('home_index.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));

    //For app/views/home/top.blade.php
    gulp.src(['public/css/top-page.css',
        'public/css/jquery.bxslider.css',
        'public/css/slide_v2.css'])
        .pipe(minifyCss())
        .pipe(concat('home_top.min.css'))
        .pipe(gulp.dest('public/css_min'));
    gulp.src(['public/js/slide.js',
        'public/js/themes/professional-post.js'])
        .pipe(uglifyJs('home_top.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));
});

gulp.task('module_image', function () {
    //For app/views/image/index.blade.php
    gulp.src(['public/css/setting.css',
        'public/css/image.css',
        'public/css/bootstrap-switch.min.css',
        'public/css/sweet-alert.css',
        'public/css/jquery.fancybox.css'])
        .pipe(minifyCss())
        .pipe(concat('image_index.min.css'))
        .pipe(gulp.dest('public/css_min'));
    gulp.src(['public/js/sweet-alert.min.js',
        'public/js/bootstrap-switch.min.js',
        'public/js/image.js',
        'public/js/progress-bar.js',
        'public/js/jquery.fancybox.js'])
        .pipe(uglifyJs('image_index.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));
});

gulp.task('module_modals', function () {
    //For app/views/modals/login_form.blade.php
    gulp.src('public/css/form-login.css')
        .pipe(minifyCss())
        .pipe(concat('modals_login_form.min.css'))
        .pipe(gulp.dest('public/css_min'));
    gulp.src('public/js/login.js')
        .pipe(uglifyJs('modals_login_form.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));
});

gulp.task('module_monthlythemes', function () {
    //For app/views/monthlythemes/backnumber.blade.php
    gulp.src(['public/css/bootstrap-switch.min.css',
        'public/css/sweet-alert.css',
        'public/css/admin.css'])
        .pipe(minifyCss())
        .pipe(concat('monthlythemes_backnumber.min.css'))
        .pipe(gulp.dest('public/css_min'));
    gulp.src(['public/js/back-number.js',
        'public/js/sweet-alert.min.js',
        'public/js/bootstrap-switch.min.js',
        'public/js/bootstrap-datepicker.js'])
        .pipe(uglifyJs('monthlythemes_backnumber.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));

    //For app/views/monthlythemes/create.blade.php
    gulp.src(['public/css/bootstrap-switch.min.css',
        'public/css/sweet-alert.css',
        'public/css/admin.css'])
        .pipe(minifyCss())
        .pipe(concat('monthlythemes_create.min.css'))
        .pipe(gulp.dest('public/css_min'));
    gulp.src(['public/js/theme.js',
        'public/js/theme-create.js',
        'public/js/sweet-alert.min.js',
        'public/js/bootstrap-switch.min.js',
        'public/js/bootstrap-datepicker.js'])
        .pipe(uglifyJs('monthlythemes_create.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));
});

gulp.task('module_notification', function () {
    //For app/views/notification/index.blade.php
    gulp.src('public/css/list-notification.css')
        .pipe(minifyCss())
        .pipe(concat('notification_index.min.css'))
        .pipe(gulp.dest('public/css_min'));
});

gulp.task('module_oauth', function () {
    //For app/views/oauth/authorization_login_form.blade.php
    gulp.src('public/css/user.css')
        .pipe(minifyCss())
        .pipe(concat('oauth_authorization_login.min.css'))
        .pipe(gulp.dest('public/css_min'));

    //For app/views/oauth/create.blade.php
    gulp.src(['public/css/setting.css',
        'public/css/admin.css'])
        .pipe(minifyCss())
        .pipe(concat('oauth_create.min.css'))
        .pipe(gulp.dest('public/css_min'));

    //For app/views/oauth/index.blade.php
    gulp.src(['public/css/setting.css',
        'public/css/admin.css',
        'public/css/sweet-alert.css'])
        .pipe(minifyCss())
        .pipe(concat('oauth_index.min.css'))
        .pipe(gulp.dest('public/css_min'));
});

gulp.task('module_post', function () {
    //For app/views/post/show.blade.php
    gulp.src(['public/css/select-language.css',
        'public/css/groups.css',
        'public/css/post.css',
        'public/css/sweet-alert.css',
        'public/css/comment.css',
        'public/css/jquery.fancybox.css',
        'public/css/post-detail-groups.css',
        'public/css/see-more-groups.css',
        'public/css/group-post.css',
        'public/css/popup.css',
        'public/css/post-detail.css',
        'public/css/group-detail.css',
        'public/css/post_header_author.css',
        'public/css/post_header_author_v2.css'])
        .pipe(minifyCss())
        .pipe(concat('post_show.min.css'))
        .pipe(gulp.dest('public/css_min'));
    gulp.src(['public/js/groups/join-group.js',
        'public/js/sweet-alert.min.js',
        'public/js/post.js',
        'public/js/view-counter.js',
        'public/js/stock.js',
        'public/js/comment.js',
        'public/js/relationships.js',
        'public/js/zeroclipboard/ZeroClipboard.min.js',
        'public/js/dropzone.js',
        'public/js/bootstrap-tagsinput/bootstrap-tagsinput.min.js',
        'public/js/bootstrap3-typeahead.min.js',
        'public/js/jquery.fancybox.js',
        'public/js/jquery.fancyboxbox.transitions.js',
        'public/js/groups/group.js',
        'public/js/add_default_post.js',
        'public/js/social-share-count.js'
    ])
        .pipe(uglifyJs('post_show.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));

    //For app/views/post/create.blade.php
    gulp.src(['public/css/post.css',
        'public/css/bootstrap-tagsinput.css',
        'public/css/new-post.css',
        'public/css/sweet-alert.css',
        'public/css/jquery.fancybox.css',
        'public/css/screen.css'])
        .pipe(minifyCss())
        .pipe(concat('post_create.min.css'))
        .pipe(gulp.dest('public/css_min'));
    gulp.src(['public/js/groups/group-post.js',
        'public/js/sweet-alert.min.js',
        'public/js/zeroclipboard/ZeroClipboard.min.js',
        'public/js/dropzone.js',
        'public/js/bootstrap-tagsinput/bootstrap-tagsinput.min.js',
        'public/js/bootstrap3-typeahead.min.js',
        'public/js/post.js',
        'public/js/post-alert.js',
        'public/js/jquery.fancybox.js'])
        .pipe(uglifyJs('post_create.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));

    //For app/views/post/draft.blade.php
    gulp.src(['public/css/post.css',
        'public/css/sweet-alert.css',
        'public/css/draft.css',
        'public/css/post-detail.css',
        'public/css/jquery.fancybox.css'])
        .pipe(minifyCss())
        .pipe(concat('post_draft.min.css'))
        .pipe(gulp.dest('public/css_min'));
    gulp.src(['public/js/sweet-alert.min.js',
        'public/js/draft.js',
        'public/js/jquery.fancybox.js'])
        .pipe(uglifyJs('post_draft.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));

    //For app/views/post/edit.blade.php
    gulp.src(['public/css/post.css',
        'public/css/bootstrap-tagsinput.css',
        'public/css/new-post.css',
        'public/css/sweet-alert.css',
        'public/css/jquery.fancybox.css',
        'public/css/screen.css'])
        .pipe(minifyCss())
        .pipe(concat('post_edit.min.css'))
        .pipe(gulp.dest('public/css_min'));
    gulp.src(['public/js/groups/group-post.js',
        'public/js/sweet-alert.min.js',
        'public/js/zeroclipboard/ZeroClipboard.min.js',
        'public/js/dropzone.js',
        'public/js/bootstrap-tagsinput/bootstrap-tagsinput.min.js',
        'public/js/bootstrap3-typeahead.min.js',
        'public/js/post.js',
        'public/js/post-alert.js',
        'public/js/jquery.fancybox.js'])
        .pipe(uglifyJs('post_edit.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));

    //For app/views/post/index.blade.php
    gulp.src(['public/css/post-detail.css',
        'public/css/group.css',
        'public/css/group-detail.css',
        'public/css/post_header_author.css',
        'public/css/post_header_author_v2.css'])
        .pipe(minifyCss())
        .pipe(concat('post_index.min.css'))
        .pipe(gulp.dest('public/css_min'));
    gulp.src(['public/js/post.js',
        'public/js/post-all-filter.js'])
        .pipe(uglifyJs('post_index.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));

    //For app/views/post/list.blade.php
    gulp.src(['public/css/setting.css',
        'public/css/category.css',
        'public/css/bootstrap-switch.min.css',
        'public/css/sweet-alert.css',
        'public/css/admin.css',
        'public/css/select2.min.css',
        'public/css/select2-bootstrap.min.css'])
        .pipe(minifyCss())
        .pipe(concat('post_list.min.css'))
        .pipe(gulp.dest('public/css_min'));
    gulp.src(['public/js/sweet-alert.min.js',
        'public/js/bootstrap-switch.min.js',
        'public/js/bootstrap-datepicker.js',
        'public/js/setting.js',
        'public/js/user.js',
        'public/js/ban.js',
        'public/js/select2.full.min.js',
        'public/js/post-manager.js'])
        .pipe(uglifyJs('post_list.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));

    //For app/views/post/statistic.blade.php
    gulp.src(['public/css/setting.css',
        'public/css/category.css',
        'public/css/admin.css'])
        .pipe(minifyCss())
        .pipe(concat('post_statistic.min.css'))
        .pipe(gulp.dest('public/css_min'));
    gulp.src(['public/js/raphael/raphael-min.js',
        'public/js/raphael/g.raphael-min.js',
        'public/js/raphael/g.pie-min.js',
        'public/js/raphael/g.bar-min.js',
        'public/js/graph.js'])
        .pipe(uglifyJs('post_statistic.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));
});

gulp.task('module_profile', function () {
    //For app/views/profile/update.blade.php
    gulp.src(['public/css/setting.css',
        'public/css/sweet-alert.css',
        'public/css/bootstrap-switch.min.css',
        'public/css/select2.min.css',
        'public/css/select2-bootstrap.min.css'])
        .pipe(minifyCss())
        .pipe(concat('profile_update.min.css'))
        .pipe(gulp.dest('public/css_min'));
    gulp.src(['public/js/sweet-alert.min.js',
        'public/js/dropzone.js',
        'public/js/profile.js',
        'public/js/bootstrap-tagsinput/bootstrap-tagsinput.min.js',
        'public/js/bootstrap3-typeahead.min.js',
        'public/js/bootstrap-switch.min.js',
        'public/js/setting.js',
        'public/js/select2.full.min.js'])
        .pipe(uglifyJs('profile_update.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));
});

gulp.task('module_report', function () {
    //For app/views/report/index.blade.php
    gulp.src(['public/css/sweet-alert.css',
        'public/css/setting.css',
        'public/css/admin.css'])
        .pipe(minifyCss())
        .pipe(concat('report_index.min.css'))
        .pipe(gulp.dest('public/css_min'));
    gulp.src(['public/js/sweet-alert.min.js',
        'public/js/report.js'])
        .pipe(uglifyJs('report_index.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));
});

gulp.task('module_role', function () {
    //For app/views/role/change.blade.php
    gulp.src(['public/css/setting.css',
        'public/css/sweet-alert.css',
        'public/css/admin.css'])
        .pipe(minifyCss())
        .pipe(concat('role_change.min.css'))
        .pipe(gulp.dest('public/css_min'));
    gulp.src(['public/js/sweet-alert.min.js',
        'public/js/role.js'])
        .pipe(uglifyJs('role_change.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));

    //For app/views/role/privilege.blade.php
    gulp.src(['public/css/setting.css',
        'public/css/role.css',
        'public/css/admin.css'])
        .pipe(minifyCss())
        .pipe(concat('role_privilege.min.css'))
        .pipe(gulp.dest('public/css_min'));
});

gulp.task('module_search', function () {
    //For app/views/search/index.blade.php
    gulp.src(['public/css/search.css',
        'public/css/perfect-scrollbar.css',
        'public/css/result-search.css',
        'public/css/list-category.css',
        'public/css/post_header_author.css',
        'public/css/post_header_author_v2.css',
        'public/css/group.css'])
        .pipe(minifyCss())
        .pipe(concat('search_index.min.css'))
        .pipe(gulp.dest('public/css_min'));
    gulp.src(['public/js/relationships.js',
        'public/js/perfect-scrollbar.min.js',
        'public/js/search.js'])
        .pipe(uglifyJs('search_index.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));
});

gulp.task('module_server', function () {
    //For app/views/server/deploy.blade.php
    gulp.src(['public/css/setting.css',
        'public/css/admin.css'])
        .pipe(minifyCss())
        .pipe(concat('server_deploy.min.css'))
        .pipe(gulp.dest('public/css_min'));

    //For app/views/setting/index.blade.php
    gulp.src('public/css/setting.css')
        .pipe(minifyCss())
        .pipe(concat('setting_index.min.css'))
        .pipe(gulp.dest('public/css_min'));
});

gulp.task('module_setting', function () {
    //For app/views/setting/language.blade.php
    gulp.src(['public/css/setting.css',
        'public/css/bootstrap-switch.min.css'])
        .pipe(minifyCss())
        .pipe(concat('setting_language.min.css'))
        .pipe(gulp.dest('public/css_min'));
    gulp.src(['public/js/bootstrap-switch.min.js',
        'public/js/setting.js'])
        .pipe(uglifyJs('setting_language.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));
});

gulp.task('module_themes', function () {
    //For app/views/themes/back_number.blade.php
    gulp.src('public/css/top-page.css')
        .pipe(minifyCss())
        .pipe(concat('themes_back_number.min.css'))
        .pipe(gulp.dest('public/css_min'));

    //For app/views/themes/categories.blade.php
    gulp.src(['public/css/category.css',
        'public/css/category-detail.css',
        'public/css/themes.css',
        'public/css/group.css',
        'public/css/group-detail.css',
        'public/css/post_header_author.css'])
        .pipe(minifyCss())
        .pipe(concat('themes_categories.min.css'))
        .pipe(gulp.dest('public/css_min'));
    gulp.src('public/js/themes/category-post.js')
        .pipe(uglifyJs('themes_categories.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));
});

gulp.task('module_user', function () {
    //For app/views/user/change_password.blade.php
    gulp.src(['public/css/setting.css',
        'public/css/user.css'])
        .pipe(minifyCss())
        .pipe(concat('user_change_password.min.css'))
        .pipe(gulp.dest('public/css_min'));

    //For app/views/user/draft.blade.php
    gulp.src(['public/css/profile_user_company.css',
        'public/css/group.css',
        'public/css/group-detail.css',
        'public/css/post_header_author.css',
        'public/css/post_header_author_v2.css'])
        .pipe(minifyCss())
        .pipe(concat('user_draft.min.css'))
        .pipe(gulp.dest('public/css_min'));

    //For app/views/user/followers.blade.php
    gulp.src(['public/css/group.css',
        'public/css/list-category.css',
        'public/css/profile_user_company.css',
        'public/css/post_header_author_v2.css'])
        .pipe(minifyCss())
        .pipe(concat('user_followers.min.css'))
        .pipe(gulp.dest('public/css_min'));

    //For app/views/user/following.blade.php
    gulp.src(['public/css/group.css',
        'public/css/list-category.css',
        'public/css/profile_user_company.css',
        'public/css/post_header_author_v2.css'])
        .pipe(minifyCss())
        .pipe(concat('user_following.min.css'))
        .pipe(gulp.dest('public/css_min'));

    //For app/views/user/following_cagtegories.blade.php
    gulp.src(['public/css/group.css',
        'public/css/profile_user_company.css',
        'public/css/list-category.css',
        'public/css/post_header_author_v2.css'])
        .pipe(minifyCss())
        .pipe(concat('following_cagtegories.min.css'))
        .pipe(gulp.dest('public/css_min'));
    gulp.src(['public/js/relationships.js',
        'public/js/category-follow.js',
        'public/js/category.js',
        'public/js/user.js'])
        .pipe(uglifyJs('following_cagtegories.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));

    //For app/views/user/login.blade.php
    gulp.src('public/js/user.js')
        .pipe(uglifyJs('user_login.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));

    //For app/views/user/posts.blade.php
    gulp.src('public/js/profile_user_company.css')
        .pipe(minifyCss())
        .pipe(concat('user_posts.min.css'))
        .pipe(gulp.dest('public/css_min'));

    //For app/views/user/show.blade.php
    gulp.src(['public/css/group.css',
        'public/css/user.css',
        'public/css/profile_user_company.css',
        'public/css/group.css',
        'public/css/group-detail.css',
        'public/css/post_header_author.css',
        'public/css/post_header_author_v2.css'])
        .pipe(minifyCss())
        .pipe(concat('user_show.min.css'))
        .pipe(gulp.dest('public/css_min'));
    gulp.src(['public/js/relationships.js',
        'public/js/user.js',
        'public/js/raphael/raphael-min.js',
        'public/js/raphael/g.raphael-min.js',
        'public/js/raphael/g.pie-min.js',
        'public/js/raphael/g.bar-min.js',
        'public/js/graph.js'])
        .pipe(uglifyJs('user_show.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));

    //For app/views/user/statistic.blade.php
    gulp.src(['public/css/user.css',
        'public/css/setting.css',
        'public/css/category.css',
        'public/css/admin.css'])
        .pipe(minifyCss())
        .pipe(concat('user_statistic.min.css'))
        .pipe(gulp.dest('public/css_min'));
    gulp.src(['public/js/raphael/raphael-min.js',
        'public/js/raphael/g.raphael-min.js',
        'public/js/raphael/g.pie-min.js',
        'public/js/raphael/g.bar-min.js',
        'public/js/graph.js'])
        .pipe(uglifyJs('user_statistic.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));

    //For app/views/user/stock.blade.php
    gulp.src(['public/css/profile_user_company.css',
        'public/css/group.css',
        'public/css/group-detail.css',
        'public/css/post_header_author.css',
        'public/css/post_header_author_v2.css'])
        .pipe(minifyCss())
        .pipe(concat('user_stock.min.css'))
        .pipe(gulp.dest('public/css_min'));

    gulp.src(['public/js/relationships.js',
        'public/js/user.js'])
        .pipe(uglifyJs('user_common.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));

    //For app/views/user/terms.blade.php
    gulp.src(['public/css/bootstrap.css',
        'public/css/bootstrap-theme.css',
        'public/css/font-awesome.css',
        'public/css/bootstrap-social.css',
        'public/css/jquery-ui.css',
        'public/css/animate.css',
        'public/css/sweet-alert.css',
        'public/css/common.css',
        'public/css/common-v2.css',
        'public/css/responsive.css'])
        .pipe(minifyCss())
        .pipe(concat('user_terms.min.css'))
        .pipe(gulp.dest('public/css_min'));

    //For app/views/user/update.blade.php
    gulp.src(['public/css/setting.css',
        'public/css/user.css'])
        .pipe(minifyCss())
        .pipe(concat('user_update.min.css'))
        .pipe(gulp.dest('public/css_min'));

    //For app/views/user/view.blade.php
    gulp.src(['public/css/setting.css',
        'public/css/category.css',
        'public/css/bootstrap-switch.min.css',
        'public/css/sweet-alert.css',
        'public/css/admin.css'])
        .pipe(minifyCss())
        .pipe(concat('user_view.min.css'))
        .pipe(gulp.dest('public/css_min'));
    gulp.src(['public/js/sweet-alert.min.js',
        'public/js/bootstrap-switch.min.js',
        'public/js/bootstrap-datepicker.js',
        'public/js/setting.js',
        'public/js/user.js',
        'public/js/ban.js'])
        .pipe(uglifyJs('user_view.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));
});

gulp.task('module_userskills', function () {
    //For app/views/userskills/create.blade.php
    gulp.src(['public/css/setting.css',
        'public/css/select2.min.css',
        'public/css/select2-bootstrap.min.css',
        'public/css/sweet-alert.css'])
        .pipe(minifyCss())
        .pipe(concat('userskills_create.min.css'))
        .pipe(gulp.dest('public/css_min'));
    gulp.src(['public/js/sweet-alert.min.js',
        'public/js/dropzone.js',
        'public/js/profile.js',
        'public/js/select2.full.min.js',
        'public/js/bootstrap-tagsinput/bootstrap-tagsinput.min.js',
        'public/js/bootstrap3-typeahead.min.js'])
        .pipe(uglifyJs('userskills_create.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));
});

gulp.task('module_language', function () {
    //For app/views/languages/statistic.blade.php
    gulp.src(['public/css/setting.css',
        'public/css/category.css',
        'public/css/admin.css'])
        .pipe(minifyCss())
        .pipe(concat('language_statistic.min.css'))
        .pipe(gulp.dest('public/css_min'));
});

gulp.task('home_sign_up', function () {
    // For home user not login
    gulp.src(['css/font-google.css',
        'public/css/toppage_not_auth_inline.css',
        'public/css/popup-toppage.css',
        'public/css/style-toppage.css',
        'public/css/style-toppage-responsive.css',
        'public/css/body-language.css',
        'public/css/basicPopup.css',
        'public/css/sweet-alert.css'])
        .pipe(minifyCss())
        .pipe(concat('home_sign_up.min.css'))
        .pipe(gulp.dest('public/css_min'));

    gulp.src(['public/js/ga.js',
        'public/js/jquery.basicPopup.js',
        'public/js/toppage.js',
        'public/js/sweet-alert.min.js'])
        .pipe(uglifyJs('home_sign_up.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));
});


gulp.task('codemirror', function () {
    // For codemirror post detail
    gulp.src(['public/js/codemirror/lib/codemirror.css',
        'public/js/codemirror/addon/fold/foldgutter.css',
        'public/js/codemirror/theme/3024-day.css',
        'public/js/codemirror/theme/3024-night.css',
        'public/js/codemirror/theme/abcdef.css',
        'public/js/codemirror/theme/ambiance.css',
        'public/js/codemirror/theme/ambiance-mobile.css',
        'public/js/codemirror/theme/base16-dark.css',
        'public/js/codemirror/theme/base16-light.css',
        'public/js/codemirror/theme/bespin.css',
        'public/js/codemirror/theme/blackboard.css',
        'public/js/codemirror/theme/cobalt.css',
        'public/js/codemirror/theme/colorforth.css',
        'public/js/codemirror/theme/dracula.css',
        'public/js/codemirror/theme/eclipse.css',
        'public/js/codemirror/theme/elegant.css',
        'public/js/codemirror/theme/erlang-dark.css',
        'public/js/codemirror/theme/hopscotch.css',
        'public/js/codemirror/theme/icecoder.css',
        'public/js/codemirror/theme/isotope.css',
        'public/js/codemirror/theme/lesser-dark.css',
        'public/js/codemirror/theme/liquibyte.css',
        'public/js/codemirror/theme/material.css',
        'public/js/codemirror/theme/mbo.css',
        'public/js/codemirror/theme/mdn-like.css',
        'public/js/codemirror/theme/midnight.css',
        'public/js/codemirror/theme/monokai.css',
        'public/js/codemirror/theme/neat.css',
        'public/js/codemirror/theme/neo.css',
        'public/js/codemirror/theme/night.css',
        'public/js/codemirror/theme/paraiso-dark.css',
        'public/js/codemirror/theme/paraiso-light.css',
        'public/js/codemirror/theme/paraiso-light.css',
        'public/js/codemirror/theme/pastel-on-dark.css',
        'public/js/codemirror/theme/railscasts.css',
        'public/js/codemirror/theme/rubyblue.css',
        'public/js/codemirror/theme/seti.css',
        'public/js/codemirror/theme/solarized.css',
        'public/js/codemirror/theme/the-matrix.css',
        'public/js/codemirror/theme/tomorrow-night-bright.css',
        'public/js/codemirror/theme/tomorrow-night-eighties.css',
        'public/js/codemirror/theme/ttcn.css',
        'public/js/codemirror/theme/twilight.css',
        'public/js/codemirror/theme/vibrant-ink.css',
        'public/js/codemirror/theme/xq-dark.css',
        'public/js/codemirror/theme/xq-light.css',
        'public/css/sweet-alert.zenburn'])
        .pipe(minifyCss())
        .pipe(concat('codemirror.min.css'))
        .pipe(gulp.dest('public/css_min'));

    gulp.src(['public/js/codemirror/lib/markdown-it.js',
        'public/js/codemirror/lib/markdown-it-footnote.js',
        'public/js/codemirror/lib/highlight.pack.js',
        'public/js/codemirror/lib/emojify.js',
        'public/js/codemirror/lib/codemirror.js',
        'public/js/codemirror/lib/overlay.js',
        'public/js/codemirror/lib/xml.js',
        'public/js/codemirror/lib/markdown.js',
        'public/js/codemirror/lib/gfm.js',
        'public/js/codemirror/lib/javascript.js',
        'public/js/codemirror/lib/css.js',
        'public/js/codemirror/lib/htmlmixed.js',
        'public/js/codemirror/lib/rawinflate.js',
        'public/js/codemirror/lib/rawdeflate.js',


        'public/js/codemirror/addon/edit/continuelist.js',
        'public/js/codemirror/addon/fold/foldcode.js',
        'public/js/codemirror/addon/fold/foldgutter.js',
        'public/js/codemirror/addon/fold/brace-fold.js',
        'public/js/codemirror/addon/fold/xml-fold.js',
        'public/js/codemirror/addon/fold/markdown-fold.js',
        'public/js/codemirror/addon/selection/active-line.js',
        'public/js/codemirror/addon/edit/matchbrackets.js',
        'public/js/codemirror/addon/edit/matchbrackets.js',


        'public/js/codemirror/mode/apl/apl.js',
        'public/js/codemirror/mode/asciiarmor/asciiarmor.js',
        'public/js/codemirror/mode/asterisk/asterisk.js',
        'public/js/codemirror/mode/clike/clike.js',
        'public/js/codemirror/mode/clojure/clojure.js',
        'public/js/codemirror/mode/cmake/cmake.js',
        'public/js/codemirror/mode/cobol/cobol.js',
        'public/js/codemirror/mode/coffeescript/coffeescript.js',
        'public/js/codemirror/mode/commonlisp/commonlisp.js',
        'public/js/codemirror/mode/css/css.js',
        'public/js/codemirror/mode/cypher/cypher.js',
        'public/js/codemirror/mode/d/d.js',
        'public/js/codemirror/mode/dart/dart.js',
        'public/js/codemirror/mode/diff/diff.js',
        'public/js/codemirror/mode/django/django.js',
        'public/js/codemirror/mode/dtd/dtd.js',
        'public/js/codemirror/mode/dylan/dylan.js',
        'public/js/codemirror/mode/ebnf/ebnf.js',
        'public/js/codemirror/mode/ecl/ecl.js',
        'public/js/codemirror/mode/eiffel/eiffel.js',
        'public/js/codemirror/mode/erlang/erlang.js',
        'public/js/codemirror/mode/forth/forth.js',
        'public/js/codemirror/mode/fortran/fortran.js',
        'public/js/codemirror/mode/gas/gas.js',
        'public/js/codemirror/mode/gfm/gfm.js',
        'public/js/codemirror/mode/gherkin/gherkin.js',
        'public/js/codemirror/mode/go/go.js',
        'public/js/codemirror/mode/groovy/groovy.js',
        'public/js/codemirror/mode/haml/haml.js',
        'public/js/codemirror/mode/haskell/haskell.js',
        'public/js/codemirror/mode/haxe/haxe.js',
        'public/js/codemirror/mode/htmlmixed/htmlmixed.js',
        'public/js/codemirror/mode/htmlmixed/htmlmixed.js',
        'public/js/codemirror/mode/http/http.js',
        'public/js/codemirror/mode/idl/idl.js',
        'public/js/codemirror/mode/jade/jade.js',
        'public/js/codemirror/mode/javascript/javascript.js',
        'public/js/codemirror/mode/jinja2/jinja2.js',
        'public/js/codemirror/mode/julia/julia.js',
        'public/js/codemirror/mode/julia/julia.js',
        'public/js/codemirror/mode/livescript/livescript.js',
        'public/js/codemirror/mode/lua/lua.js',
        'public/js/codemirror/mode/markdown/markdown.js',
        'public/js/codemirror/mode/mirc/mirc.js',
        'public/js/codemirror/mode/mllike/mllike.js',
        'public/js/codemirror/mode/modelica/modelica.js',
        'public/js/codemirror/mode/mumps/mumps.js',
        'public/js/codemirror/mode/nginx/nginx.js',
        'public/js/codemirror/mode/ntriples/ntriples.js',
        'public/js/codemirror/mode/octave/octave.js',
        'public/js/codemirror/mode/pascal/pascal.js',
        'public/js/codemirror/mode/pegjs/pegjs.js',
        'public/js/codemirror/mode/perl/perl.js',
        'public/js/codemirror/mode/php/php.js',
        'public/js/codemirror/mode/pig/pig.js',
        'public/js/codemirror/mode/properties/properties.js',
        'public/js/codemirror/mode/puppet/puppet.js',
        'public/js/codemirror/mode/python/python.js',
        'public/js/codemirror/mode/q/q.js',
        'public/js/codemirror/mode/r/r.js',
        'public/js/codemirror/mode/rpm/rpm.js',
        'public/js/codemirror/mode/rst/rst.js',
        'public/js/codemirror/mode/ruby/ruby.js',
        'public/js/codemirror/mode/sass/sass.js',
        'public/js/codemirror/mode/scheme/scheme.js',
        'public/js/codemirror/mode/shell/shell.js',
        'public/js/codemirror/mode/sieve/sieve.js',
        'public/js/codemirror/mode/slim/slim.js',
        'public/js/codemirror/mode/smalltalk/smalltalk.js',
        'public/js/codemirror/mode/smarty/smarty.js',
        'public/js/codemirror/mode/solr/solr.js',
        'public/js/codemirror/mode/sparql/sparql.js',
        'public/js/codemirror/mode/spreadsheet/spreadsheet.js',
        'public/js/codemirror/mode/sql/sql.js',
        'public/js/codemirror/mode/stex/stex.js',
        'public/js/codemirror/mode/stylus/stylus.js',
        'public/js/codemirror/mode/swift/swift.js',
        'public/js/codemirror/mode/tcl/tcl.js',
        'public/js/codemirror/mode/textile/textile.js',
        'public/js/codemirror/mode/tiddlywiki/tiddlywiki.js',
        'public/js/codemirror/mode/tiki/tiki.js',
        'public/js/codemirror/mode/toml/toml.js',
        'public/js/codemirror/mode/tornado/tornado.js',
        'public/js/codemirror/mode/troff/troff.js',
        'public/js/codemirror/mode/turtle/turtle.js',
        'public/js/codemirror/mode/twig/twig.js',
        'public/js/codemirror/mode/vb/vb.js',
        'public/js/codemirror/mode/vbscript/vbscript.js',
        'public/js/codemirror/mode/velocity/velocity.js',
        'public/js/codemirror/mode/verilog/verilog.js',
        'public/js/codemirror/mode/xml/xml.js',
        'public/js/codemirror/mode/xquery/xquery.js',
        'public/js/codemirror/mode/yaml/yaml.js',
        'public/js/codemirror/mode/z80/z80.js',
        'public/js/textile.min.js'
    ])
        .pipe(uglifyJs('codemirror.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));
});

gulp.task('codemirror_home', function () {
    gulp.src([
        'public/js/codemirror/lib/markdown-it.js',
        'public/js/codemirror/lib/markdown-it-footnote.js',
        'public/js/codemirror/lib/codemirror.js',
        'public/js/codemirror/lib/markdown.js'])
        .pipe(uglifyJs('codemirror_home.min.js', {outSourceMap: true}))
        .pipe(gulp.dest('public/js_min'));
});
