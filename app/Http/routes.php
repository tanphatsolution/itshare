<?php

use Illuminate\Routing\Router;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
// Validate all post request submitted with the CSRF filter

// Define routes to handle every action in Home controller
Route::get('/', ['as'=>'getTopPage', 'uses'=>'HomeController@getTopPage']);
Route::group(['prefix' => '{locale}'], function () {
    Route::get('/', 'HomeController@getTopPage')->where(['locale' => '[a-z]{2}']);
});


Route::get('theme/{locale}/{themeName}', 'HomeController@getTop');
Route::controller('home', 'HomeController');
Route::get('/register/finish', 'UsersController@socialRegistration');
Route::get('sitemap', 'HomeController@getSitemap');


/* ---------------------- Define routes to handle every action in User controller -----------------------------------------*/
Route::get('u/{username}/following',            ['as' => 'getUserFollowing',                'uses' => 'UsersController@getFollowing']);
Route::get('u/{username}/followers',            ['as' => 'getUserFollowers',                'uses' => 'UsersController@getFollowers']);
Route::get('u/{username}/posts',                ['as' => 'getUserPosts',                    'uses' => 'UsersController@getPosts']);
Route::get('u/{username}/clip',                 ['as' => 'getUserClips',                    'uses' => 'UsersController@getStock']);
Route::get('u/{username}/drafts',               ['as' => 'getUserDraft',                    'uses' => 'UsersController@getDraft']);
Route::get('u/{username}',                      ['as' => 'getUser',                         'uses' => 'UsersController@getShow']);
Route::get('login',                             ['as' => 'getLogin',                        'uses' => 'UsersController@getLogin']);
Route::get('users',                             ['as' => 'getUserIndex',                    'uses' => 'UsersController@getIndex']);
Route::get('users/statistic',                   ['as' => 'getUserStatistic',                'uses' => 'UsersController@getStatistic']);
Route::get('u/{username}/following-categories', ['as' => 'getUserFollowingCate',            'uses' => 'UsersController@getFollowingCategories']);

Route::post('users/resendActiveEmail',          ['as' => 'postUserResendActiveEmail',   'uses' => 'UsersController@resendActiveEmail']);
Route::post('users/changeDefaultPostLang',      ['as' => 'postUserChangeDefaultPostLang',   'uses' => 'UsersController@changeUserDefaultPostLanguage']);

Route::controller('users', 'UsersController');
Route::resource('users', 'UsersController');

/* =========================================================================================================================*/


/* ----------------- Define routes to handle every action in Post controller ------------------------------------------------*/
Route::get('{username}/posts/{encryptedId}/{seoUrl?}',  ['as' => 'post.detail',     'uses' => 'PostsController@show']);
Route::get('posts/list',                        ['as' => 'getListPost',     'uses' => 'PostsController@getList']);
Route::get('posts/listclip',                    ['as' => 'getListClip',     'uses' => 'PostsController@getListUserStock']);
Route::get('posts/filter',                      ['as' => 'getPostFillter',  'uses' => 'PostsController@filter']);
Route::get('posts/counter',                     ['as' => 'getPostCounter',  'uses' => 'PostsController@counter']);
Route::get('posts/create/{themeId}',            ['as' => 'getPostCreateTheme', 'uses' => 'PostsController@create']);
Route::get('posts/create',                      ['as' => 'getPostCreate',   'uses' => 'PostsController@create']);
Route::get('posts/{encryptedId}/edit',          ['as' => 'post.edit',       'uses' => 'PostsController@edit']);
Route::get('posts/getListUserStockModal',       ['as' => 'getListUserStock','uses' => 'PostsController@getListUserStockModal']);
Route::get('posts/statistic',                   ['as' => 'getPostStatistic','uses' => 'PostsController@getStatistic']);
Route::get('posts/{wall?}',                     ['as' => 'getPostWall',     'uses' => 'PostsController@getIndex']);
Route::get('posts/{wall}/{filter}',             ['as' => 'getPostWallFillter','uses' => 'PostsController@getIndex']);
Route::get('posts',                             ['as' => 'getPostIndex',    'uses' => 'PostsController@getIndex']);
Route::get('posts/view/{param}',                ['as' => 'getPostView',     'uses' => 'PostsController@view']);
Route::get('drafts/{encryptedId}',              ['as' => 'post.draft',          'uses' => 'PostsController@draft']);
Route::get('drafts',                            ['as' => 'getDrafts',           'uses' => 'PostsController@draft']);
Route::get('posts/filtersInAll/{filter}/{pageCount}/{filterBy}', ['as' => 'getPostFillterInAll','uses' => 'PostsController@filtersInAll']);
Route::get('related/load',                        ['as' => 'related',     'uses' => 'PostsController@getRelated']);

