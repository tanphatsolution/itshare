<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Carbon\Carbon;
use App\Data\Blog\Notification;
use App\Data\Blog\Config;

class ClearUnnecessaryNotificationCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'notification:clear_unnecessary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear unnecessary notification.';

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
        $maxNotifications = Config::get('limitation.max_notifications');
        $maxTimeLive = Config::get('limitation.max_time_live');
        Notification::clearNotification($maxNotifications, $maxTimeLive);
    }
}
