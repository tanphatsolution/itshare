<?php
namespace App\Data\Blog;

class MonthlyTheme extends BaseModel
{

    // The database table used by the model.
    protected $table = 'monthly_themes';

    // The attributes which prevents the listed columns from mass assignment
    protected $guarded = ['id'];

    public function themeLanguages()
    {
        $filterLanguageCode = getThemeLanguage();
        return $this->hasMany('App\Data\Blog\MonthlyThemeLanguage')->where('language_code', $filterLanguageCode);
    }

    public function themeSubject()
    {
        return $this->belongsTo('App\Data\Blog\MonthlyThemeSubject', 'monthly_theme_subject_id');
    }
}
