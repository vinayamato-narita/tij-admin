<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateTestCommentExpired extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update_test_comment_expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "update test comment over 3 hours but not commented";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $maxHoursComment = config('env.MAX_HOURS_COMMENT');
        $beforeMaxHourseComment = Carbon::now()->subHours($maxHoursComment);
        DB::table('test_comment')->whereNull('comment_end_time')
            ->where('comment_start_time', '<', $beforeMaxHourseComment)
            ->update(array(
            'comment_start_time'=> null,
        ));

    }
}
