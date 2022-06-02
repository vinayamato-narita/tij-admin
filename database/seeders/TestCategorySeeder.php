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
            ['parent_category_name' => '文字・語彙', 'category_name' => '漢字読み', 'display_order' => 1,
                'en' => [
                    'parent_category_name' => 'Vocabulary', 'category_name' => 'Kanji reading '
                ],
                'zh' => [
                    'parent_category_name' => '文字、詞匯', 'category_name' => '漢字讀音'
                ]
            ],
            ['parent_category_name' => '文字・語彙', 'category_name' => '表記', 'display_order' => 2,
                'en' => [
                    'parent_category_name' => 'Vocabulary', 'category_name' => 'Orthography'
                ],
                'zh' => [
                    'parent_category_name' => '文字、詞匯', 'category_name' => '標記'
                ]
            ],
            ['parent_category_name' => '文字・語彙', 'category_name' => '文脈規定', 'display_order' => 3,
                'en' => [
                    'parent_category_name' => 'Vocabulary', 'category_name' => 'Word formation'
                ],
                'zh' => [
                    'parent_category_name' => '文字、詞匯', 'category_name' => '上下文規定'
                ]
            ],
            ['parent_category_name' => '文字・語彙', 'category_name' => '言い換え類義', 'display_order' => 4,
                'en' => [
                    'parent_category_name' => 'Vocabulary', 'category_name' => 'Contextually-defined expressions'
                ],
                'zh' => [
                    'parent_category_name' => '文字、詞匯', 'category_name' => '換言類義'
                ]
            ],
            ['parent_category_name' => '文字・語彙', 'category_name' => '用法', 'display_order' => 5,
                'en' => [
                    'parent_category_name' => 'Vocabulary', 'category_name' => 'Paraphrases'
                ],
                'zh' => [
                    'parent_category_name' => '文字、詞匯', 'category_name' => '用法'
                ]
            ],
            ['parent_category_name' => '文法', 'category_name' => '文の文法１（文法形式の判断）', 'display_order' => 6,
                'en' => [
                    'parent_category_name' => 'Grammar', 'category_name' => 'Sentential grammar 1
(Selecting grammar form)'
                ],
                'zh' => [
                    'parent_category_name' => '語法', 'category_name' => '句子語法1（語法形式的判斷）'
                ]
            ],
            ['parent_category_name' => '文法', 'category_name' => '文の文法２（文の組み立て）', 'display_order' => 7,
                'en' => [
                    'parent_category_name' => 'Grammar', 'category_name' => 'Sentential grammar 2
(Sentence composition)'
                ],
                'zh' => [
                    'parent_category_name' => '語法', 'category_name' => '句子語法2（句子結構）'
                ]
            ],
            ['parent_category_name' => '文法', 'category_name' => '文章の文法', 'display_order' => 8,
                'en' => [
                    'parent_category_name' => 'Grammar', 'category_name' => 'Text grammar'
                ],
                'zh' => [
                    'parent_category_name' => '語法', 'category_name' => '文章的語法'
                ]
            ],
            ['parent_category_name' => '読解', 'category_name' => '内容理解（短文）', 'display_order' => 9,
                'en' => [
                    'parent_category_name' => 'Reading', 'category_name' => 'Comprehension
(Short passages)'
                ],
                'zh' => [
                    'parent_category_name' => '閱讀理解', 'category_name' => '內容理解（短文）'
                ]
            ],
            ['parent_category_name' => '読解', 'category_name' => '内容理解（中文）', 'display_order' => 10,
                'en' => [
                    'parent_category_name' => 'Reading', 'category_name' => 'Comprehension
(Mid-size passages)'
                ],
                'zh' => [
                    'parent_category_name' => '閱讀理解', 'category_name' => '內容理解（中文）'
                ]
            ],
            ['parent_category_name' => '読解', 'category_name' => '内容理解（長文）', 'display_order' => 11,
                'en' => [
                    'parent_category_name' => 'Reading', 'category_name' => 'Comprehension
(Long passages)'
                ],
                'zh' => [
                    'parent_category_name' => '閱讀理解', 'category_name' => '內容理解（長句）'
                ]
            ],
            ['parent_category_name' => '読解', 'category_name' => '統合理解', 'display_order' => 12,
                'en' => [
                    'parent_category_name' => 'Reading', 'category_name' => 'Integrated comprehension'
                ],
                'zh' => [
                    'parent_category_name' => '閱讀理解', 'category_name' => '綜合理解'
                ]
            ],
            ['parent_category_name' => '読解', 'category_name' => '主張理解（長文）', 'display_order' => 13,
                'en' => [
                    'parent_category_name' => 'Reading', 'category_name' => 'Thematic comprehension
(Long passages)'
                ],
                'zh' => [
                    'parent_category_name' => '閱讀理解', 'category_name' => '主張理解（長句）'
                ]
            ],
            ['parent_category_name' => '読解', 'category_name' => '情報検索', 'display_order' => 14,
                'en' => [
                    'parent_category_name' => 'Reading', 'category_name' => 'Information retrieval'
                ],
                'zh' => [
                    'parent_category_name' => '閱讀理解', 'category_name' => '信息搜索'
                ]
            ],
        ];

        DB::beginTransaction();
        try {
            foreach ($arrInsert as $aI) {
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
