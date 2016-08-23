<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Data\System\User;
use DB;
use Config;

class RemoveActiveWorkEmail extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'remove:activate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove active work email if it\'s framgia mail and private email also is framgia mail.';

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
        $this->info('Starting command....');
        DB::beginTransaction();
        try {
            User::where('email', 'like', Config::get('mail.extension'))
                ->where('email', DB::raw('`work_email`'))
                ->whereNotNull('active_work_email_token')
                ->update(['active_work_email_token' => null]);
            DB::commit();
            $this->info('Done!');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Error: ' . $e->getMessage());
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }

}
