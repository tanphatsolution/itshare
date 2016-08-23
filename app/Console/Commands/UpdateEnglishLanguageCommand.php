<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Data\Blog\Setting;
use App\Data\Blog\UserPostLanguage;
use App\Data\Blog\Post;

class UpdateEnglishLanguageCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'language:updateEnglishLanguage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

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
        $lang = ['en_PI', 'en_UK', 'en_UD', 'en_US'];

        //For post language
        $post = Post::whereIn('language_code', $lang);
        $countPost = $post->count();
        $post->update(['language_code' => 'en']);
        $this->info('Done! ' . $countPost . ' posts updated.');

        //For setting language
        $languageSettings = Setting::whereIn('default_post_language', $lang);
        $totalSetting = $languageSettings->count();
        $languageSettings->update(['default_post_language' => 'en']);
        $this->info('Done! ' . $totalSetting . 'settings updated.');

        //For user post language
        $languageUserPost = UserPostLanguage::whereIn('language_code', $lang)->get();
        $totalUserPost = $languageUserPost->count();
        if ($totalUserPost > 0) {
            foreach ($languageUserPost as $userPost) {
                try {
                    $enUser = UserPostLanguage::where('user_id', $userPost->user_id)
                        ->where('language_code', 'en');
                    if ($enUser->count() > 0) {
                        if ($enUser->count() > 1) {
                            $enUserCount = $enUser->count();
                            $enUser->take($enUserCount - 1)->skip(0)->delete();
                        }
                        $userPost->delete();
                    } else {
                        $userPost->update(['language_code' => 'en']);
                    }
                } catch (\Exception $ex) {
                    continue;
                }
            }
        }
        $this->info('Done! ' . $totalUserPost . ' languages updated.');
    }
}
