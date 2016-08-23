<?php namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckMailQueueCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'viblo:check-mail-queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check whether Mail Queue is running or not.';

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
        $path = base_path();
        $command = 'php ' . $path . '/artisan queue:listen --queue=emails --verbose';
        $result = shell_exec('ps -aux | grep ' . $command);
        $count = substr_count($result, $command);
        if ($count <= 2) {
            $command .= '> /dev/null 2>/dev/null &';
            exec($command);
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
