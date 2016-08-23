<?php namespace App\Data\Blog;

class PostView extends BaseModel
{

    // Database table used by the model
    protected $table = 'post_views';

    protected $guarded = ['id'];

    protected $fillable = [
        'post_id',
        'views_count',
    ];

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
