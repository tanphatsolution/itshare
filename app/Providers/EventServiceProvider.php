<?php namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\PostCommentNotificationHandler;
use App\Events\CommentMentionNotificationHandler;
use App\Events\UserFollowNotificationHandler;
use App\Events\FeedbackNotificationHandler;
use App\Events\FollowingUserPostNotificationHandler;
use App\Events\GroupAddMemberNotificationHandler;
use App\Events\GroupApprovePostNotificationHandler;
use App\Events\GroupPostNotificationHandler;
use App\Events\LogJoinGroupByUserHandler;
use App\Events\LogKeywordSearchByUserHandler;
use App\Events\LogViewPostDetailHandler;
use App\Events\LoginSuccessHandler;
use App\Events\LogoutSuccessHandler;
use App\Events\PostMentionNotificationHandler;
use App\Events\PostStockNotificationHandler;
use App\Events\ReportNotificationHandler;
use App\Events\ViewGroupSeriesHandler;
use App\Events\ViewPostHandler;

class EventServiceProvider extends ServiceProvider {

    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        PostCommentNotificationHandler::EVENT_NAME => [
            'App\Events\PostCommentNotificationHandler',
        ],

        CommentMentionNotificationHandler::EVENT_NAME => [
            'App\Events\CommentMentionNotificationHandler',
        ],

        UserFollowNotificationHandler::EVENT_NAME => [
            'App\Events\UserFollowNotificationHandler',
        ],

        FeedbackNotificationHandler::EVENT_NAME => [
            'App\Events\FeedbackNotificationHandler',
        ],

        FollowingUserPostNotificationHandler::EVENT_NAME => [
            'App\Events\FollowingUserPostNotificationHandler',
        ],

        GroupAddMemberNotificationHandler::EVENT_NAME => [
            'App\Events\GroupAddMemberNotificationHandler',
        ],

        GroupApprovePostNotificationHandler::EVENT_NAME => [
            'App\Events\GroupApprovePostNotificationHandler',
        ],

        GroupPostNotificationHandler::EVENT_NAME => [
            'App\Events\GroupPostNotificationHandler',
        ],

        LogJoinGroupByUserHandler::EVENT_NAME => [
            'App\Events\LogJoinGroupByUserHandler',
        ],

        LogKeywordSearchByUserHandler::EVENT_NAME => [
            'App\Events\LogKeywordSearchByUserHandler',
        ],

        LogViewPostDetailHandler::EVENT_NAME => [
            'App\Events\LogViewPostDetailHandler',
        ],

        LoginSuccessHandler::EVENT_NAME => [
            'App\Events\LoginSuccessHandler',
        ],

        LogoutSuccessHandler::EVENT_NAME => [
            'App\Events\LogoutSuccessHandler',
        ],

        PostMentionNotificationHandler::EVENT_NAME => [
            'App\Events\PostMentionNotificationHandler',
        ],

        PostStockNotificationHandler::EVENT_NAME => [
            'App\Events\PostStockNotificationHandler',
        ],

        ReportNotificationHandler::EVENT_NAME => [
            'App\Events\ReportNotificationHandler',
        ],

        ViewGroupSeriesHandler::EVENT_NAME => [
            'App\Events\ViewGroupSeriesHandler',
        ],

        ViewPostHandler::EVENT_NAME => [
            'App\Events\ViewPostHandler',
        ]
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }

}
