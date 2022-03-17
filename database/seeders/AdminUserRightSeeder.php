<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminUserRightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_rights')->truncate();
        DB::table('admin_user_rights')->truncate();
        $arrInsert = [
            [
                'admin_rights_name_ja' => 'PAYMENTHISTORY' ,
                'admin_rights_menu' => 'PAYMENTHISTORY',
                'admin_rights_menu_order' => 1
            ],
            [
                'admin_rights_name_ja' => 'STUDENT' ,
                'admin_rights_menu' => 'STUDENT',
                'admin_rights_menu_order' => 2
            ],
            [
                'admin_rights_name_ja' => 'TEACHER' ,
                'admin_rights_menu' => 'TEACHER',
                'admin_rights_menu_order' => 3
            ],
            [
                'admin_rights_name_ja' => 'SCHEDULE' ,
                'admin_rights_menu' => 'SCHEDULE',
                'admin_rights_menu_order' => 4
            ],
            [
                'admin_rights_name_ja' => 'GROUP_LESSON_SCHEDULE' ,
                'admin_rights_menu' => 'GROUP_LESSON_SCHEDULE',
                'admin_rights_menu_order' => 5
            ],
            [
                'admin_rights_name_ja' => 'LESSONSTATUS' ,
                'admin_rights_menu' => 'LESSONSTATUS',
                'admin_rights_menu_order' => 6
            ],
            [
                'admin_rights_name_ja' => 'GROUP_LESSON_HISTORY',
                'admin_rights_menu' => 'GROUP_LESSON_HISTORY',
                'admin_rights_menu_order' => 7
            ],
            [
                'admin_rights_name_ja' => 'GROUP_LESSON_RESERVE' ,
                'admin_rights_menu' => 'GROUP_LESSON_RESERVE',
                'admin_rights_menu_order' => 8
            ],
            [
                'admin_rights_name_ja' => 'LESSONCANCEL' ,
                'admin_rights_menu' => 'LESSONCANCEL',
                'admin_rights_menu_order' => 9
            ],
            [
                'admin_rights_name_ja' => 'ABILITY_TEST_RESULT' ,
                'admin_rights_menu' => 'ABILITY_TEST_RESULT',
                'admin_rights_menu_order' => 10
            ],
            [
                'admin_rights_name_ja' => 'COMMENT' ,
                'admin_rights_menu' => 'COMMENT',
                'admin_rights_menu_order' => 11
            ],
            [
                'admin_rights_name_ja' => 'STUDENT_COMMENT' ,
                'admin_rights_menu' => 'STUDENT_COMMENT',
                'admin_rights_menu_order' => 12
            ],
            [
                'admin_rights_name_ja' => 'LESSONCOURSE' ,
                'admin_rights_menu' => 'LESSONCOURSE',
                'admin_rights_menu_order' => 13
            ],
            [
                'admin_rights_name_ja' => 'LESSON' ,
                'admin_rights_menu' => 'LESSON',
                'admin_rights_menu_order' => 14
            ],
            [
                'admin_rights_name_ja' => 'TEXT' ,
                'admin_rights_menu' => 'TEXT',
                'admin_rights_menu_order' => 15
            ],
            [
                'admin_rights_name_ja' => 'PREPARATION' ,
                'admin_rights_menu' => 'PREPARATION',
                'admin_rights_menu_order' => 16
            ],
            [
                'admin_rights_name_ja' => 'REVIEW' ,
                'admin_rights_menu' => 'REVIEW',
                'admin_rights_menu_order' => 17
            ],
            [
                'admin_rights_name_ja' => 'TEST' ,
                'admin_rights_menu' => 'TEST',
                'admin_rights_menu_order' => 18
            ],
            [
                'admin_rights_name_ja' => 'GUIDE' ,
                'admin_rights_menu' => 'GUIDE',
                'admin_rights_menu_order' => 19
            ],
            [
                'admin_rights_name_ja' => 'REMINDMAIL' ,
                'admin_rights_menu' => 'REMINDMAIL',
                'admin_rights_menu_order' => 20
            ],
            [
                'admin_rights_name_ja' => 'NOTIFICATION' ,
                'admin_rights_menu' => 'NOTIFICATION',
                'admin_rights_menu_order' => 21
            ],
            [
                'admin_rights_name_ja' => 'COURSE_REGISTRATION' ,
                'admin_rights_menu' => 'COURSE_REGISTRATION',
                'admin_rights_menu_order' => 22
            ],
            [
                'admin_rights_name_ja' => 'INQUIRY' ,
                'admin_rights_menu' => 'INQUIRY',
                'admin_rights_menu_order' => 23
            ],
            [
                'admin_rights_name_ja' => 'ACCESSLOG' ,
                'admin_rights_menu' => 'ACCESS_LOG',
                'admin_rights_menu_order' => 24
            ],
            [
                'admin_rights_name_ja' => 'INQUIRYSUBJECT' ,
                'admin_rights_menu' => 'INQUIRYSUBJECT',
                'admin_rights_menu_order' => 25
            ],
            [
                'admin_rights_name_ja' => 'FAQ' ,
                'admin_rights_menu' => 'FAQ',
                'admin_rights_menu_order' => 26
            ],
            [
                'admin_rights_name_ja' => 'NEWS' ,
                'admin_rights_menu' => 'NEWS',
                'admin_rights_menu_order' => 27
            ],
            [
                'admin_rights_name_ja' => 'ADMINUSER' ,
                'admin_rights_menu' => 'ADMINUSER',
                'admin_rights_menu_order' => 28
            ],
            [
                'admin_rights_name_ja' => 'ZOOM_ACCOUNT' ,
                'admin_rights_menu' => 'ZOOM_ACCOUNT',
                'admin_rights_menu_order' => 29
            ],
            [
                'admin_rights_name_ja' => 'ZOOM_SETTING' ,
                'admin_rights_menu' => 'ZOOM_SETTING',
                'admin_rights_menu_order' => 30
            ],
            [
                'admin_rights_name_ja' => 'CSV' ,
                'admin_rights_menu' => 'CSV',
                'admin_rights_menu_order' => 31
            ],
            [
                'admin_rights_name_ja' => 'CSVEXPORT' ,
                'admin_rights_menu' => 'CSVEXPORT',
                'admin_rights_menu_order' => 32
            ],
            [
                'admin_rights_name_ja' => 'LMSCSV' ,
                'admin_rights_menu' => 'LMSCSV',
                'admin_rights_menu_order' => 33
            ],
            [
                'admin_rights_name_ja' => 'GMO' ,
                'admin_rights_menu' => 'GMO',
                'admin_rights_menu_order' => 34
            ],
            [
                'admin_rights_name_ja' => 'DEMANDMAIL' ,
                'admin_rights_menu' => 'DEMANDMAIL',
                'admin_rights_menu_order' => 35
            ],
            [
                'admin_rights_name_ja' => 'ADMINDEMAND' ,
                'admin_rights_menu' => 'ADMINDEMAND',
                'admin_rights_menu_order' => 36
            ],
        ];

        DB::beginTransaction();
        try {
            foreach ($arrInsert as  $aI) {
                DB::table('admin_rights')->insert([
                    'admin_rights_name_ja' => $aI['admin_rights_name_ja'],
                    'admin_rights_menu' => $aI['admin_rights_menu'],
                    'admin_rights_menu_order' => $aI['admin_rights_menu_order']
                ]);
            }

            $adminRights = DB::table('admin_rights')->get()->toArray();
            $arrInsertAdminUserRights = [];
            foreach ($adminRights as $item) {
                $arrInsertAdminUserRights[] = [
                    'admin_user_id' => 1,
                    'admin_rights_id' => $item->admin_rights_id,
                    'is_permitted' => 1,
                    'can_edit' => 1
                ];
            }

            foreach ($arrInsertAdminUserRights as  $aI) {
                DB::table('admin_user_rights')->insert([
                    'admin_user_id' => $aI['admin_user_id'],
                    'admin_rights_id' => $aI['admin_rights_id'],
                    'is_permitted' => $aI['is_permitted'],
                    'can_edit' => $aI['can_edit']
                ]);
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
        }
    }
}
