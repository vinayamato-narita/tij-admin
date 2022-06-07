<?php

namespace App\Console;

use App\Console\Commands\DailyUpdateTestResultNotSubmitted;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\SendMailBeforeLessonStart::class,
        Commands\GroupCourseDecision::class,
        Commands\AbilityTestReportCommand::class,
        Commands\UpdateTestCommentExpired::class,
        Commands\DailyUpdateTestResultNotSubmitted::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('send_mail_before_lesson_start')
                 ->everyFiveMinutes();

        $schedule->command('group_course_decision:run')
                 ->dailyAt('1:00');

        $schedule->command('ability_test_report:run')
                 ->dailyAt('1:15');

        $schedule->command('command:update_test_comment_expired')
            ->everyFiveMinutes();
        $schedule->command('daily_update_test_result_not_submitted:run')
            ->everyFiveMinutes();
        $schedule->command('send_mail_test_review_remind:run')
            ->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
