<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Data\Blog\Post;
use App\Data\Blog\Category;
use App\Data\Blog\CategoryFilter;
use App;
use URL;
use File;

class CreateSitemapCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'sitemap:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create file site map for all posts and categories';

    /**
     * Create a new command instance.
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
        // Cron job
        // @monthly php artisan sitemap:create
        $sitemap = App::make('sitemap');

        $sitemap->add(URL::to('/'), Carbon::now(), '1.0', 'daily');
        $sitemap->add(URL::to('/?lang=all'), Carbon::now(), '1.0', 'daily');

        $sitemap->add(URL::to('/posts?wall=all'), Carbon::now(), '0.9', 'daily');
        $sitemap->add(URL::to('/posts?lang=all'), Carbon::now(), '0.9', 'daily');

        $sitemap->add(URL::to('/categories'), Carbon::now(), '0.9', 'daily');

        $categoryFilterIds = CategoryFilter::lists('category_id');

        $categories = empty($categoryFilterIds) ? Category::all() : Category::whereNotIn('id', $categoryFilterIds)
            ->get();

        foreach ($categories as $category) {
            $sitemap->add(URL::action('CategoriesController@show', [$category->short_name]),
                Carbon::now(), '0.9', 'daily');
        }

        $posts = Post::whereNotNull('published_at')->get();

        foreach ($posts as $post) {
            $sitemap->add(URL::action('PostsController@show', [$post->user->username, $post->encrypted_id]),
                Carbon::now(), '0.9', 'daily');
        }

        $sitemap->store('xml', 'sitemap');

        if (File::exists(public_path() . '/sitemap.xml')) {
            chmod(public_path() . '/sitemap.xml', 0777);
        }
    }
}
