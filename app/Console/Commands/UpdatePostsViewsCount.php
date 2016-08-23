<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Data\Blog\Post;
use App\Data\Blog\PostView;

class UpdatePostsViewsCount extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'post:update-view-count';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update post views count to post_view tables';

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
        $this->info('Start Command:');
        $oldPostsViews = Post::lists('views_count', 'id');
        $success = 0;
        $fail = 0;
        
        foreach ($oldPostsViews as $postId => $viewsCount) {
            $postView = PostView::where('post_id', $postId)->first();
            if (!$postView) {
                $createPostViews = PostView::create([
                    'post_id' => $postId,
                    'views_count' => $viewsCount,
                ]);

                if ($createPostViews) {
                    $success++;
                    $this->info('created: 1 row effected! post_id => ' . $createPostViews->post_id . '; views_count => ' . $createPostViews->views_count);
                } else {
                    $fail++;
                    $this->error('created: 1 row error! post_id => ' . $createPostViews->post_id);
                }
            } else {
                $updatePostView = $postView->update([
                    'views_count' => $postView->views_count + $viewsCount,
                ]);

                if (isset($updatePostView) && $updatePostView) {
                    $success++;
                    $this->info('updated: 1 row effected! post_id => ' . $updatePostView->post_id . '; views_count => ' . $updatePostView->views_count);
                } else {
                    $fail++;
                    $this->error('updated: 1 row error! post_id => ' . $postId);
                }
            }
        }

        $this->info('Done!');
        $this->info('Success: ' . $success);
        $this->error('Fail: ' . $fail);
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
