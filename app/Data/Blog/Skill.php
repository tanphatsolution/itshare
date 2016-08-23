<?php namespace App\Data\Blog;

class Skill extends BaseModel
{
    // The database table used by the model.
    protected $table = 'skills';

    // The attributes which prevents the listed columns from mass assignment
    protected $guarded = ['id'];

    // The attributes specifies which attributes should be mass-assignable
    protected $fillable = ['skill_category_id', 'name', 'short_name', 'default_flag'];


    public function skills()
    {
        return $this->hasMany('App\Data\Blog\UserSkill');
    }

    public function skillCategory()
    {
        return $this->belongsTo('App\Data\Blog\SkillCategory');
    }

    public function userSkills()
    {
        return $this->belongsToMany('App\Data\System\User', 'user_skills', 'skill_id', 'user_id');
    }
}