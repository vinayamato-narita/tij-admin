<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FAQCategoryseeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('faq_category')->truncate();
        DB::table('faq_category_info')->truncate();
        DB::table('faq_parent_category')->truncate();
        DB::table('faq_parent_category_info')->truncate();


        //「わたしば」について

        DB::table('faq_parent_category')->insert(['faq_parent_category_id' => 1,
            'faq_parent_category_name' => '「わたしば」について']);

        DB::table('faq_parent_category_info')->insert([
            'faq_parent_category_info_id' => 1,
            'faq_parent_category_id' => 1,
            'faq_parent_category_name' => 'About "Watashiba"',
            'lang_type' => 'en'
        ]);

        DB::table('faq_parent_category_info')->insert([
            'faq_parent_category_info_id' => 2,
            'faq_parent_category_id' => 1,
            'faq_parent_category_name' => '「わたしば」について',
            'lang_type' => 'zh'
        ]);


        DB::table('faq_category')->insert([
            'faq_category_id' => 1,
            'faq_parent_category_id' => 1,
            'oder_number' => 1,
            'faq_category_name' => 'サービス概要']);

        DB::table('faq_category_info')->insert([
            'faq_category_info_id' => 1,
            'faq_category_id' => 1,
            'faq_category_name' => 'Services',
            'lang_type' => 'en']);

        DB::table('faq_category_info')->insert([
            'faq_category_info_id' => 2,
            'faq_category_id' => 1,
            'faq_category_name' => 'サービス概要',
            'lang_type' => 'zh']);


        //コースとレッスンについて

        DB::table('faq_parent_category')->insert(['faq_parent_category_id' => 2,
            'faq_parent_category_name' => 'コースとレッスンについて']);

        DB::table('faq_parent_category_info')->insert([
            'faq_parent_category_info_id' => 3,
            'faq_parent_category_id' => 2,
            'faq_parent_category_name' => 'About Courses and Lessons',
            'lang_type' => 'en'
        ]);

        DB::table('faq_parent_category_info')->insert([
            'faq_parent_category_info_id' => 4,
            'faq_parent_category_id' => 2,
            'faq_parent_category_name' => 'コースとレッスンについて',
            'lang_type' => 'zh'
        ]);


        DB::table('faq_category')->insert([
            'faq_category_id' => 2,
            'faq_parent_category_id' => 2,
            'oder_number' => 2,
            'faq_category_name' => 'コースについて']);

        DB::table('faq_category_info')->insert([
            'faq_category_info_id' => 3,
            'faq_category_id' => 2,
            'faq_category_name' => 'Courses',
            'lang_type' => 'en']);

        DB::table('faq_category_info')->insert([
            'faq_category_info_id' => 4,
            'faq_category_id' => 2,
            'faq_category_name' => 'コースについて',
            'lang_type' => 'zh']);


        DB::table('faq_category')->insert([
            'faq_category_id' => 3,
            'faq_parent_category_id' => 2,
            'oder_number' => 3,
            'faq_category_name' => '無料体験レッスン']);

        DB::table('faq_category_info')->insert([
            'faq_category_info_id' => 5,
            'faq_category_id' => 3,
            'faq_category_name' => 'Free Trial Lesson',
            'lang_type' => 'en']);

        DB::table('faq_category_info')->insert([
            'faq_category_info_id' => 6,
            'faq_category_id' => 3,
            'faq_category_name' => '無料体験レッスン',
            'lang_type' => 'zh']);


        DB::table('faq_category')->insert([
            'faq_category_id' => 4,
            'faq_parent_category_id' => 2,
            'oder_number' => 4,
            'faq_category_name' => '予約・キャンセル']);

        DB::table('faq_category_info')->insert([
            'faq_category_info_id' => 7,
            'faq_category_id' => 4,
            'faq_category_name' => 'Reservation/Cancellation',
            'lang_type' => 'en']);

        DB::table('faq_category_info')->insert([
            'faq_category_info_id' => 8,
            'faq_category_id' => 4,
            'faq_category_name' => '予約・キャンセル',
            'lang_type' => 'zh']);


        DB::table('faq_category')->insert([
            'faq_category_id' => 5,
            'faq_parent_category_id' => 2,
            'oder_number' => 5,
            'faq_category_name' => 'レッスンの種類']);

        DB::table('faq_category_info')->insert([
            'faq_category_info_id' => 9,
            'faq_category_id' => 5,
            'faq_category_name' => 'Lesson Types',
            'lang_type' => 'en']);

        DB::table('faq_category_info')->insert([
            'faq_category_info_id' => 10,
            'faq_category_id' => 5,
            'faq_category_name' => 'レッスンの種類',
            'lang_type' => 'zh']);



        DB::table('faq_category')->insert([
            'faq_category_id' => 6,
            'faq_parent_category_id' => 2,
            'oder_number' => 6,
            'faq_category_name' => '教材']);

        DB::table('faq_category_info')->insert([
            'faq_category_info_id' => 11,
            'faq_category_id' => 6,
            'faq_category_name' => 'Teaching materials',
            'lang_type' => 'en']);

        DB::table('faq_category_info')->insert([
            'faq_category_info_id' => 12,
            'faq_category_id' => 6,
            'faq_category_name' => '教材',
            'lang_type' => 'zh']);



        DB::table('faq_category')->insert([
            'faq_category_id' => 7,
            'faq_parent_category_id' => 2,
            'oder_number' => 7,
            'faq_category_name' => '評価']);

        DB::table('faq_category_info')->insert([
            'faq_category_info_id' => 13,
            'faq_category_id' => 7,
            'faq_category_name' => 'Evaluation',
            'lang_type' => 'en']);

        DB::table('faq_category_info')->insert([
            'faq_category_info_id' => 14,
            'faq_category_id' => 7,
            'faq_category_name' => '評価',
            'lang_type' => 'zh']);




        //講師について

        DB::table('faq_parent_category')->insert(['faq_parent_category_id' => 3,
            'faq_parent_category_name' => '講師について']);

        DB::table('faq_parent_category_info')->insert([
            'faq_parent_category_info_id' => 5,
            'faq_parent_category_id' => 3,
            'faq_parent_category_name' => 'About Lecturers',
            'lang_type' => 'en'
        ]);

        DB::table('faq_parent_category_info')->insert([
            'faq_parent_category_info_id' => 6,
            'faq_parent_category_id' => 3,
            'faq_parent_category_name' => '講師について',
            'lang_type' => 'zh'
        ]);

        DB::table('faq_category')->insert([
            'faq_category_id' => 8,
            'faq_parent_category_id' => 3,
            'oder_number' => 8,
            'faq_category_name' => '講師について']);

        DB::table('faq_category_info')->insert([
            'faq_category_info_id' => 15,
            'faq_category_id' => 8,
            'faq_category_name' => 'About Lecturers',
            'lang_type' => 'en']);

        DB::table('faq_category_info')->insert([
            'faq_category_info_id' => 16,
            'faq_category_id' => 8,
            'faq_category_name' => '講師について',
            'lang_type' => 'zh']);

        //サービスとお支払い

        DB::table('faq_parent_category')->insert(['faq_parent_category_id' => 4,
            'faq_parent_category_name' => 'サービスとお支払い']);

        DB::table('faq_parent_category_info')->insert([
            'faq_parent_category_info_id' => 7,
            'faq_parent_category_id' => 4,
            'faq_parent_category_name' => 'Services and Payment',
            'lang_type' => 'en'
        ]);

        DB::table('faq_parent_category_info')->insert([
            'faq_parent_category_info_id' => 8,
            'faq_parent_category_id' => 4,
            'faq_parent_category_name' => 'サービスとお支払い',
            'lang_type' => 'zh'
        ]);

        DB::table('faq_category')->insert([
            'faq_category_id' => 9,
            'faq_parent_category_id' => 4,
            'oder_number' => 9,
            'faq_category_name' => 'お支払いについて']);

        DB::table('faq_category_info')->insert([
            'faq_category_info_id' => 17,
            'faq_category_id' => 9,
            'faq_category_name' => 'About Payment',
            'lang_type' => 'en']);

        DB::table('faq_category_info')->insert([
            'faq_category_info_id' => 18,
            'faq_category_id' => 9,
            'faq_category_name' => 'お支払いについて',
            'lang_type' => 'zh']);

        DB::table('faq_category')->insert([
            'faq_category_id' => 10,
            'faq_parent_category_id' => 4,
            'oder_number' => 10,
            'faq_category_name' => 'アカウントについて']);

        DB::table('faq_category_info')->insert([
            'faq_category_info_id' => 19,
            'faq_category_id' => 10,
            'faq_category_name' => 'About Account',
            'lang_type' => 'en']);

        DB::table('faq_category_info')->insert([
            'faq_category_info_id' => 20,
            'faq_category_id' => 10,
            'faq_category_name' => 'アカウントについて',
            'lang_type' => 'zh']);

        //ご利用環境

        DB::table('faq_parent_category')->insert(['faq_parent_category_id' => 5,
            'faq_parent_category_name' => 'ご利用環境']);

        DB::table('faq_parent_category_info')->insert([
            'faq_parent_category_info_id' => 9,
            'faq_parent_category_id' => 5,
            'faq_parent_category_name' => 'System Requirements',
            'lang_type' => 'en'
        ]);

        DB::table('faq_parent_category_info')->insert([
            'faq_parent_category_info_id' => 10,
            'faq_parent_category_id' => 5,
            'faq_parent_category_name' => 'ご利用環境',
            'lang_type' => 'zh'
        ]);

        DB::table('faq_category')->insert([
            'faq_category_id' => 11,
            'faq_parent_category_id' => 5,
            'oder_number' => 11,
            'faq_category_name' => '対応環境・ツール']);

        DB::table('faq_category_info')->insert([
            'faq_category_info_id' => 21,
            'faq_category_id' => 11,
            'faq_category_name' => 'System Requirements and Tools',
            'lang_type' => 'en']);

        DB::table('faq_category_info')->insert([
            'faq_category_info_id' => 22,
            'faq_category_id' => 11,
            'faq_category_name' => '対応環境・ツール',
            'lang_type' => 'zh']);


        //トラブル対応

        DB::table('faq_parent_category')->insert(['faq_parent_category_id' => 6,
            'faq_parent_category_name' => 'トラブル対応']);

        DB::table('faq_parent_category_info')->insert([
            'faq_parent_category_info_id' => 11,
            'faq_parent_category_id' => 6,
            'faq_parent_category_name' => 'Troubleshooting',
            'lang_type' => 'en'
        ]);

        DB::table('faq_parent_category_info')->insert([
            'faq_parent_category_info_id' => 12,
            'faq_parent_category_id' => 6,
            'faq_parent_category_name' => 'トラブル対応',
            'lang_type' => 'zh'
        ]);

        DB::table('faq_category')->insert([
            'faq_category_id' => 12,
            'faq_parent_category_id' => 6,
            'oder_number' => 12,
            'faq_category_name' => 'レッスン中']);

        DB::table('faq_category_info')->insert([
            'faq_category_info_id' => 23,
            'faq_category_id' => 12,
            'faq_category_name' => 'During Lesson',
            'lang_type' => 'en']);

        DB::table('faq_category_info')->insert([
            'faq_category_info_id' => 24,
            'faq_category_id' => 12,
            'faq_category_name' => 'レッスン中',
            'lang_type' => 'zh']);

        DB::table('faq_category')->insert([
            'faq_category_id' => 13,
            'faq_parent_category_id' => 6,
            'oder_number' => 13,
            'faq_category_name' => 'レッスン以外']);

        DB::table('faq_category_info')->insert([
            'faq_category_info_id' => 25,
            'faq_category_id' => 13,
            'faq_category_name' => 'Except Lessons',
            'lang_type' => 'en']);

        DB::table('faq_category_info')->insert([
            'faq_category_info_id' => 26,
            'faq_category_id' => 13,
            'faq_category_name' => 'レッスン以外',
            'lang_type' => 'zh']);






    }
}