Route::post('posts/preview',                    ['as' => 'postPostPreview',     'uses' => 'PostsController@preview']);
Route::post('posts/create',                     ['as' => 'postPostCreate',      'uses' => 'PostsController@postCreate']);
Route::post('posts/autoSaveDraft',              ['as' => 'postAutoSaveDrafts',  'uses' => 'PostsController@autoSaveDraft']);
Route::post('posts/save',                       ['as' => 'postSavePost',        'uses' => 'PostsController@save']);
Route::post('posts/changeLanguage',             ['as' => 'postPostChangLang',   'uses' => 'PostsController@changeLanguage']);
Route::patch('posts/{encryptedId}/unpublished', ['as' => 'patchPostUnpublished','uses' => 'PostsController@unpublished']);
Route::resource('posts', 'PostsController');
/*=========================================================================================================================*/



/*-------------------------- Define routes to handle every action in Feedback controller --------------------------------*/

Route::get('feedbacks/preview',         ['as' => 'getFeedbackPreview',      'uses' => 'FeedbacksController@getPreview']);
Route::get('feedbacks/create',          ['as' => 'getFeedbackCreate',       'uses' => 'FeedbacksController@getCreate']);
Route::post('feedbacks/reply',          ['as' => 'postFeedbackReply',       'uses' => 'FeedbacksController@postReply']);
Route::post('feedbacks/update-status',  ['as' => 'postFeedbackUpdateStatus','uses' => 'FeedbacksController@postIndex']);
Route::resource('feedbacks', 'FeedbacksController');
/*======================================================================================================================*/



/*-------------------- Define routes to handle every action in Category controller -------------------------------------*/

Route::get('categories/export',         ['as' => 'getCategoryExport',   'uses'  =>  'CategoriesController@getExport']);
Route::get('categories/import',         ['as' => 'getCategoryImport',   'uses'  =>  'CategoriesController@getImport']);
Route::get('categories/view',           ['as' => 'getCategoryView',     'uses'  =>  'CategoriesController@getView']);
Route::get('categories/{name}/{tab}',   ['as' => 'getCategoryNameTag',  'uses'  =>  'CategoriesController@show']);
Route::post('categories/export',        ['as' => 'postCategoryExport',  'uses'  =>  'CategoriesController@postExport']);
Route::post('categories/import',        ['as' => 'postCategoryImport',  'uses'  =>  'CategoriesController@postImport']);
Route::post('categories/restore',       ['as' => 'postCategoryRestore', 'uses'  =>  'CategoriesController@restore']);
Route::resource('categories', 'CategoriesController');
Route::resource('categoryfilters', 'CategoryFiltersController');
/*======================================================================================================================*/


/*-------------------- Define routes to handle every action in  controller ----------------------------*/
Route::get('clip/count',                ['as' => 'getClipCount',        'uses' => 'StocksController@count']);
Route::get('quick-search',              ['as' => 'getQuickSearch',      'uses' => 'SearchsController@getQuickSearch']);
Route::get('profiles/getSuggestCities', ['as' => 'getProfileSuggestCity','uses' => 'ProfilesController@getSuggestCities']);
Route::get('search/{keyword?}',         ['as' => 'getSearch',           'uses' => 'SearchsController@getIndex'])->where('keyword', '.*');

Route::post('comments/preview',         ['as' => 'postCommentPreview',  'uses' => 'CommentsController@preview']);
Route::post('comments/load',         ['as' => 'loadcomment',  'uses' => 'CommentsController@loadComment']);
Route::post('search',                   ['as' => 'postSearch',          'uses' => 'SearchsController@postIndex']);
Route::post('process',                  ['as' => 'postReportProcess',   'uses' => 'ReportsController@process']);
Route::post('notifications/fetch',      ['as' => 'postNotificationFetch','uses' => 'NotificationsController@fetch']);

