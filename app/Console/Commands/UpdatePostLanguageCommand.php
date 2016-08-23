<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Data\Blog\Post;
use App\Libraries\DetectLanguageHelper;

class UpdatePostLanguageCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'post:updateLanguage';

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
        $nullLanguagePosts = Post::whereNotNull('encrypted_id')
            ->whereNull('language_code')
            ->whereNotNull('published_at')
            ->whereNull('deleted_at')
            ->get();
        $total = $nullLanguagePosts->count();
        if ($total > 0) {
            $this->info('Start update posts language. Number of posts: ' . $total);
            $count = 0;
            $detectLanguage = new DetectLanguageHelper();
            foreach ($nullLanguagePosts as $post) {
                try {
                    $checkContent = $post->title . ' ' . substr($post->content, 0, 200);
                    $post->language_code = $detectLanguage->simpleDetect($checkContent);
                    $post->save();
                    $count++;
                } catch (\Exception $ex) {
                    break;
                }
            }
            $this->info('Done! ' . $count / $total . ' posts updated.');
        } else {
            $this->info('No posts to update!');
        }
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
