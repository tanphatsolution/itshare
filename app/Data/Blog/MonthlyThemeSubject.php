<?php
namespace App\Data\Blog;


class MonthlyThemeSubject extends BaseModel
{

    // The database table used by the model.
    protected $table = 'monthly_theme_subjects';

    // The attributes which prevents the listed columns from mass assignment
    protected $guarded = ['id'];

    public function themes()
    {
        return $this->hasMany('App\Data\Blog\MonthlyTheme');
    }

    public function professionals()
    {
        return $this->hasMany('App\Data\Blog\MonthlyProfessional');
    }

    public function contests()
    {
        return $this->hasMany('App\Data\Blog\Contest');
    }
}
