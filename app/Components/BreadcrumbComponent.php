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
