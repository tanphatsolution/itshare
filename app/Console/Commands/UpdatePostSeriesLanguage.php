<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Data\Blog\PostSeries;
use App\Data\Blog\GroupSeries;

class UpdatePostSeriesLanguage extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'run:update-post-series-language';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update series language.';

    /**
     * Create a new command instance.
     *
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
        $series = PostSeries::where('post_series.group_series_id', '!=', 0)
            ->join('group_series', 'group_series.id', '=', 'post_series.group_series_id')
            ->join('settings', 'settings.user_id', '=', 'group_series.user_id')
            ->where(function ($query) {
                $query->whereNull('post_series.language_code')
                    ->orWhere('post_series.language_code', '');
            })
            ->get();
        $countSeries = $series->count();

        foreach ($series as $item) {
            PostSeries::where('group_series_id', $item->group_series_id)
                ->update(['language_code' => $item->default_post_language]);
            GroupSeries::where('id', $item->group_series_id)
                ->where(function ($query) {
                    $query->whereNull('language_code')
                        ->orWhere('language_code', '');
                })
                ->update(['language_code' => $item->default_post_language]);
        }

        $this->info('Done! ' . $countSeries . ' post series updated.');
    }
}

