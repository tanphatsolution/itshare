<?php namespace App\Data\Blog;

class SkillCategory extends BaseModel
{
    // The database table used by the model.
    protected $table = 'skill_categories';

    // The attributes which prevents the listed columns from mass assignment
    protected $guarded = ['id'];

    // The attributes specifies which attributes should be mass-assignable
    protected $fillable = ['name', 'short_name'];

    public function skills()
    {
        return $this->hasMany('App\Data\Blog\Skill');
    }
}