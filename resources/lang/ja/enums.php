<?php
use App\Enums\InquiryFlag;
use App\Enums\StudentEntryType;
use App\Enums\LangTypeOption;
use App\Enums\TestType;
return [
    InquiryFlag::class => [
        InquiryFlag::SUPPORTED => '対応済',
        InquiryFlag::NOTSUPPORTED => '未対応',
    ],
    StudentEntryType::class => [
        StudentEntryType::USING => '利用中',
        StudentEntryType::TEMPORARYREGISTRATION => '仮登録',
        StudentEntryType::INVALID => '無効',
        StudentEntryType::CANCEL => '解約',
        StudentEntryType::WAITINGCANCEL => '解約待ち',
    ],
    LangTypeOption::class => [
        LangTypeOption::JAPANESE => '日本語',
    ],
    TestType::class => [
        TestType::CONFIRMED => '確認テスト',
        TestType::ABILITY => '実力テスト',
        TestType::ENDCOURSE => 'コース終了テスト',
    ],
];
