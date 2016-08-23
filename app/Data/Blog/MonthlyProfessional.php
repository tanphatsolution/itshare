<?php namespace App\Data\Blog;

class MonthlyProfessional extends BaseModel
{

    // The database table used by the model.
    protected $table = 'monthly_professionals';

    // The attributes which prevents the listed columns from mass assignment
    protected $guarded = ['id'];

    public function post()
    {
        return $this->belongsTo('App\Data\Blog\Post');
    }

    public function theme()
    {
    	return $this->belongsTo('App\Data\Blog\MonthlyTheme', 'monthly_theme_id');
    }

    public function themeSubject()
    {
    	return $this->belongsTo('App\Data\Blog\MonthlyThemeSubject');
    }
}
