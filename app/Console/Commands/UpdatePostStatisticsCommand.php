<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Data\Blog\Post;

class UpdatePostStatisticsCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'post:update-statistics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Post Statistics.';

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
        $posts = Post::whereNotNull('published_at')->whereNotNull('encrypted_id')->get();
        if ($posts) {
            $this->info('Total posts: ' . $posts->count());
            foreach ($posts as $post) {
                $info = 'Post ' . $post->id . ' stocks_count before: ' . $post->stocks_count
                    . ' comments_count before: ' . $post->comments_count;
                $post->stocks_count = $post->stocks()->count();
                $post->comments_count = $post->comments()->count();
                $post->save();
                $info .= ' stocks_count after: ' . $post->stocks_count . ' comments_count after: '
                    . $post->comments_count;
                $this->info($info);
            }

            $this->info('Done update post statistics!');
        } else {
            $this->info('Nothing to do!');
        }
    }

}