Route::resource('categoryfollow', 'CategoryFollowsController');
Route::resource('comments', 'CommentsController');
Route::resource('clip', 'StocksController');
Route::resource('notifications', 'NotificationsController');
Route::resource('post_category', 'PostCategoriesController');
Route::resource('posthelpfuls', 'PostHelpfulsController');
Route::resource('relationships', 'UserRelationshipsController');
Route::resource('reports', 'ReportsController');
Route::resource('user_skills', 'UserSkillsController');
Route::resource('/contests', 'ContestController');

Route::controller('tag',        'TagsController');
Route::controller('settings',   'SettingsController');
Route::controller('socials',    'SocialsController');
Route::controller('password',   'PasswordController');
Route::controller('role',       'RoleController');
Route::controller('server', 'ServerController');
Route::controller('profiles', 'ProfilesController');
Route::controller('images', 'ImageController');
Route::controller('account', 'AccountController');
/*=====================================================================================================================*/


//Define routes to terms of service
Route::get('/terms/{lang}', ['as' => 'getTermServiceWithLang',          'uses' => 'UsersController@getTermsOfService']);
Route::get('/terms',        ['as' => 'getTermServiceWithDefaultLang',   'uses' => 'UsersController@getTermsOfService']);


/*------------------------------------- Define routes to handle actions in Category Request --------------------------------*/
Route::resource('categoryrequests', 'CategoryRequestsController');
Route::get('categoryrequests/view',     ['as' => 'getCategoryRequestView',  'uses' => 'CategoryRequestsController@getView']);
Route::get('categoryrequests',          ['as' => 'getCategoryRequest',      'uses' => 'CategoryRequestsController@getView']);
Route::post('categoryrequests/restore', ['as' => 'postRestoreCateRequest',  'uses' => 'CategoryRequestsController@restore']);
Route::post('categoryrequests/accept',  ['as' => 'postAcceptCateRequest',   'uses' => 'CategoryRequestsController@accept']);
Route::post('categoryrequests/reject',  ['as' => 'postRejectCateRequest',   'uses' => 'CategoryRequestsController@reject']);
/*===========================================================================================================================*/


/*------------------------------setting default language in post's content -------------------------------------------------*/
Route::get('languages',             ['as'=>'getPostLang', 'uses' => 'UserPostLanguagesController@getPostLanguages']);
Route::get('languages/statistic',   ['as'=>'getStatistic','uses' => 'UserPostLanguagesController@getStatistic']);

Route::post('languages',            ['as' => 'postPostLang','uses' => 'UserPostLanguagesController@postLanguages']);
Route::post('languages/settings',   ['as' => 'postLangSetting','uses' => 'UserPostLanguagesController@settingLanguage']);
Route::post('languages/settings/filterPostLanguages',   ['as' => 'postSettingFillterPostLang', 'uses' => 'UserPostLanguagesController@settingFilterPostLanguages']);
Route::post('languages/settings/changeLanguageSystem',  ['as' => 'postSystemlangSetting','uses' => 'UserPostLanguagesController@settingLanguageSystem']);
Route::post('languages/settings/changeDefaultPostLang', ['as' => 'postSetDefaultPostLang','uses' => 'UserPostLanguagesController@settingDefaultPostLang']);
Route::post('languages/settings/changeTopPageLang',     ['as' => 'postChangeTopPageLang','uses' => 'UserPostLanguagesController@settingTopPageLang']);
/*==========================================================================================================================*/


/*------------------------------------------------ Setting theme ----------------------------------------------------------*/
Route::get('monthlythemesubjects/getListMonthlyThemes', ['as' => 'getMonthyThemes', 'uses' => 'MonthlyThemeSubjectsController@getListMonthlyThemes']);
Route::get('monthlythemesubjects/backnumber/{month}/{year}', ['as' => 'getBackThemesSubjectMonthYear', 'uses' => 'MonthlyThemeSubjectsController@getView']);
Route::get('monthlythemesubjects/backnumber', ['as' => 'getBackThemeSubject', 'uses' => 'MonthlyThemeSubjectsController@getView']);
Route::get('monthlythemesubjects/checkBackNumber', ['as' => 'getCheckThemeBack', 'uses' => 'MonthlyThemeSubjectsController@checkBackNumber']);
Route::post('monthlythemesubjects/checkInput', ['as' => 'postCheckThemeInput', 'uses' => 'MonthlyThemeSubjectsController@checkInput']);
Route::post('monthlythemesubjects/postUpdate', ['as' => 'postThemeUpdate', 'uses' => 'MonthlyThemeSubjectsController@postUpdate']);

