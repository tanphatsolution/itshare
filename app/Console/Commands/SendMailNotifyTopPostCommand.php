<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Services\NotificationService;

class SendMailNotifyTopPostCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'mail:send_mail_notify_top_posts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send mail notify top posts.';

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
        // Cron job
        // @weekly php artisan mail:send_mail_notify_top_posts
        NotificationService::sentMailNotifyTopPosts();
    }
}
