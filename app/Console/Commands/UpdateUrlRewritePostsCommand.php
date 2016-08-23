<?php namespace App\Console\Commands;

use App\Data\Blog\Post;
use Illuminate\Console\Command;
use App\Libraries\DetectLanguageHelper;
use Illuminate\Support\Str;

class UpdateUrlRewritePostsCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'post:update_url_rewrite';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Url Rewrite For Posts';

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
        $detectLanguage = new DetectLanguageHelper();
        foreach (Post::all() as $post) {
            if (!$post->url_rewrite && ($detectLanguage->simpleDetect($post->title) == 'en'
                    || $detectLanguage->simpleDetect($post->title) == 'vi')
            ) {
                $post->url_rewrite = Str::slug($post->title);
                $post->save();
            }
        }
    }
}
