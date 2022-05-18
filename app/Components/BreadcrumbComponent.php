<?php
namespace App\Components;

class BreadcrumbComponent
{

    public function __construct()
    {
    }

    public function listBreadcrumb()
    {
        return [
            'dashboard' => [
                'text' => 'ホーム',
                'route_str' => "dashboard.index",
            ],
            'admin_list' => [
                'text' => '管理ユーザ一覧',
                'route_str' => "admin.index",
            ],
            'teacher_list' => [
                'text' => '講師情報一覧',
                'route_str' => "teacher.index",
            ],
            'create_admin' => [
                'text' => '管理ユーザ新規作成',
                'route_str' => "admin.create",
            ],
            'show_admin' => [
                'text' => 'show admin',
                'route_str' => "admin.show",
            ],
            'edit_admin' => [
                'text' => '管理ユーザ編集',
                'route_str' => "admin.edit",
            ],
            'teacher_add' => [
                'text' => '講師新規作成',
                'route_str' => "teacher.create",
            ],
            'teacher_edit' => [
                'text' => '講師情報編集',
                'route_str' => "teacher.edit",
            ],
            'teacher_show' => [
                'text' => '講師情報表示',
                'route_str' => "teacher.show",
            ],
            'faq_list' => [
                'text' => 'FAQ一覧',
                'route_str' => "faq.index",
            ],
            'create_faq' => [
                'text' => 'FAQ新規作成',
                'route_str' => "faq.create",
            ],
            'show_faq' => [
                'text' => 'FAQ情報表示',
                'route_str' => "faq.show",
            ],
            'edit_faq' => [
                'text' => 'FAQ編集',
                'route_str' => "faq.edit",
            ],
            'text_list' => [
                'text' => 'テキスト一覧',
                'route_str' => "text.index",
            ],
            'text_add' => [
                'text' => 'テキスト新規作成',
                'route_str' => "text.create",
            ],
            'text_show' => [
                'text' => 'テキスト情報表示',
                'route_str' => "text.show",
            ],
            'text_edit' => [
                'text' => 'テキスト編集',
                'route_str' => "text.edit",
            ],
            'news_list' => [
                'text' => 'お知らせ一覧',
                'route_str' => "news.index",
            ],
            'create_news' => [
                'text' => 'お知らせ新規作成',
                'route_str' => "news.create",
            ],
            'edit_news' => [
                'text' => 'お知らせ編集',
                'route_str' => "news.edit",
            ],
            'show_news' => [
                'text' => 'お知らせ情報表示',
                'route_str' => "news.show",
            ],
            'edit_lang_news' => [
                'text' => 'お知らせ多言語編集',
                'route_str' => "editLangNews",
            ],
            'lesson_list' => [
                'text' => 'レッスン一覧',
                'route_str' => "lesson.index",
            ],
            'lesson_add' => [
                'text' => 'レッスン新規作成',
                'route_str' => "lesson.create",
            ],
            'lesson_show' => [
                'text' => 'レッスン情報表示',
                'route_str' => "lesson.show",
            ],
            'edit_lang_lesson' => [
                'text' => 'レッスン多言語編集',
                'route_str' => "lesson.editLang",
            ],
            'edit_lang_faq' => [
                'text' => 'FAQ多言語編集',
                'route_str' => "editLangFaq",
            ],
            'inquiry_list' => [
                'text' => '問い合わせ履歴一覧',
                'route_str' => "inquiry.index",
            ],
            'edit_inquiry' => [
                'text' => '問い合わせ詳細',
                'route_str' => "inquiry.edit",
            ],
            'course_list' => [
                'text' => 'コース一覧',
                'route_str' => "course.index",
            ],
            'course_add' => [
                'text' => 'コース新規作成',
                'route_str' => "course.create",
            ],
            'course_set_add' => [
                'text' => 'セットコース新規作成',
                'route_str' => "course.setCreate",
            ],
            'course_show' => [
                'text' => 'コース情報表示',
                'route_str' => "course.show",
            ],
            'course_set_show' => [
                'text' => 'セットコース情報表示',
                'route_str' => "course.setShow",
            ],
            'csv_index' => [
                'text' => 'CSV出力',
                'route_str' => "csv.index",
            ],
            'inquiry_subject_index' => [
                'text' => '問い合わせ件名管理',
                'route_str' => "inquirySubject.index",
            ],
            'show_inquiry_subject' => [
                'text' => '問い合わせ件名情報表示',
                'route_str' => "inquirySubject.show",
            ],
            'edit_inquiry_subject' => [
                'text' => '問い合わせ件名編集',
                'route_str' => "inquirySubject.edit",
            ],
            'create_inquiry_subject' => [
                'text' => '問い合わせ件名新規作成',
                'route_str' => "inquirySubject.create",
            ],
            'lesson_status_index' => [
                'text' => 'レッスン状況',
                'route_str' => "lessonStatus.create",
            ],
            'comment_list' => [
                'text' => 'コメント一覧',
                'route_str' => "comment.index",
            ],
            'course_edit' => [
                'text' => 'コース編集',
                'route_str' => "course.edit",
            ],
            'edit_lang_course' => [
                'text' => 'コース多言語編集',
                'route_str' => "course.editLang",
            ],
            'course_campaign_create' => [
                'text' => 'キャンペーン追加',
                'route_str' => "course.campaignCreate",
            ],
            'course_set_edit' => [
                'text' => 'セットコース編集',
                'route_str' => "course.setEdit",
            ],
            'student_list' => [
                'text' => '学習者情報一覧',
                'route_str' => "student.index",
            ],
            'student_comment_list' => [
                'text' => 'コメント履歴一覧',
                'route_str' => "student.commentList",
            ],
            'student_create_comment' => [
                'text' => 'コメント新規作成',
                'route_str' => "student.createComment",
            ],
            'student_edit_comment' => [
                'text' => 'コメント編集',
                'route_str' => "student.editComment",
            ],
            'lesson_cancel_history_list' => [
                'text' => 'レッスンキャンセル履歴',
                'route_str' => "lessonCancelHistory.index",
            ],
            'student_lesson_history_list' => [
                'text' => 'レッスン履歴一覧',
                'route_str' => "student.lessonHistoryList",
            ],
            'show_student_lesson_history' => [
                'text' => 'レッスン履歴詳細',
                'route_str' => "student.showLessonHistory",
            ],
            'remind_mail_list' => [
                'text' => 'リマインドメール一覧',
                'route_str' => "remindmail.index",
            ],
            'remind_mail_show' => [
                'text' => 'リマインドメール情報表示',
                'route_str' => "remindmail.show",
            ],
            'remind_mail_edit' => [
                'text' => 'リマインドメール編集',
                'route_str' => "remindmail.edit",
            ],
            'student_payment_history_list' => [
                'text' => '支払い履歴一覧',
                'route_str' => "student.paymentHistoryList",
            ],
            'create_student_payment_history' => [
                'text' => '支払い履歴新規作成',
                'route_str' => "student.createPaymentHistory",
            ],
            'edit_student_payment_history' => [
                'text' => '支払い履歴編集',
                'route_str' => "student.editPaymentHistory",
            ],
            'category_list' => [
                'text' => 'コースカテゴリ一覧',
                'route_str' => "category.index",
            ],
            'category_create' => [
                'text' => 'コースカテゴリ新規作成',
                'route_str' => "category.create",
            ],
            'category_show' => [
                'text' => 'コースカテゴリ情報表示',
                'route_str' => "category.show",
            ],
            'category_edit' => [
                'text' => 'コースカテゴリ編集',
                'route_str' => "category.edit",
            ],
            'edit_lang_category' => [
                'text' => 'コースカテゴリ多言語編集',
                'route_str' => "category.editLang",
            ],
            'edit_student' => [
                'text' => '学習者情報編集',
                'route_str' => "student.edit",
            ],
            'payment_history_list' => [
                'text' => '支払い履歴一覧',
                'route_str' => "paymentHistory.index",
            ],
            'edit_payment_history' => [
                'text' => '支払い履歴編集',
                'route_str' => "paymentHistory.edit",
            ],
            'lesson_schedule_index' => [
                'text' => 'スケジュール管理',
                'route_str' => 'lessonSchedule.index'
            ],
            'group_schedule_index' => [
                'text' => 'グループレッスンスケジュール管理',
                'route_str' => 'groupSchedule.index'
            ],
            'student_point_history_list' => [
                'text' => '受講回数履歴一覧',
                'route_str' => "student.pointHistoryList",
            ],
            'show_student_point_history' => [
                'text' => '受講回数履歴詳細',
                'route_str' => "student.showPointHistory",
            ],
            'edit_role' => [
                'text' => '権限編集',
                'route_str' => "admin.editRole",
            ],
            'test_list' => [
                'text' => 'テスト一覧',
                'route_str' => "test.index",
            ],
            'test_show' => [
                'text' => 'テスト情報表示',
                'route_str' => "test.show",
            ],
            'test_add_question' => [
                'text' => 'テスト問題追加',
                'route_str' => "test.addQuestion",
            ],
            'test_edit_question' => [
                'text' => 'テスト問題編集',
                'route_str' => "test.editQuestion",
            ],
            'create_test' => [
                'text' => 'テスト新規作成',
                'route_str' => "test.create",
            ],
            'edit_test' => [
                'text' => 'テスト編集',
                'route_str' => "test.edit",
            ],
            'preparation_list' => [
                'text' => '予習一覧',
                'route_str' => "preparation.index",
            ],
            'preparation_add' => [
                'text' => '予習新規作成',
                'route_str' => "preparation.create",
            ],
            'preparation_show' => [
                'text' => '予習情報表示',
                'route_str' => "preparation.show",
            ],
            'preparation_edit' => [
                'text' => '予習編集',
                'route_str' => "preparation.edit",
            ],
            'review_list' => [
                'text' => '復習一覧',
                'route_str' => "review.index",
            ],
            'review_add' => [
                'text' => '復習新規作成',
                'route_str' => "review.create",
            ],
            'review_show' => [
                'text' => '復習情報表示',
                'route_str' => "review.show",
            ],
            'review_edit' => [
                'text' => '復習編集',
                'route_str' => "review.edit",
            ],
            'ability_test_result_list' => [
                'text' => '受験済実力テスト一覧',
                'route_str' => 'abilityTestResult.index'
            ],
            'ability_test_result_show' => [
                'text' => '受験済実力テスト情報表示',
                'route_str' => 'abilityTestResult.show'
            ],
            'ability_test_result_edit' => [
                'text' => '実力テスト評価',
                'route_str' => 'abilityTestResult.edit'
            ],
            'ability_test_result_answer_detail' => [
                'text' => '実力テスト回答詳細',
                'route_str' => 'abilityTestResult.answerDetail'
            ],
            'zoom_account_list' => [
                'text' => 'ZOOMアカウント一覧',
                'route_str' => 'zoomAccount.index'
            ],
            'zoom_account_add' => [
                'text' => 'Zoomアカウント新規作成',
                'route_str' => 'zoomAccount.create'
            ],
            'zoom_account_edit' => [
                'text' => 'Zoomアカウント編集',
                'route_str' => 'zoomAccount.edit'
            ],
            'zoom_setting' => [
                'text' => 'ZOOM連携設定',
                'route_str' => 'zoomSetting.edit'
            ],
            'group_lesson_history' => [
                'text' => 'グループレッスン履歴一覧',
                'route_str' => 'group_lesson_history.index'
            ],
            'group_lesson_reserve' => [
                'text' => 'グループレッスンコース申込一覧',
                'route_str' => 'groupLessonReserves.index'
            ],
            'group_lesson_reserve_show' => [
                'text' => 'グループコース申込情報表示',
                'route_str' => 'groupLessonReserves.show'
            ],
            'student_attendance' => [
                'text' => '出欠一覧',
                'route_str' => 'groupLessonHistory.studentAttendance'
            ],
            'edit_lang_remind_mail' => [
                'text' => 'リマインドメール情報',
                'route_str' => "editLangRemindMail",
            ],
            'teacher_lesson_history' => [
                'text' => 'レッスン履歴一覧',
                'route_str' => "teacher.lessonHistory",
            ],
            'teacher_lesson_history_detail' => [
                'text' => 'レッスン履歴詳細',
                'route_str' => "teacher.lessonHistoryDetail",
            ],
            'edit_lang_teacher' => [
                'text' => '講師情報多言語編集',
                'route_str' => "teacher.editLang",
            ],
            'group_lesson_student_list' => [
                'text' => '学習者一覧',
                'route_str' => 'groupLesson.getStudent'
            ],
            'group_course_user_import' => [
                'text' => 'インポート',
                'route_str' => 'courseGroupUser.import'
            ],
            'group_course_save_user_import' => [
                'text' => 'インポート結果',
                'route_str' => 'courseGroupUser.saveImport'
            ],
            'file_list' => [
                'text' => 'メディア一覧',
                'route_str' => 'file.index'
            ],
            'create_file' => [
                'text' => 'メディア新規作成',
                'route_str' => 'file.index'
            ],
            'edit_file' => [
                'text' => 'メディア編集',
                'route_str' => 'file.index'
            ],
        ];
    }

    public function generateBreadcrumb($data = [])
    {
        $breadcrumbResult = [];
        $breadcrumb = $this->listBreadcrumb();
        foreach ($data as $key => $item) {
            if (!isset($breadcrumb[$item['name']])) {
                continue;
            }
            $nameBreadcrumb = $item['name'];
            $itemBreadcumb['text'] = $breadcrumb[$nameBreadcrumb]['text'];
            if (isset($item['session_url']) && $item['session_url'] != '') {
                $itemBreadcumb['url'] = $item['session_url'];
                if ($key == (count($data) - 1)) {
                    $itemBreadcumb['url'] = '#';
                }
                $breadcrumbResult[] = $itemBreadcumb;
                continue;
            }
            unset($item['name']);
            $itemBreadcumb['url'] = route($breadcrumb[$nameBreadcrumb]['route_str'], $item);

            if ($key == (count($data) - 1)) {
                $itemBreadcumb['url'] = '#';
            }

            $breadcrumbResult[] = $itemBreadcumb;
        }
        return $breadcrumbResult;
    }
}
