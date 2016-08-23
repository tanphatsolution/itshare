<?php namespace App\Data\Blog;

class PostSeries extends BaseModel
{
    // The database table used by the model.
    protected $table = 'post_series';

    protected $guarded = ['id'];
    protected $fillable = [
        'post_id',
        'group_series_id',
        'language_code',
        'published_at',
        'created_at',
        'updated_at',
    ];

    public function post()
    {
        return $this->belongsTo('App\Data\Blog\Post');
    }

    public function series()
    {
        return $this->belongsTo('App\Data\Blog\GroupSeries', 'group_series_id');
    }
}
