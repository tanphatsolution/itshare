<?php
namespace App\Services;

use App\Data\Blog\StockRankingWeekly;

class StockRankingWeeklyService
{
    public static function create($group)
    {
        foreach ($group as $key) {
            StockRankingWeekly::create([
                'post_id' => $key['post_id'],
                'stocks_count' => $key['stocks_count'],
                'year' => $key['year'],
                'week' => $key['week']
            ]);
        }
    }
}
