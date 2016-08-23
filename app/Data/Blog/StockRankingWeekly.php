<?php namespace App\Data\Blog;


class StockRankingWeekly extends BaseModel
{
    // The database table used by the model.
    protected $table = 'stock_ranking_weekly';
    // The attributes which prevents the listed columns from mass assignment
    protected $guarded = ['id'];
    // The attributes specifies which attributes should be mass-assignable
    protected $fillable = ['post_id', 'stocks_count', 'year', 'week'];

    public function postStockRanking()
    {
        return $this->belongsTo('App\Data\Blog\Post');
    }
}
