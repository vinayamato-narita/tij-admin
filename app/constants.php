<?php

const TITLE_USER_LIST = "アカウント一覧";
const TITLE_USER_CREATE = "アカウント新規追加";
const TITLE_USER_DETAIL = "アカウント情報確認";
const TITLE_USER_EDIT = "アカウント編集";

const TITLE_DEVICETYPE_LIST = "デバイス種類一覧";
const TITLE_DEVICETYPE_CREATE = "デバイス種類新規追加";
const TITLE_DEVICETYPE_DETAIL = "デバイス種類情報詳細";
const TITLE_DEVICETYPE_EDIT = "デバイス種類編集";
const TITLE_LOGIN = "ログイン";
const TITLE_FORGOT_PASSWORD = "パスワード忘れ";
const TITLE_HOME = "ホーム";
const TITLE_PALETTE_LIST = "GPSデータ一覧";
const TITLE_PALETTE_EDIT = "GPSデータ編集";
const TITLE_FIRMWARE_LIST = "ファームウェア一覧";
const TITLE_FIRMWARE_CREATE = "ファームウェア新規作成";
const TITLE_FIRMWARE_EDIT = "ファームウェア編集";
const TITLE_CONTRACT_PALETTE = "出庫データ連携";
const TITLE_CONTRACT_PALETTE_RETURN = "入庫データ連携";


const TITLE_CONTRACT_LIST = "得意先一覧";
const TITLE_CONTRACT_CREATE = "得意先新規追加";
const TITLE_CONTRACT_DETAIL = "得意先情報詳細";
const TITLE_CONTRACT_EDIT = "得意先編集";

const TITLE_USER_ACCOUNT_LIST = "得意先アカウント一覧";
const TITLE_USER_ACCOUNT_CREATE = "得意先アカウント新規追加";
const TITLE_USER_ACCOUNT_DETAIL = "得意先アカウント情報詳細";
const TITLE_USER_ACCOUNT_EDIT = "得意先アカウント編集";

const MESSAGE_USER_CREATE_SUCCESS = "アカウントの追加が完了しました。";
const MESSAGE_USER_UPDATE_SUCCESS = "アカウントの編集が完了しました。";
const MESSAGE_USER_DELETE_SUCCESS = "このアカウントの削除が完了しました。";
const MESSAGE_CANNOT_FIND_USER = "ユーザーが見つかりませんでした";
const MESSAGE_DEVICETYPE_CREATE_SUCCESS = "デバイス種類新規追加が完了しました。";
const MESSAGE_DEVICETYPE_UPDATE_SUCCESS = "デバイス種類編集が完了しました。";
const MESSAGE_DEVICETYPE_DELETE_SUCCESS = "デバイス種類の削除が完了しました";
const MESSAGE_CANNOT_FIND_DEVICETYPE = "デバイス種類が見つかりませんでした";
const MESSAGE_LOGIN_ERROR = "ユーザー名とパスワードが一致しません。";
const MESSAGE_ERROR = "エラーが発生しました";
const MESSAGE_CONTRACT_CREATE_SUCCESS = "得意先新規追加が完了しました。";
const MESSAGE_CONTRACT_UPDATE_SUCCESS = "得意先編集が完了しました。";
const MESSAGE_CONTRACT_DELETE_SUCCESS = "得意先の削除が完了しました";
const MESSAGE_CANNOT_FIND_CONTRACT = "得意先が見つかりませんでした";
const MESSAGE_USER_ACCOUNT_CREATE_SUCCESS = "アカウント新規追加が完了しました。";
const MESSAGE_USER_ACCOUNT_UPDATE_SUCCESS = "アカウント編集が完了しました。";
const MESSAGE_USER_ACCOUNT_DELETE_SUCCESS = "アカウントの削除が完了しました";
const MESSAGE_CANNOT_FIND_USER_ACCOUNT = "アカウントが見つかりませんでした";
const MESSAGE_USER_ACCOUNT_DELETE_ERROR = "処理中にエラーが発生しました。画面を再読込下さい。";


const MESSAGE_PALETTE_DELETE_ERROR = "エラーが発生しました。再度お願いします。";
const MESSAGE_PALETTE_DELETE_SUCCESS = "このGPSデータの削除が完了しました。";
const MESSAGE_PALETTE_UPDATE_SUCCESS = "GPS端末データ編集が完了しました。";
const MESSAGE_ERROR_UPLOAD_FILE = "ファイルアップロードが失敗しました。再度お願いします。";
const MESSAGE_FIRMWARE_CREATE_SUCCESS = "ファームウェアの登録が完了しました";
const MESSAGE_FIRMWARE_UPDATE_SUCCESS = "ファームウェアの編集が完了しました";
const MESSAGE_FIRMWARE_DELETE_ERROR_CONTACT_EXIST = "得意先に選択されていますので、削除できません。";
const MESSAGE_FIRMWARE_DELETE_SUCCESS = "このファームウェアの削除が完了しました";
const MESSAGE_RESET_PASSWORD_SUCCESS = "選択したユーザーにパスワード初期化メールを送信しました。";
const MESSAGE_DELETE_ERROR = "エラーが発生しました。再度お願いします。";

const MESSAGE_IMPORT_SIZE_EMPTY = 'ファイルを指定してください。';
const MESSAGE_IMPORT_FILE_ERROR = 'ファイルアップロードエラー。';
const MESSAGE_IMPORT_FILE_NAME = '先頭3文字が"OUT"のファイルを指定して下さい。';
const MESSAGE_IMPORT_FILE_NAME_RESULT = '先頭2文字が"IN"のファイルを指定して下さい。';
const MESSAGE_IMPORT_SUCCESS = '登録が完了しました。';


