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
            'comment_list' => [
                'text' => 'コメント一覧',
                'route_str' => "comment.index",
            ],
            'course_edit' => [
                'text' => 'コース編集',
                'route_str' => "course.edit",
            ],
            'course_set_edit' => [
                'text' => 'セットコース編集',
                'route_str' => "course.setEdit",
            ],
            'lesson_cancel_history_list' => [
                'text' => 'レッスンキャンセル履歴',
                'route_str' => "lessonCancelHistory.index",
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
