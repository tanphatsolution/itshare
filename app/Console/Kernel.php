<?php namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		'App\Console\Commands\Inspire',
		'App\Console\Commands\CheckMailQueueCommand',
		'App\Console\Commands\ClearNullPostCommand',
		'App\Console\Commands\ClearUnnecessaryNotificationCommand',
		'App\Console\Commands\CreateSitemapCommand',
		'App\Console\Commands\DeleteBackupLastWeekCommand',
		'App\Console\Commands\MagazineEmailCommand',
		'App\Console\Commands\RemoveActiveWorkEmail',
		'App\Console\Commands\ResendActiveEmail',
		'App\Console\Commands\SendMailCommand',
		'App\Console\Commands\SendMailNotificationCommand',
		'App\Console\Commands\SendMailNotifyAdminCommand',
		'App\Console\Commands\SendMailNotifyTopPostCommand',
		'App\Console\Commands\StockRankingWeeklyCommand',
		'App\Console\Commands\UpdateEncryptedIdForPost',
		'App\Console\Commands\UpdateEnglishLanguageCommand',
		'App\Console\Commands\UpdateLanguageCodeInPostAndSetting',
		'App\Console\Commands\UpdatePostLanguageCommand',
		'App\Console\Commands\UpdatePostSeries',
		'App\Console\Commands\UpdatePostSeriesLanguage',
		'App\Console\Commands\UpdatePostStatisticsCommand',
		'App\Console\Commands\UpdatePostsNumber',
		'App\Console\Commands\UpdatePostsViewsCount',
		'App\Console\Commands\UpdateStatusEmailSettingCommand',
		'App\Console\Commands\UpdateUrlRewritePostsCommand',
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		$schedule->command('inspire')
				 ->hourly();
	}

}
