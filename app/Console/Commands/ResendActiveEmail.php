<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Data\System\User;
use App\Services\UserService;

class ResendActiveEmail extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'send:active-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resend Active Email to Non-active Users';

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
        $this->info('Starting...');
        $this->info('Getting non-active users...');
        $users = User::whereNotNull('active_token')->get();
        $total = $users->count();
        $i = 0;
        $this->info('Getting email...');
        foreach ($users as $user) {
            $i++;
            try {
                UserService::sendConfirmEmail($user->email);
            } catch (\Exception $e) {
                $this->info('Error!' . $e->getMessage());
            }
            $percent = round($i / $total, 6) * 100;
            $this->info('Sending... ' . $percent . '%');
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
