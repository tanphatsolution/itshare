<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Data\Blog\Post;

class UpdateEncryptedIdForPost extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'post:updateEncryptedId';

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
        $nullEncryptedIdPosts = Post::whereNull('encrypted_id')->get();
        $numPosts = $nullEncryptedIdPosts->count();
        if ($numPosts > 0) {
            foreach ($nullEncryptedIdPosts as $post) {
                $post->encryptedId = encrypt_id($post->id);
                $post->save();
            }
            $this->info('Done! ' . $numPosts . ' posts updated');
        } else {
            $this->info('Nothing to update!');
        }
    }

}
