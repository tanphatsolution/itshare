<?php namespace App\Data\Blog;

class ContestDomain extends BaseModel
{
    // Database table used by the model
    protected $table = 'contest_domains';

    protected $guarded = ['id'];
    protected $fillable = ['contest_id', 'domain_id'];
}
