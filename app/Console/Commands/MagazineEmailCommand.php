<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NotificationService;
use Request;
use Log;

class MagazineEmailCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'magazine:send_email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the scheduler';

    /**
     * Current timestamp when command is called.
     *
     * @var integer
     */
    protected $timestamp;

    /**
     * Hold messages that get logged
     *
     * @var array
     */
    protected $messages = array();

    /**
     * Specify the time of day that daily tasks get run
     *
     * @var string [HH:MM]
     */
    protected $runAt = '03:00';

    //Weekly config
    protected $weeklyDate = 'Fri';
    protected $weeklyTimeStart = '09:20';
    protected $weeklyTimeEnd = '23';

    //Monthly config
    protected $monthlySendDate = '08';
    protected $monthlySendTime = '16:27';

    //NoPost config
    protected $noPostSendDate = 'Thu';
    protected $noPostSendTime = '16:00';
    protected $dateOfWeekIndex = 4;
    protected $noOfWeek = 3;

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->timestamp = time();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->everyFiveMinutesFrom($this->weeklyDate,
            $this->weeklyTimeStart,
            $this->weeklyTimeEnd, function () {
            NotificationService::sendMailMagazineWeekly();
            $this->messages[] = 'Sent weekly magazine email';
        });
        $this->monthlyOn($this->monthlySendTime, function () {
            NotificationService::sendMailMagazineMonthly();
            $this->messages[] = 'Sent monthly magazine email';
        });
        $this->thursdays(function () {
            if ($this->checkThursdays(date('Y-m-d', $this->timestamp))) {
                NotificationService::sendMailMagazineMonthlyNoPost();
                $this->messages[] = 'Sent weekly magazine email for member has not post';
            }
        });
        $this->finish();
    }

    protected function finish()
    {
        // Write execution time and messages to the log
        $executionTime = round(((microtime(true) - Request::server('REQUEST_TIME_FLOAT')) * 1000), 3);
        $allMessage = '';
        if (count($this->messages) > 0) {
            foreach ($this->messages as $item) {
                $allMessage .= $item . ' | ';
            }
        }
        Log::info('Cron: execution time: ' . $executionTime . ' | ' . $allMessage);
    }

    /**
     * AVAILABLE SCHEDULES
     */
    protected function everyFiveMinutes(callable $callback)
    {
        if ((int)date('i', $this->timestamp) % 5 === 0) call_user_func($callback);
    }

    protected function everyTenMinutes(callable $callback)
    {
        if ((int)date('i', $this->timestamp) % 10 === 0) call_user_func($callback);
    }

    protected function everyFifteenMinutes(callable $callback)
    {
        if ((int)date('i', $this->timestamp) % 15 === 0) call_user_func($callback);
    }

    protected function everyThirtyMinutes(callable $callback)
    {
        if ((int)date('i', $this->timestamp) % 30 === 0) call_user_func($callback);
    }

    /**
     * Called every full hour
     */
    protected function hourly(callable $callback)
    {
        if (date('i', $this->timestamp) === '00') call_user_func($callback);
    }

    /**
     * Called every hour at the minute specified
     *
     * @param  integer $minute
     */
    protected function hourlyAt($minute, callable $callback)
    {
        if ((int)date('i', $this->timestamp) === $minute) call_user_func($callback);
    }

    /**
     * Called every day
     */
    protected function daily(callable $callback)
    {
        if (date('H:i', $this->timestamp) === $this->runAt) call_user_func($callback);
    }

    /**
     * Called every day at the 24h-format time specified
     *
     * @param  string $time [HH:MM]
     */
    protected function dailyAt($time, callable $callback)
    {
        if (date('H:i', $this->timestamp) === $time) call_user_func($callback);
    }

    /**
     * Called every day at 12:00am and 12:00pm
     */
    protected function twiceDaily(callable $callback)
    {
        if (date('h:i', $this->timestamp) === '12:00') call_user_func($callback);
    }

    /**
     * Called every weekday
     */
    protected function weekdays(callable $callback)
    {
        $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'];
        if (in_array(date('D', $this->timestamp), $days) && date('H:i', $this->timestamp) === $this->runAt)
            call_user_func($callback);
    }

    protected function mondays(callable $callback)
    {
        if (date('D', $this->timestamp) === 'Mon' && date('H:i', $this->timestamp) === $this->runAt)
            call_user_func($callback);
    }

    protected function tuesdays(callable $callback)
    {
        if (date('D', $this->timestamp) === 'Tue' && date('H:i', $this->timestamp) === $this->runAt)
            call_user_func($callback);
    }

    protected function wednesdays(callable $callback)
    {
        if (date('D', $this->timestamp) === 'Wed' && date('H:i', $this->timestamp) === $this->runAt)
            call_user_func($callback);
    }

    protected function thursdays(callable $callback)
    {
        if (date('D', $this->timestamp) === $this->noPostSendDate &&
            date('H:i', $this->timestamp) === $this->noPostSendTime
        ) call_user_func($callback);
    }

    protected function fridays(callable $callback)
    {
        if (date('D', $this->timestamp) === 'Fri' && date('H:i', $this->timestamp) === $this->runAt)
            call_user_func($callback);
    }

    protected function saturdays(callable $callback)
    {
        if (date('D', $this->timestamp) === 'Sat' && date('H:i', $this->timestamp) === $this->runAt)
            call_user_func($callback);
    }

    protected function sundays(callable $callback)
    {
        if (date('D', $this->timestamp) === 'Sun' && date('H:i', $this->timestamp) === $this->runAt)
            call_user_func($callback);
    }

    /**
     * Called once every week (basically the same as using sundays() above...)
     */
    protected function weekly(callable $callback)
    {
        if (date('D', $this->timestamp) === 'Sun' && date('H:i', $this->timestamp) === $this->runAt)
            call_user_func($callback);
    }

    /**
     * Called once every week at the specified day and time
     *
     * @param  string $day [Three letter format (Mon, Tue, ...)]
     * @param  string $time [HH:MM]
     */
    protected function weeklyOn($day, $time, callable $callback)
    {
        if (date('D', $this->timestamp) === $day && date('H:i', $this->timestamp) === $time)
            call_user_func($callback);
    }

    /**
     * Called each month on the 1st
     */
    protected function monthly(callable $callback)
    {
        if (date('d', $this->timestamp) === '01' && date('H:i', $this->timestamp) === $this->runAt) call_user_func($callback);
    }

    /**
     * Called each month on the 1st
     */
    protected function monthlyOn($time, callable $callback)
    {
        if (date('d', $this->timestamp) === $this->monthlySendDate && date('H:i', $this->timestamp) === $time) call_user_func($callback);
    }

    /**
     * Called each year on the 1st of January
     */
    protected function yearly(callable $callback)
    {
        if (date('m', $this->timestamp) === '01' && date('d', $this->timestamp) === '01' && date('H:i', $this->timestamp) === $this->runAt) call_user_func($callback);
    }

    /**
     * Called every five minutes from x.. hour to y.. hour
     */
    protected function everyFiveMinutesFrom($day, $start_hour, $end_hour, callable $callback)
    {
        if ((date('D', $this->timestamp) === $day)
            && ((int)date('H', $this->timestamp) >= $start_hour)
            && ((int)date('H', $this->timestamp) <= $end_hour)
            && ((int) date('i', $this->timestamp) % 5 === 0)) {
            call_user_func($callback);
        }
    }

    protected function inThursday($day, callable $callback)
    {
        if ((date('D', $this->timestamp) === $day)
            && ((int)date('H', $this->timestamp) == '10:50')
            && ((int)date('i', $this->timestamp) % 5 === 0)
        ) {
            call_user_func($callback);
        }
    }

    protected function checkThursdays($currentDate)
    {
        $year = date('Y');
        $month = date('m');
        $checkIndex = 0;
        for ($day = 1; $day <= 31; $day++) {
            $time = mktime(0, 0, 0, $month, $day, $year);
            if (date('N', $time) == $this->dateOfWeekIndex) {
                $checkIndex++;
                if (($checkIndex == 1 || $checkIndex == $this->noOfWeek) && ($currentDate == date('Y-m-d', $time))) {
                    return true;
                }
            }
        }
        return false;
    }

}

