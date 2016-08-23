<?php namespace App\Data\Blog;

class PostHelpful extends BaseModel
{

    CONST ANONYMOUS_USER = null;
    CONST HELPFUL_YES = 1;
    CONST HELPFUL_NO = 0;
    CONST LIMIT_NOT_HELPFUL_WARNING = 20;

    // Database table used by the model
    protected $table = 'post_helpfuls';

    protected $guarded = ['id'];

    public static $createRules = [
        'helpful' => 'required',
        'post_id' => 'required',
        'user_id' => 'required',
    ];

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

}
