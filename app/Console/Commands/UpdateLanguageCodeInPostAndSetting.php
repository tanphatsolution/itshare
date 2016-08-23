<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Data\Blog\Setting;
use App\Data\Blog\Post;
use Config;
use DB;

class UpdateLanguageCodeInPostAndSetting extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lang:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update language code in post and setting post language default.';

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
        $default = 'en';
        $this->info('Getting language code...');
        $languageCode = Config::get('detect_language.code');

        $this->info('Getting posts...');
        DB::beginTransaction();
        $i = 0;
        try {
            $posts = Post::all();
            foreach ($posts as $post) {
                if (!isset($languageCode[$post->language_code]) || is_null($post->language_code)) {
                    $i++;
                    $post->update(['language_code' => $default]);
                }
            }
            DB::commit();
            $this->info('Updated ' . $i . ' posts.');
        } catch (\Exception $e) {
            DB::rollback();
            $this->error('Fail to set post language code...');
        }
        $this->info('Getting user(s) setting...');
        DB::beginTransaction();
        $i = 0;
        try {
            $settings = Setting::all();
            foreach ($settings as $setting) {
                if (!isset($setting->default_post_language)) {
                    $i++;
                    $setting->update(['default_post_language' => $default]);
                }
            }
            DB::commit();
            $this->info('Updated ' . $i . ' user(s) setting.');
        } catch (\Exception $e) {
            DB::rollback();
            $this->error('Fail to set setting language code...');
        }

        $this->info('Done!');
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