const PAGE_SIZE_LIMIT = [20, 50, 100];
const PAGE_SIZE_DEFAULT = 100;

const COURSE_FREE_ID = 1;

const PAYMENTHISTORY = 1;
const STUDENT = 2;
const TEACHER = 3;
const SCHEDULE = 4;
const GROUP_LESSON_SCHEDULE = 5;
const LESSONSTATUS = 6;
const GROUP_LESSON_HISTORY = 7;
const GROUP_LESSON_RESERVE = 8;
const LESSONCANCEL = 9;
const ABILITY_TEST_RESULT = 10;
const COMMENT = 11;
const STUDENT_COMMENT = 12;
const LESSONCOURSE = 13;
const LESSON = 14;
const TEXT = 15;
const PREPARATION = 16;
const REVIEW = 17;
const TEST = 18;
const GUIDE = 19;
const REMINDMAIL = 20;
const NOTIFICATION = 21;
const COURSE_REGISTRATION = 22;
const INQUIRY = 23;
const ACCESSLOG = 24;
const INQUIRYSUBJECT = 25;
const FAQ = 26;
const NEWS = 27;
const ADMINUSER = 28;
const ZOOM_ACCOUNT = 29;
const ZOOM_SETTING = 30;
const CSV = 31;
const CSVEXPORT = 32;
const LMSCSV = 33;
const GMO = 34;
const DEMANDMAIL = 35;
const ADMINDEMAND = 36;

const CANEDIT = 'can_edit';
const ISPERMITTED = 'is_permitted';
const CATEGORY = 'CATEGORY';
const COURSE = 'COURSE';

const COURSE_TAX = 0.1;
const FILE = 37;
const IMPORT_STUDENT = 38;

const MENU = [
	PAYMENTHISTORY => [
		'paymentHistory.index',
		'paymentHistory.edit',
	],
	STUDENT => [
		'student.index',
		'student.edit',
		'student.commentList',
		'student.createComment',
		'student.editComment',
		'student.lessonHistoryList',
		'student.showLessonHistory',
		'student.paymentHistoryList',
		'student.createPaymentHistory',
		'student.editPaymentHistory',
		'student.pointHistoryList',
		'student.showPointHistory',
	],
	TEACHER => [
		'teacher.index',
		'teacher.create',
		'teacher.show',
		'teacher.edit',
		'teacher.lessonHistory',
		'teacher.lessonHistoryDetail',
		'teacher.editLang',
	],
	COMMENT => [
		'comment.index'
	],
	CATEGORY => [
		'category.index',
		'category.create',
		'category.show',
		'category.edit',
		'category.course',
		'category.editLang',
	],
	COURSE => [
		'course.index',
		'course.create',
		'course.show',
		'course.edit',
		'course.setCreate',
		'course.getCourse',
		'course.lesson',
		'course.setShow',
		'course.setEdit',
		'course.editLang',
		'course.campaignCreate',
	],
	LESSON => [
		'lesson.index',
		'lesson.create',
		'lesson.show',
		'lesson.edit',
		'lesson.textLesson',
		'lesson.editLang',
	],
	TEXT => [
		'text.index',
		'text.create',
		'text.show',
		'text.edit',
	],
	PREPARATION => [
		'preparation.index',
		'preparation.create',
		'preparation.show',
		'preparation.edit',
	],
	REVIEW => [
		'review.index',
		'review.create',
		'review.show',
		'review.edit',
	],
	TEST => [
		'test.index',
		'test.create',
		'test.show',
		'test.edit',
		'test.addQuestion',
	],
	REMINDMAIL => [
		'remindmail.index',
		'remindmail.create',
		'remindmail.show',
		'remindmail.edit',
		'editLangRemindMail',
	],
	INQUIRY => [
		'inquiry.index',
		'inquiry.create',
		'inquiry.show',
		'inquiry.edit',
	],
	INQUIRYSUBJECT => [
		'inquirySubject.index',
		'inquirySubject.create',
		'inquirySubject.show',
		'inquirySubject.edit',
		'editLangInquirySubject',
	],
	FAQ => [
		'faq.index',
		'faq.create',
		'faq.show',
		'faq.edit',
		'editLangFaq',
	],
	NEWS => [
		'news.index',
		'news.create',
		'news.show',
		'news.edit',
		'editLangNews',
	],
	ADMINUSER => [
		'admin.index',
		'admin.create',
		'admin.show',
		'admin.edit',
		'admin.editRole',
    ],
    SCHEDULE => [
        'lessonSchedule.index',
		'lessonSchedule.create',
		'lessonSchedule.show',
		'lessonSchedule.edit',
    ],
    GROUP_LESSON_RESERVE => [
        'groupLessonReserves.index',
		'groupLessonReserves.show',
        'groupLesson.getStudent'
    ],
    GROUP_LESSON_SCHEDULE => [
        'groupSchedule.index'
    ],
    ZOOM_ACCOUNT => [
        'zoomAccount.index',
		'zoomAccount.create',
		'zoomAccount.show',
		'zoomAccount.edit',
    ],
    ZOOM_SETTING => [
		'zoomSetting.edit',
    ],
    GROUP_LESSON_HISTORY => [
    	'groupLessonHistory.index',
    	'groupLessonHistory.studentAttendance'
    ],
    FILE => [
		'file.index',
		'file.create',
		'file.show',
		'file.edit',
	],
	STUDENT_COMMENT => [
		'publicComment.index',
    ],
    COURSE_REGISTRATION => [
		'courseGroupUser.import',
    ],
    IMPORT_STUDENT => [
		'courseGroupUser.importView',
    ],
    ABILITY_TEST_RESULT => [
        'abilityTestResult.answerDetail',
        'abilityTestResult.show'
    ]
];


