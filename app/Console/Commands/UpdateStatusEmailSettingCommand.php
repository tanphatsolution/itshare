<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Data\System\User;

class UpdateStatusEmailSettingCommand extends Command
{
    const PRIVATE_EMAIL_STATUS = 0;
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'user_setting_display_email:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update status setting email of user to private';

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
        foreach(User::all() as $user) {
            if ($user->setting->display_email) {
                $user->setting->display_email = self::PRIVATE_EMAIL_STATUS;
                $user->setting->save();
            }

            if ($user->setting->display_work_email) {
                $user->setting->display_work_email = self::PRIVATE_EMAIL_STATUS;
                $user->setting->save();
            }
        }
    }
}

