<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Data\Blog\Post;

class ClearNullPostCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'post:clearNullPost';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all posts without encrypted id.';

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
        $nullPosts = Post::whereNull('encrypted_id')
            ->whereNull('deleted_at');
        $total = $nullPosts->count();
        if ($total > 0) {
            $this->info('Start clearing null posts. Number of posts:' . $total);
            $nullPosts->delete();
            $this->info('Done!' . $total . '.posts cleared');
        } else {
            $this->info('No null posts detected!');
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
