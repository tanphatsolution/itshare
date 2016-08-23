<?php namespace App\Data\Blog;

class FollowCategory extends BaseModel
{
    // The database table used by the model.
    protected $table = 'follow_categories';

    protected $guarded = ['id'];
    protected $fillable = ['category_id', 'user_id'];


    public function category()
    {
        return $this->belongsTo('App\Data\Blog\Category', 'category_id');
    }

    public function follower()
    {
        return $this->belongsTo('App\Data\System\User', 'user_id');
    }
}