// Define routes to handle every anction in MonthlyThemesController
Route::get('themes/back-number',                        ['as' => 'getBackNumber',   'uses' => 'MonthlyThemesController@backNumber']);
Route::get('themes/professionals',                      ['as' => 'getProfessional', 'uses' => 'MonthlyThemesController@professionals']);
Route::get('theme/{themeName}/{subThemeName}/{tab?}',   ['as' => 'getSubThemeTab', 'uses' => 'MonthlyThemesController@categories']);
Route::get('theme/{locale?}/{themeName}/{subThemeName}/{tab?}', ['as' => 'subThemeNameTabLocale', 'uses' => 'MonthlyThemesController@categories']);
Route::resource('monthlythemesubjects', 'MonthlyThemeSubjectsController');
/*==================================================================================================================*/


/*------------------------------------------ Define routes Group :) -----------------------------------------------------*/
Route::get('groups/checkGroupPrivacy',      ['as' => 'getCheckGroupPrivacy','uses' => 'GroupsController@checkGroupPrivacy']);
Route::get('groups/getUsersList',           ['as' => 'getUsersList',        'uses' => 'GroupsController@getUsersList']);
Route::get('groups/getUsersListWhenCreate', ['as' => 'getUsersWhenCreate',  'uses' => 'GroupsController@getUsersListWhenCreate']);
Route::get('groups/userGroups',             ['as' => 'getUserGroups',       'uses' => 'GroupsController@getAllUserGroups']);

Route::post('groups/checkInput',                ['as' => 'postCheckGroupInput', 'uses' => 'GroupsController@checkInput']);
Route::post('groups/checkShortname',            ['as' => 'postCheckSortName',   'uses' => 'GroupsController@checkShortname']);
Route::post('groups/editGroupByClick',          ['as' => 'postEditGroup',       'uses' => 'GroupsController@editGroupByClick']);
Route::post('groups/changeRole',                ['as' => 'postChangeRole',      'uses' => 'GroupsController@changeRole']);
Route::post('groups/uploadImage/{typeImage}',   ['as' => 'postUploadImage',     'uses' => 'GroupsController@uploadImage']);
Route::post('groups/addMember',                 ['as' => 'postAddmember',       'uses' => 'GroupsController@addMember']);
Route::post('groups/removeMember',              ['as' => 'postRemoveMember',    'uses' => 'GroupsController@removeMember']);
Route::post('groups/addMemberWhenCreate',       ['as' => 'postAddmemberWhenCreate', 'uses' => 'GroupsController@addMemberWhenCreate']);
Route::post('groups/slug',       ['as' => 'groups.slug', 'uses' => 'GroupsController@generateSlug']);
/*===========================================================================================================================*/


/*---------------------------- Define routes to handle every action in GroupController --------------------------------------*/
Route::get('groups/{encryptedId}/search',   ['as' => 'getGroupSearch',      'uses' => 'GroupsController@search']);
Route::get('groups/create/{encryptedId}',   ['as' => 'getGroupEncryptCreate','uses' => 'GroupsController@create']);
Route::get('groups/contents',               ['as' => 'getGroupContent',     'uses' => 'GroupsController@getRemoteGroupContents']);
Route::get('groupUsers',                    ['as' => 'getGroupMember',      'uses' => 'GroupsController@getGroupMembers']);
Route::get('groups/follow',                 ['as' => 'getGroupFollow',      'uses' => 'GroupsController@followUsers']);

