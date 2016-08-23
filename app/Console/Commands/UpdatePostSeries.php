<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Data\Blog\Post;
use App\Data\Blog\PostSeries;
use App\Data\Blog\GroupSeries;
use DB;

class UpdatePostSeries extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'run:update-post-series';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update posts and series into post-series tables.';

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
        $this->info('Getting posts data...');
        $posts = Post::whereNotNull('encrypted_id')
            ->whereNull('deleted_at')
            ->get();

        DB::beginTransaction();
        $this->info('Importing posts...');

        try {
            foreach ($posts as $post) {
                PostSeries::create([
                    'post_id' => $post->id,
                    'group_series_id' => 0,
                    'language_code' => $post->language_code,
                    'published_at' => $post->published_at,
                    'created_at' => $post->created_at,
                    'updated_at' => $post->updated_at,
                ]);
            }

            DB::commit();
            $this->info('Imported posts to post_series table');
        } catch (\Exception $e) {
            DB::rollback();
            $this->error('Fail to import posts to post_series table');
        }

        $this->info('Getting series data...');
        $series = GroupSeries::all();

        DB::beginTransaction();
        $this->info('Importing series...');

        try {
            foreach ($series as $singleSeries) {
                PostSeries::create([
                    'post_id' => 0,
                    'group_series_id' => $singleSeries->id,
                    'language_code' => null,
                    'published_at' => $singleSeries->updated_at,
                    'created_at' => $singleSeries->created_at,
                    'updated_at' => $singleSeries->updated_at,
                ]);
            }

            DB::commit();
            $this->info('Imported series to post_series table');
        } catch (\Exception $e) {
            DB::rollback();
            $this->error('Fail to import series to post_series table');
        }

        $this->info('End command...');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array();
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array();
    }

}
