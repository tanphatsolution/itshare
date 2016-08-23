<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Data\Blog\Stock;
use App\Data\Blog\StockRankingWeekly;
use App\Data\Blog\GroupPost;
use App\Data\Blog\GroupSetting;
use Carbon\Carbon;
use DB;
use Log;

class StockRankingWeeklyCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'saveDataToTable:stock_ranking_weekly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $lastMonday = Carbon::now()->subWeek()->startOfWeek();
        $lastSunday = Carbon::now()->subWeek()->endOfWeek();
        $week = $lastMonday->weekOfYear;
        $year = Carbon::now()->subWeek()->year;

        $getstocksTableName = Stock::getTableName();
        $stocks = $this->getStocks($getstocksTableName, $lastMonday, $lastSunday);
        if ($stocks) {
            foreach ($stocks as $stock) {
                $stockRanking = StockRankingWeekly::where('post_id', $stock->post_id)
                    ->where('week', $week)
                    ->where('year', $year)->first();
                if ($stockRanking) {
                    $stockRanking->stocks_count = $stock->stocksCount;
                    $stockRanking->update();
                } else {
                    StockRankingWeekly::create([
                        'post_id' => $stock->post_id,
                        'stocks_count' => $stock->stocksCount,
                        'year' => $year,
                        'week' => $week,
                    ]);
                }
            }
        } else {
            Log::info('Undefined stocks in week:' . $week);
        }
    }

    public function getStocks($tableName, $lastMonday, $lastSunday)
    {
        $secretGroupsId = GroupSetting::where('privacy_flag', GroupSetting::PRIVACY_PRIVATE)->lists('group_id');
        $secretPostsId = GroupPost::whereIn('group_id', $secretGroupsId)
            ->whereNotNull('post_id')
            ->lists('post_id');

        $stocks = DB::table('stocks')->select(DB::raw($tableName . 'post_id, count(*) as stocksCount'))
            ->whereBetween($tableName . 'created_at', [$lastMonday, $lastSunday])
            ->whereNotIn('post_id', $secretPostsId)
            ->groupBy('post_id')
            ->orderBy('stocksCount', 'desc')
            ->get();
        return $stocks;
    }

}
