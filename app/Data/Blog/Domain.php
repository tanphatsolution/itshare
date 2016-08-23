<?php namespace App\Data\Blog;

class Domain extends BaseModel
{

    // Database table used by the model
    protected $table = 'domains';

    protected $guarded = ['id'];
    protected $fillable = ['name'];

    public function contests()
    {
        return $this->belongsToMany('Contest', 'contest_domains', 'domain_id', 'contest_id');
    }
}