Route::get('groups/{encryptedGroupId}/groupseries/create', ['as' => 'getGroupSerieCreate', 'uses' => 'GroupSeriesController@create']);
Route::get('groups/{encryptedGroupId}/groupseries/{groupSeriesId}', ['as' => 'getGroupSeries', 'uses' => 'GroupSeriesController@show']);
Route::get('groups/{encryptedGroupId}/groupseries/{groupSeriesId}/edit', ['as' => 'getGroupSeriesEdit', 'uses' => 'GroupSeriesController@edit']);
Route::get('groups/{encryptedId}/categories/{categoryShortName}',['as' => 'getGroupCategory', 'uses' => 'GroupCategoriesController@getGroupCategoryPosts']);
Route::get('groups/{encryptId}/filter/{filter}',                    ['as' => 'getGroupFillter','uses' => 'GroupsController@show']);
Route::get('groupseries/counter',                   ['as' => 'getGroupSeriesCounter', 'uses' => 'GroupSeriesController@counter']);

Route::post('groups/{encryptedId}/search',  ['as' => 'postGroupEncryptSearch',  'uses' => 'GroupsController@search']);
Route::post('groups/posts/approve',         ['as' => 'postGroupPostApprove',    'uses' => 'GroupsController@approvePost']);
Route::post('groups/posts/deny',            ['as' => 'postGroupPostDeny',       'uses' => 'GroupsController@denyPost']);
Route::post('groups/users/approve',         ['as' => 'postGroupUserApprove',    'uses' => 'GroupsController@approveUser']);
Route::post('groups/join',                  ['as' => 'postGroupJoin',           'uses' => 'GroupsController@join']);
Route::post('groups/postsUsers/approveFromNotify', ['as' => 'postGroupUserApproveNoti', 'uses' => 'GroupsController@approveUserAndPostFromNotify']);
Route::delete('groups/{encryptId}/delete',  ['as' => 'postGroupEncryptDelete',  'uses' => 'GroupsController@deleteGroup']);
Route::delete('groups/{encryptId}/leave',   ['as' => 'deleteGroupEncryptLeave', 'uses' => 'GroupsController@leaveGroup']);
//Group series
Route::post('groupseries/item',             ['as' => 'postGroupSeriesItem',     'uses' => 'GroupSeriesController@addItem']);
Route::post('groups/{encryptedGroupId}/groupseries', ['as' => 'postGroupSeries','uses' => 'GroupSeriesController@store']);
Route::post('groups/{encryptedGroupId}/groupseries/{groupSeriesId}', ['as' => 'postGroupSeriesId', 'uses' => 'GroupSeriesController@update']);
Route::post('groupseries/checkInput',       ['as' => 'postGroupSeriesCheckInput','uses' => 'GroupSeriesController@checkInput']);
Route::post('groupseries/checkFormatURL',   ['as' => 'postGroupSeriesCheckURL',  'uses' => 'GroupSeriesController@checkFormatURL']);
Route::post('groupseries/getElementBtnByLinkType', ['as' => 'getGroupSeriesElementByLinkType', 'uses' => 'GroupSeriesController@getElementBtnByLinkType']);

Route::post('groups/memberGroupInSeries',   ['as' => 'postGroupMemberInSeries', 'uses' => 'GroupsController@getMemberGroupInSeries']);
Route::resource('groups', 'GroupsController');
/*===============================================================================================================================*/


// Social Count API
Route::get('api/get_social_count',          ['as' => 'getApiSocialCount',       'uses' => 'SocialsController@getCount']);


/*------------------------------------------ Oauth2 --------------------------------------------------------------------------- */
Route::get('oauth/authorize',               ['as' => 'oauth.authorize.get', 'uses' => 'OAuthController@getAuthorize']);
Route::get('oauth/apps',                    ['as' => 'getAuthApp',          'uses' => 'OAuthController@getApps']);
Route::get('oauth/apps/create',             ['as' => 'getAppCreate',        'uses' => 'OAuthController@createApp']);
Route::get('oauth/apps/edit/{clientId}',    ['as' => 'getEditApp', 'uses' => 'OAuthController@editApp']);

Route::post('oauth/access_token',   ['as' => 'postAuthToken',           'uses' => 'OAuthController@postAccessToken']);
Route::post('oauth/login',          ['as' => 'postLogin',               'uses' => 'OAuthController@postLogin']);
Route::post('oauth/authorize',      ['as' => 'oauth.authorize.post',    'uses' => 'OAuthController@postAuthorize']);
Route::post('oauth/apps/storeApp',  ['as' => 'postStoreApp',            'uses' => 'OAuthController@storeApp']);
Route::patch('oauth/apps/update',   ['as' => 'patchUpdateApp',          'uses' => 'OAuthController@updateApp']);
Route::delete('oauth/apps/delete',  ['as' => 'deleteDestroyApp',        'uses' => 'OAuthController@destroyApp']);
/*=============================================================================================================================*/


