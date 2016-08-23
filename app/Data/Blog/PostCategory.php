<?php namespace App\Data\Blog;

class PostCategory extends BaseModel
{
    // The database table used by the model.
    protected $table = 'post_categories';

    protected $guarded = ['id'];
    protected $fillable = ['post_id', 'category_id'];

    public function post()
    {
        return $this->belongsTo('App\Data\Blog\Post');
    }

    public function category()
    {
        return $this->belongsTo('App\Data\Blog\Category');
    }
}