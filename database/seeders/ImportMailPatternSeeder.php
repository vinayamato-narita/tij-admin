<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ImportMailPatternSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('send_remind_mail_pattern')->truncate();
        DB::table('send_remind_mail_pattern_info')->truncate();
        DB::table('send_remind_mail_timing')->truncate();
        $path = public_path('sql/mailPattern.sql');

        $sql = file_get_contents($path);
        DB::unprepared($sql);
    }
}