/*------------------------------------- Define routes rssfeed -----------------------------------------------------------------*/
Route::get('rss/{language}/allposts',               ['as' => 'getRSSAllPost',    'uses' => 'RssController@getRssAllPosts']);
Route::get('rss/following/{username}',              ['as' => 'getRSSUserFollow', 'uses' => 'RssController@getRssByUserFollowing']);
Route::get('rss/group/{slug}',                      ['as' => 'getRSSByGroup',    'uses' => 'RssController@getRssByGroup']);
Route::get('rss/user/{username}',                   ['as' => 'getRSSByUser',     'uses' => 'RssController@getRssByUser']);
Route::get('rss/category/{category_short_name}',    ['as' => 'getRSSByCategory', 'uses' => 'RssController@getRssByCategory']);
Route::get('rss/topclip',                           ['as' => 'getRSSByTopClip',  'uses' => 'RssController@getRssByTopClip']);
Route::get('rss/topposts',                          ['as' => 'getRSSByTopPost',  'uses' => 'RssController@getRssByTopPosts']);
Route::get('rss/newposts',                          ['as' => 'getRSSByNewPost',  'uses' => 'RssController@getRssByNewPosts']);
Route::get('rss/helpfulposts',                      ['as' => 'getRSSByHelpFullPost', 'uses' => 'RssController@getRssByHelpfulPosts']);
/*===============================================================================================================================*/


//Define routes write logs
Route::post('writelog', ['as' => 'postWriteLog', 'uses' => 'LogsController@store']);


/*-------------------------------------- Define routes test magazine -----------------------------------------------------------*/
Route::get('settime',       ['as' => 'getSetTime',      'uses' => 'SetTimeController@getForm']);
Route::post('settime',      ['as' => 'postSetTime',     'uses' => 'SetTimeController@postForm']);
Route::post('clearoffset',  ['as' => 'postClearOffset', 'uses' => 'SetTimeController@clearOffset']);
Route::post('readlog',      ['as' => 'postReadLog',     'uses' => 'SetTimeController@readLog']);
Route::post('sendemail',    ['as' => 'postSendEMail',   'uses' => 'SetTimeController@sendEmail']);
Route::post('postuser',     ['as' => 'postPostUser',    'uses' => 'SetTimeController@postUser']);
Route::post('getuser',      ['as' => 'postGetuser',     'uses' => 'SetTimeController@getUser']);
/*==============================================================================================================================*/


/*-------------------------------------- Define routes for Contest -------------------------------------------------------------*/
Route::get('/contests/{contest}/rankings',          ['as' => 'getRanking',            'uses' => 'ContestController@show']);
Route::get('/contests/{contest}/articles/{user}',   ['as' => 'getContestArticleUser', 'uses' => 'ContestController@getArticles']);
/*==============================================================================================================================*/


// convert to slug
Route::get('/post/convert-to-slug',['as' => 'convert','uses' => 'SanitizesController@ConvertIdPostToSlug']);
Route::get('/group/convert-to-slug',['as' => 'convert','uses' => 'SanitizesController@ConvertIdGroupToSlug']);



// Routes for faq
Route::group(['prefix' => 'faq', 'namespace' => 'Faq'], function(Router $router) {

    // Home page
    $router->get('/', 'HomeController@index');
    $router->resource('questions', 'QuestionsController', ['except' =>['show']]);
    $router->get('/ajax/question', 'HomeController@ajaxGetQuestion');
    $router->get('/ajax/user-ranking', 'HomeController@ajaxGetUserRanking');




    $router->get('/questions/{id}/{slug?}', ['as' => 'faq.questions.show', 'uses' => 'QuestionsController@show'])->where('id', '[0-9]+');
    // get ranking
    $router->get('/ranking', function(){
        return Response::view('faq.ranking.ranking');
    });

    // get user_profile
    $router->get('/user_profile', function(){
        return Response::view('faq.user.user_profile');
    });
});
