<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('test_category')->truncate();
        $arrInsert = [
            [ 'parent_category_name' => '文字・語彙' , 'category_name' => '漢字読み', 'display_order' => 1],
            [ 'parent_category_name' => '文字・語彙' , 'category_name' => '表記', 'display_order' => 2],
            [ 'parent_category_name' => '文字・語彙' , 'category_name' => '文脈規定', 'display_order' => 3],
            [ 'parent_category_name' => '文字・語彙' , 'category_name' => '言い換え類義', 'display_order' => 4],
            [ 'parent_category_name' => '文字・語彙' , 'category_name' => '用法', 'display_order' => 5],
            [ 'parent_category_name' => '文法' , 'category_name' => '適切語彙の選択', 'display_order' => 6],
            [ 'parent_category_name' => '文法' , 'category_name' => '文の並べ替え', 'display_order' => 7],
            [ 'parent_category_name' => '文法' , 'category_name' => '文中の文法', 'display_order' => 8],
            [ 'parent_category_name' => '読解' , 'category_name' => '内容理解（短文）', 'display_order' => 9],
            [ 'parent_category_name' => '読解' , 'category_name' => '内容理解（中文）', 'display_order' => 10],
            [ 'parent_category_name' => '読解' , 'category_name' => '内容理解（長文）', 'display_order' => 11],
            [ 'parent_category_name' => '読解' , 'category_name' => '統合理解', 'display_order' => 12],
            [ 'parent_category_name' => '読解' , 'category_name' => '主張理解（長文）', 'display_order' => 13],
            [ 'parent_category_name' => '読解' , 'category_name' => '情報検索', 'display_order' => 14],
        ];

        DB::beginTransaction();
        try {
            foreach ($arrInsert as  $aI) {
                DB::table('test_category')->insert([
                    'parent_category_name' => $aI['parent_category_name'],
                    'category_name' => $aI['category_name'],
                    'display_order' => $aI['display_order']
                ]);
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
        }

    }
}
