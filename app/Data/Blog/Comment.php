<?php namespace App\Data\Blog;

use App\Events\CommentMentionNotificationHandler;
use Event;
use App\Events\PostCommentNotificationHandler;
use Validator;
use GrahamCampbell\Markdown\Facades\Markdown;

class Comment extends BaseModel
{

    // Database table used by the model
    protected $table = 'comments';

    protected $guarded = ['id'];

    /**
     * Validate input data for stock
     *
     * @return Validator
     */
    public static function validateComment($inputData = [])
    {
        // Rules for validation of data
        $rules = [
            'user_id' => 'required',
            'post_id' => 'required',
            'content' => 'required',
        ];

        $validator = Validator::make($inputData, $rules);
        return $validator;
    }

    /**
     * Relationship with User model
     *
     * @return relationship
     */
    public function user()
    {
        return $this->belongsTo('App\Data\System\User');
    }

    /**
     * Relationship with Post model
     *
     * @return relationship
     */
    public function post()
    {
        return $this->belongsTo('App\Data\Blog\Post');
    }

    /**
     * Return Comment's content that parsed down.
     * @return string
     */
    public function getParsedContent()
    {
        $escaped = markdown_escape($this->content);
        $render = new \Parsedown();
        $render = $render->text($escaped);
        return cleanHtml($render);
    }

    public static function boot()
    {
        parent::boot();
        // After create
        static::created(function ($comment) {
            $comment->post->increment('comments_count');
            Event::fire(PostCommentNotificationHandler::EVENT_NAME, $comment);
            Event::fire(CommentMentionNotificationHandler::EVENT_NAME, $comment);
        });

        // After update
        static::updated(function ($comment) {
            Event::fire(CommentMentionNotificationHandler::EVENT_NAME, $comment);
        });

        // After delete
        static::deleted(function ($comment) {
            $comment->post->decrement('comments_count');
        });
    }
}
