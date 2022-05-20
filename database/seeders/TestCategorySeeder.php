<?php

namespace Database\Seeders;

use App\Models\TestCategory;
use App\Models\TestCategoryInfo;
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
        DB::table('test_category_info')->truncate();
        $arrInsert = [
            [ 'parent_category_name' => '文字・語彙' , 'category_name' => '漢字読み', 'display_order' => 1,
                'en' => [
                    'parent_category_name' => 'Vocabulary' , 'category_name' => '漢字読み'
                ],
                'zh' => [
                    'parent_category_name' => '文字和詞彙' , 'category_name' => '漢字読み'
                ]
            ],
            [ 'parent_category_name' => '文字・語彙' , 'category_name' => '表記', 'display_order' => 2,
                'en' => [
                    'parent_category_name' => 'Vocabulary' , 'category_name' => '表記'
                ],
                'zh' => [
                    'parent_category_name' => '文字和詞彙' , 'category_name' => '表記'
                ]
             ],
            [ 'parent_category_name' => '文字・語彙' , 'category_name' => '文脈規定', 'display_order' => 3,
                'en' => [
        'parent_category_name' => 'Vocabulary' , 'category_name' => '表記'
    ],
                'zh' => [
        'parent_category_name' => '文字和詞彙' , 'category_name' => '表記'
    ]
            ],
            [ 'parent_category_name' => '文字・語彙' , 'category_name' => '言い換え類義', 'display_order' => 4,
                'en' => [
                    'parent_category_name' => 'Vocabulary' , 'category_name' => '言い換え類義'
                ],
                'zh' => [
                    'parent_category_name' => '文字和詞彙' , 'category_name' => '言い換え類義'
                ]
            ],
            [ 'parent_category_name' => '文字・語彙' , 'category_name' => '用法', 'display_order' => 5,
                'en' => [
                    'parent_category_name' => 'Vocabulary' , 'category_name' => '用法'
                ],
                'zh' => [
                    'parent_category_name' => '文字和詞彙' , 'category_name' => '用法'
                ]
            ],
            [ 'parent_category_name' => '文法' , 'category_name' => '適切語彙の選択', 'display_order' => 6,
                'en' => [
                    'parent_category_name' => 'Grammar' , 'category_name' => '適切語彙の選択'
                ],
                'zh' => [
                    'parent_category_name' => '語法' , 'category_name' => '適切語彙の選択'
                ]
            ],
            [ 'parent_category_name' => '文法' , 'category_name' => '文の並べ替え', 'display_order' => 7,
                'en' => [
                    'parent_category_name' => 'Grammar' , 'category_name' => '文の並べ替え'
                ],
                'zh' => [
                    'parent_category_name' => '語法' , 'category_name' => '文の並べ替え'
                ]
            ],
            [ 'parent_category_name' => '文法' , 'category_name' => '文中の文法', 'display_order' => 8,
                'en' => [
                    'parent_category_name' => 'Grammar' , 'category_name' => '文中の文法'
                ],
                'zh' => [
                    'parent_category_name' => '語法' , 'category_name' => '文中の文法'
                ]
            ],
            [ 'parent_category_name' => '読解' , 'category_name' => '内容理解（短文）', 'display_order' => 9,
                'en' => [
                    'parent_category_name' => 'Reading' , 'category_name' => '内容理解（短文）'
                ],
                'zh' => [
                    'parent_category_name' => '閱讀' , 'category_name' => '内容理解（短文）'
                ]
            ],
            [ 'parent_category_name' => '読解' , 'category_name' => '内容理解（中文）', 'display_order' => 10,
                'en' => [
                    'parent_category_name' => 'Reading' , 'category_name' => '内容理解（中文）'
                ],
                'zh' => [
                    'parent_category_name' => '閱讀' , 'category_name' => '内容理解（中文）'
                ]
            ],
            [ 'parent_category_name' => '読解' , 'category_name' => '内容理解（長文）', 'display_order' => 11,
                'en' => [
                    'parent_category_name' => 'Reading' , 'category_name' => '内容理解（長文）'
                ],
                'zh' => [
                    'parent_category_name' => '閱讀' , 'category_name' => '内容理解（長文）'
                ]
            ],
            [ 'parent_category_name' => '読解' , 'category_name' => '統合理解', 'display_order' => 12,
                'en' => [
                    'parent_category_name' => 'Reading' , 'category_name' => '統合理解'
                ],
                'zh' => [
                    'parent_category_name' => '閱讀' , 'category_name' => '統合理解'
                ]
            ],
            [ 'parent_category_name' => '読解' , 'category_name' => '主張理解（長文）', 'display_order' => 13,
                'en' => [
                    'parent_category_name' => 'Reading' , 'category_name' => '主張理解（長文）'
                ],
                'zh' => [
                    'parent_category_name' => '閱讀' , 'category_name' => '主張理解（長文）'
                ]
            ],
            [ 'parent_category_name' => '読解' , 'category_name' => '情報検索', 'display_order' => 14,
                'en' => [
                    'parent_category_name' => 'Reading' , 'category_name' => '情報検索'
                ],
                'zh' => [
                    'parent_category_name' => '閱讀' , 'category_name' => '情報検索'
                ]
            ],
        ];

        DB::beginTransaction();
        try {
            foreach ($arrInsert as  $aI) {
                $testCategory = new TestCategory();
                $testCategory->parent_category_name = $aI['parent_category_name'];
                $testCategory->category_name = $aI['category_name'];
                $testCategory->display_order = $aI['display_order'];
                $testCategory->save();
                // insert for eng language
                $testCategoryInfo = new TestCategoryInfo();
                $testCategoryInfo->test_category_id = $testCategory->test_category_id;
                $testCategoryInfo->parent_category_name = $aI['en']['parent_category_name'];
                $testCategoryInfo->category_name = $aI['en']['category_name'];
                $testCategoryInfo->lang_type = 'en';
                $testCategoryInfo->save();

                // insert for zh language
                $testCategoryInfo = new TestCategoryInfo();
                $testCategoryInfo->test_category_id = $testCategory->test_category_id;
                $testCategoryInfo->parent_category_name = $aI['zh']['parent_category_name'];
                $testCategoryInfo->category_name = $aI['zh']['category_name'];
                $testCategoryInfo->lang_type = 'zh';
                $testCategoryInfo->save();
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
        }

    }
}
