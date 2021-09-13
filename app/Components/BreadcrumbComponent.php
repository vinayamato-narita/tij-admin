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
                'text' => '従業員一覧',
                'route_str' => "admin.index",
            ],
            'teacher_list' => [
                'text' => '講師情報一覧',
                'route_str' => "teacher.index",
            ],
            'teacher_add' => [
                'text' => '講師新規作成',
                'route_str' => "teacher.create",
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
