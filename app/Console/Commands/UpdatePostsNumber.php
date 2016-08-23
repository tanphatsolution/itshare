<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Data\Blog\Category;

class UpdatePostsNumber extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'category:update-posts-number';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update posts number in a category.';

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
        $categories = Category::all();
        if ($categories) {
            foreach ($categories as $category) {
                $postsCount = $category->publishedPosts()->count();
                $category->posts_count = $postsCount;
                $category->save();
                $this->info($category->name . ' has: ' . $postsCount);
            }
            $this->info('Done!');
        } else {
            $this->info('Nothing to do!');
        }
    }
}
