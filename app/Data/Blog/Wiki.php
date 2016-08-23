<?php namespace App\Data\Blog;

class Wiki extends BaseModel
{

    // The database table used by the model.
    protected $table = 'wiki';

    // The attributes which prevents the listed columns from mass assignment
    protected $guarded = ['id'];

    protected $fillable = [
        'parent_id',
        'group_id',
        'post_id',
        'title',
    ];

    public function post()
    {
        return $this->belongsTo('App\Data\Blog\Post');
    }

}
