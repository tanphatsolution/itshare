<?php namespace App\Data\Blog;

class ContestCategory extends BaseModel
{
    // The database table used by the model.
    protected $table = 'contest_categories';

    protected $guarded = ['id'];
    protected $fillable = [
        'contest_id',
        'category_id',
        'created_at',
        'updated_at',
    ];
}
