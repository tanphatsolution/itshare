<?php namespace App\Data\Blog;

class Contest extends BaseModel
{

    // Database table used by the model
    protected $table = 'contests';

    protected $dates = ['term_start', 'term_end', 'term_score_end'];
    protected $guarded = ['id'];
    protected $fillable = [
        'title',
        'term_start',
        'term_end',
        'term_score_end',
        'user_id',
        'monthly_theme_subject_id',
    ];

    CONST CONTEST_PER_PAGE = 20;
    /**
     * Relationship with User model
     *
     * @return relationship
     */

    public static $createRules = [
        'name' => 'required',
        'start' => 'required',
        'end' => 'required',
    ];

    public function user()
    {
        return $this->belongsTo('App\Data\System\User');
    }

    public function domains()
    {
        return $this->belongsToMany('App\Data\Blog\Domain', 'contest_domains', 'contest_id', 'domain_id')->withTimestamps();
    }

    public function categories()
    {
        return $this->belongsToMany('App\Data\Blog\Category', 'contest_categories', 'contest_id', 'category_id')->withTimestamps();
    }

    public function MonthlyThemeSubject()
    {
        return $this->belongsTo('App\Data\Blog\MonthlyThemeSubject');
    }
}
