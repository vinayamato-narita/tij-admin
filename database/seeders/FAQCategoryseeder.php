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


        DB::table('faq_parent_category')->insert(['faq_parent_category_id' => 1,
            'faq_parent_category_name' => 'コースの購入について']);


        DB::table('faq_category')->insert([
            'faq_category_id' => 1,
            'faq_parent_category_id' => 1,
            'oder_number' => 1,
            'faq_category_name' => '3段階目のタイトル']);


        DB::table('faq_parent_category')->insert(['faq_parent_category_id' => 2,
            'faq_parent_category_name' => '講師について']);


        DB::table('faq_category')->insert([
            'faq_category_id' => 2,
            'faq_parent_category_id' => 2,
            'oder_number' => 2,
            'faq_category_name' => '3段階目のタイトル']);





        DB::table('faq_parent_category')->insert(['faq_parent_category_id' => 3,
            'faq_parent_category_name' => '使用について']);


        DB::table('faq_category')->insert([
            'faq_category_id' => 3,
            'faq_parent_category_id' => 3,
            'oder_number' => 3,
            'faq_category_name' => '3段階目のタイトル']);




    }
}
