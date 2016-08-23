<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use App\Services\NotificationService;

class SendMailNotifyAdminCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'mail:send-mail-notify-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send mail notify to admins.';

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
        $sender = $this->option('sender');
        if ($sender == 'system') {
            NotificationService::sentSystemMailNotify();
        } else {
            NotificationService::sentMailNotifyAdmin();
        }
    }

    protected function getOptions()
    {
        return [
            ['sender', null, InputOption::VALUE_OPTIONAL, 'option.', null],
        ];
    }
}
