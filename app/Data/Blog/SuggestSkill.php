<?php namespace App\Data\Blog;

class SuggestSkill extends BaseModel
{

    // The database table used by the model.
    protected $table = 'suggest_skills';
    // The attributes which prevents the listed columns from mass assignment
    protected $guarded = ['id'];
    // The attributes specifies which attributes should be mass-assignable
    protected $fillable = ['name', 'short_name'];
}
