<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewsSubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('news_subject')->truncate();

        DB::table('news_subject')->insert([
            'news_subject_id' => 1,
            'news_subject_ja' => '全て']);

        DB::table('news_subject')->insert([
            'news_subject_id' => 2,
            'news_subject_ja' => '講師']);

        DB::table('news_subject')->insert([
            'news_subject_id' => 3,
            'news_subject_ja' => '生徒']);
    }
}
