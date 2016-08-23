<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Config;

class DeleteBackupLastWeekCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'db:delete_backup_lastweek';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete lastweek backup file.';

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
        $lastSaturday = Carbon::now()->subWeek()->startOfWeek()->next(Carbon::SATURDAY)->format('Ymd');
        $backupFilePath = Config::get('backup::path');
        foreach (glob($backupFilePath . $lastSaturday . '*.sql') as $file) {
            unlink((string)$file);
        }
    }
}
