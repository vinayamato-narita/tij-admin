<?php

namespace App\Http\Controllers;

use App\Components\BreadcrumbComponent;
use App\Enums\CourseTypeEnum;
use App\Enums\StatusCode;
use App\Enums\UserType;
use App\Enums\OrderStatus;
use App\Enums\SubsPaidStatus;
use App\Enums\PaymentWay;
use App\Enums\PaymentStatus;
use App\Models\Course;
use App\Models\CourseInfo;
use App\Models\CourseLesson;
use App\Models\CourseSetCourse;
use App\Models\Lesson;
use App\Models\Student;
use App\Models\PointSubscriptionHistory;
use App\Models\LessonSchedule;
use App\Imports\CourseUsersImport;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;
use Illuminate\Support\Arr;
use Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentsImport;
use Illuminate\Support\Facades\Hash;
use App\Models\SendRemindMailPattern;
use Illuminate\Support\Facades\Mail;

class CourseGroupUserController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function import(Request $request)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'group_course_user_import']
        ]);

        $errorMessage = '';
        $dataImport = [];
        $data = [];
        $showList = false;
        $setSession = true;

        if ($request->isMethod('POST')) {
            $ext = $request->file->getClientOriginalExtension();

            if ($ext !== "xlsx") {
                $errorMessage = '拡張子が異なります。「xlsx」ファイルを指定してください。';
            }

            $data = Excel::toArray(new CourseUsersImport, $request->file);
            $dataCheck = $this->checkHeaderImport($data[0][0]);
            $data = $this->readDataImport($data[0]);

            if (!$dataCheck) {
                $errorMessage = 'ファイルのフォーマットが異なります。正しいフォーマットファイルをダウンロードして、読込用ファイルを作成してください。';
            }

            if (count($data) > 1001) {
                $errorMessage = '一回の操作で登録するユーザ数は、1000ユーザまでにしてください';
            }

            if (empty($errorMessage)) {
                $showList = true;
                $dataImport = $data;

                $courseIds = Arr::pluck($data, 'course_id');
                $courseIds = Arr::where($courseIds, function ($value, $key) {
                    return is_integer($value);
                });
                $courseIds = array_unique($courseIds);

                $emails = Arr::pluck($data, 'email');
                $emails = Arr::where($emails, function ($value, $key) {
                    return !empty($value);
                });
                $emails = array_unique($emails);

                $psh = DB::table('point_subscription_history')
                    ->join('student', 'student.student_id', '=', 'point_subscription_history.student_id')
                    ->whereIn('student_email', $emails)
                    ->whereIn('point_subscription_history.course_id', $courseIds)
                    ->where('payment_status', '=', PaymentStatus::SUCCESS)
                    ->get()->toArray();

                $courseIds = Course::whereIn('course_id', $courseIds)
                            ->where('course_type', CourseTypeEnum::GROUP_COURSE)
                            ->pluck('is_for_lms', 'course_id')->toArray();
                $emails = Student::whereIn('student_email', $emails)
                            ->pluck('is_lms_user', 'student_email')->toArray();
                $boughtCourse = [];
                if (!empty($psh)) {
                    foreach ($psh as $p) {
                        $p = (array) $p;
                        $boughtCourse[$p['course_id']][] = $p['student_email'];
                    }
                }

                $dataImport = [];
                foreach ($data as $key => $line) {
                    $data[$key]['error_list'] = [];

                    if ($key == 0) {
                        continue;
                    }
                    if ($key == 1) {
                        continue;
                    }

                    $tmpCourseId = 0;
                    foreach ($line as $keyLine => $value) {
                        switch ($keyLine) {

                            case 'course_id':
                                if ($this->mb_trim($value) == null) {
                                    $setSession = false;
                                    $data[$key]['error_list'][] = UserType::COURSE_USER_IMPORT_HEADER[$keyLine] . ": 空白チェック";
                                    break;
                                }

                                if (!isset($courseIds[$value])) {
                                    $setSession = false;
                                    $data[$key]["error_list"][] = UserType::COURSE_USER_IMPORT_HEADER[$keyLine] . ": コースIDが存在しません";
                                    break;
                                }

                                if (empty($courseIds[$value])) {
                                    $setSession = false;
                                    $data[$key]["error_list"][] = UserType::COURSE_USER_IMPORT_HEADER[$keyLine] . ": 個人向けコースのコースIDです";
                                    break;
                                }

                                $tmpCourseId = $value;
                                break;

                            case 'email':
                                if ($this->mb_trim($value) == null) {
                                    $setSession = false;
                                    $data[$key]['error_list'][] = UserType::COURSE_USER_IMPORT_HEADER[$keyLine] . ": 空白チェック";
                                    break;
                                }

                                if (!isset($emails[$value])) {
                                    $setSession = false;
                                    $data[$key]["error_list"][] = UserType::COURSE_USER_IMPORT_HEADER[$keyLine] . ": メールアドレスが存在しません";
                                    break;
                                }

                                if (empty($emails[$value])) {
                                    $setSession = false;
                                    $data[$key]["error_list"][] = UserType::COURSE_USER_IMPORT_HEADER[$keyLine] . ": 個人ユーザのメールアドレスです";
                                    break;
                                }

                                if (!empty($dataImport[$tmpCourseId]) && in_array($value, $dataImport[$tmpCourseId])) {
                                    $setSession = false;
                                    $data[$key]["error_list"][] = "メールアドレスとコースIDが重複しています";
                                    break;
                                }

                                if (!empty($boughtCourse[$tmpCourseId]) && in_array($value, $boughtCourse[$tmpCourseId])) {
                                    $setSession = false;
                                    $data[$key]["error_list"][] = "すでにこのコースを購入しています。同じグループコースを複数回購入することはできません。";
                                    break;
                                }

                                if (!empty($tmpCourseId)) {
                                    $dataImport[$tmpCourseId][] = $value;
                                }
                                break;

                            default:
                                break;
                        }
                    }
                }
                unset($data[0],$data[1]);
            }
        }

        if (empty($errorMessage) && $setSession) {
            session()->forget('usersImportData');
            session()->put('usersImportData', $dataImport);
            session()->forget('originData');
            session()->put('originData', $data);
        }

        return view('groupCourse.user_import', [
            'dataImport' => $data,
            'showList' => $showList,
            'errorMessage' => $errorMessage,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    public function saveImport(Request $request)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $listBreadcrumb = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'group_course_user_import'],
            ['name' => 'group_course_save_user_import'],
        ]);

        $data = session()->get('usersImportData');
        session()->forget('usersImportData');
        $originData = session()->get('originData');
        session()->forget('originData');

        if (empty($data)) {
            return redirect(route('courseGroupUser.import'));
        }

        $courseIds = array_keys($data);
        $emails = [];
        foreach ($data as $key => $item) {
            $emails = array_merge($emails, $item);
        }
        $psh = DB::table('point_subscription_history')
            ->join('student', 'student.student_id', '=', 'point_subscription_history.student_id')
            ->whereIn('student_email', $emails)
            ->whereIn('point_subscription_history.course_id', $courseIds)
            ->where('payment_status', '=', PaymentStatus::SUCCESS)
            ->get()->toArray();
        $emails = Student::whereIn('student_email', $emails)
            ->get()
            ->keyBy('student_email')
            ->toArray();

        // check course is bought
        $boughtCourse = [];
        $errorCheck = false;
        if (!empty($psh)) {
            foreach ($psh as $p) {
                $p = (array) $p;
                $boughtCourse[$p['course_id']][] = $p['student_email'];
            }
        }
        foreach ($originData as $key => $line) {
            $originData[$key]['error_list'] = [];

            $tmpCourseId = 0;
            foreach ($line as $keyLine => $value) {
                switch ($keyLine) {

                    case 'course_id':
                        $tmpCourseId = $value;
                        break;

                    case 'email':
                        if (!empty($boughtCourse[$tmpCourseId]) && in_array($value, $boughtCourse[$tmpCourseId])) {
                            $errorCheck = true;
                            $data[$key]["error_list"][] = "すでにこのコースを購入しています。同じグループコースを複数回購入することはできません。";
                            break;
                        }
                        break;

                    default:
                        break;
                }
            }
        }
        if ($errorCheck) {
            return view('groupCourse.user_import', [
                'dataImport' => $data,
                'showList' => true,
                'errorMessage' => '',
            ]);
        }

        try {
            $courseInfo = DB::table('course')
                ->selectRaw(
                    '
                    course.*,
                    course_campaign.price as campaign_price,
                    course_info.course_info_id'
                )
                ->leftJoin('course_info', function ($join) {
                    $join->on('course_info.course_id', '=', 'course.course_id');
                })
                ->leftJoin('course_campaign', function ($join) {
                    $join->on('course_campaign.course_id', '=', 'course.course_id')
                        ->where('course_campaign.campaign_start_time', '<=', Carbon::now())
                        ->where('course_campaign.campaign_end_time', '>=', Carbon::now());
                })
                ->whereIn('course.course_id', $courseIds)
                ->get()
                ->keyBy('course_id')
                ->toArray();

            foreach ($data as $key => $item) {
                $courseId = $key;
                foreach ($item as $k => $i) {
                    $studentId = $emails[$i]['student_id'];

                    // create order
                    $orderId = $courseId . $k . $studentId . 'tij' . time();
                    $orderId = sprintf("%027s", $orderId);
                    $order = array(
                        'order_id' => $orderId,
                        'student_id' => $studentId,
                        'student_card_id' => 0,
                        'course_id' => $courseId,
                        'product_code' => '0000990',
                        'campaign_code' => '',
                        'order_status' => OrderStatus::PAID,
                        'order_ip' => $this->getClientIp(),
                        'order_date' => Carbon::now(),
                        'gmo_error_code' => 0,
                        'error_step' => 0,
                        'corporation_code' => ""
                    );
                    DB::table('order')->insert($order);

                    $paymentDate = date('Y-m-d H:i:s');

                    $data = array(
                        $studentId,
                        $courseId,
                        0,
                        (string) $courseInfo[$courseId]->amount,
                        (string) round(COURSE_TAX * ((empty($courseInfo[$courseId]->amount) || !is_numeric($courseInfo[$courseId]->amount)) ? 0 : $courseInfo[$courseId]->amount)),
                        'JPY',
                        '0',
                        $paymentDate,
                        '1',
                        '',
                        '',
                        $orderId,
                        '',
                        '',
                        '',
                        ''
                    );

                    // sp_insert_point_subscription
                    $ret = DB::select('call sp_insert_point_subscription(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', $data);

                    // update paid status
                    PointSubscriptionHistory::where('order_id', '=', $orderId)
                        ->update([
                            'paid_status' => SubsPaidStatus::SUCCESS,
                            'payment_way' => PaymentWay::IMPORT
                        ]);

                    $psid = DB::table('point_subscription_history')
                        ->where('student_id', '=', $studentId)
                        ->max('point_subscription_history_id');

                    $lessonSchedule = DB::table('lesson_schedule')
                        ->where('course_id', '=', $courseId)
                        ->get()->toArray();
                    foreach ($lessonSchedule as $schedule) {
                        $schedule = (array) $schedule;
                        // insert student_point_history
                        $data = array(
                            'student_id' => $studentId,
                            'pay_date' => $paymentDate,
                            'pay_description' => 'レッスン付与 (' . $courseInfo[$courseId]->course_name . ')',
                            'pay_type' => 0,
                            'point_count' => -1,
                            'expire_date' => Carbon::parse($paymentDate)->addDays($courseInfo[$courseId]->expire_day)->format('Y-m-d 23:59:59'),
                            'lesson_schedule_id' => $schedule['lesson_schedule_id'],
                            'course_id' => $courseId,
                            'start_date' => $paymentDate,
                            'point_subscription_id' => empty($psid) ? 0 : $psid
                        );
                        DB::table('student_point_history')->insert($data);

                        // insert lesson_history
                        $data = array(
                            $schedule["lesson_schedule_id"],
                            $studentId,
                            $schedule["lesson_id"],
                            $schedule["lesson_text_id"],
                            $courseId,
                        );

                        $ret = DB::select('call sp_student_lesson_reserve_register(?,?,?,?,?)', $data);
                        LessonSchedule::where('lesson_schedule_id', '=', $schedule["lesson_schedule_id"])
                            ->update(['lesson_reserve_type' => $schedule["lesson_reserve_type"]]);
                    }
                }
            }

            return view('groupCourse.user_import_success', [
                'listBreadcrumb' => $listBreadcrumb,
                'result' => true,
            ]);
        } catch (Exception $e) {
            return view('groupCourse.user_import_success', [
                'listBreadcrumb' => $listBreadcrumb,
                'result' => false,
            ]);
        }
    }

    private function checkHeaderImport($headerData)
    {
        $res = true;
        $headerDefault = array_values(UserType::COURSE_USER_IMPORT_HEADER);

        $headerData = array_map('strtolower', $headerData);
        $headerDefault = array_map('strtolower', $headerDefault);
        $headerAdd = array_slice($headerData, 0, count($headerDefault));

        if ($headerAdd != $headerDefault) {
            $res = false;
        }

        return $res;
    }

    public function mb_trim($string)
    {
        $rtn = mb_ereg_replace("(^[[:space:]]*)|([[:space:]]*$)", "", $string);
        return $rtn;
    }

    public function checkDateImport($value)
    {
        try {
            if (gettype($value) == 'string') {
                if (!$this->validateDate($value)) {
                    return false;
                }

                return Carbon::create($value);
            }

            if (gettype($value) == 'integer') {
                return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public function readDataImport($data)
    {
        foreach ($data as $key => $value) {
            $value = array_slice($value, 0, count(UserType::COURSE_USER_IMPORT_HEADER));

            if (count(UserType::COURSE_USER_KEY_IMPORT) == count($value)) {
                $data[$key] = array_combine(UserType::COURSE_USER_KEY_IMPORT, $value);
            }

            if (empty(array_filter($value, function ($v, $k) {
                return ($v !== false) && ($v !== null) && ($v !== '');
            }, ARRAY_FILTER_USE_BOTH))) {
                unset($data[$key]);
            }
        }

        return $data;
    }
    public function readDataStudentImport($data)
    {
        foreach ($data as $key => $value) {
            $value = array_slice($value, 0, count(UserType::COURSE_STUDENT_IMPORT_HEADER));

            if (count(UserType::COURSE_STUDENT_KEY_IMPORT) == count($value)) {
                $data[$key] = array_combine(UserType::COURSE_STUDENT_KEY_IMPORT, $value);
            }

            if (empty(array_filter($value, function ($v, $k) {
                return ($v !== false) && ($v !== null) && ($v !== '');
            }, ARRAY_FILTER_USE_BOTH))) {
                unset($data[$key]);
            }
        }

        return $data;
    }

    public function importView(Request $request)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'import_view']
        ]);
        $result = [];
        $msg['error_list'] = "";
        $msg['success'] = "";
        $insert = [];
        if ($request->isMethod('POST')) {
            $ext = $request->file->getClientOriginalExtension();
            $data = Excel::toArray(new StudentsImport, $request->file('file'));
            $emails = Student::pluck('student_email')->toArray();
            $dataCheck = $this->checkHeaderStudentImport($data[0][0]);
            $dataImport = $this->readDataStudentImport($data[0]);
            if ($ext !== "xlsx") {
                $msg['error_list'] = "ファイルのフォーマットが異なります。";
            }
            if (!$dataCheck) {
                $msg['error_list'] = "データに誤りがあります。";
            }
            if (empty($msg['error_list'])) {
                if (!empty($data) && count($data) > 0) {
                    if (count($dataImport) > 1001) {
                        $msg['error_list'] = "一回の操作で登録するユーザ数は、1000ユーザまでにしてください";
                    }
                    unset($dataImport[0],$dataImport[1]);
                    if (count($dataImport) == 0) {
                        $msg['error_list'] = "データを入力してください。";
                    }
                    
                    foreach ($dataImport as $key => $value) {
    
                        $excel_date = $value['student_birthday'];
                        $unix_date = ($excel_date - 25569) * 86400;
                        $excel_date = 25569 + ($unix_date / 86400);
                        $unix_date = ($excel_date - 25569) * 86400;
                    
    
                        if ($value['password'] != null) {
                            $result[$key] = $value['password'];
                        }
    
                        if (in_array($value['student_email'], $emails) == true) {
                            $msg['error_list'] =  $value['student_email'] . 'はすでに存在しています。';
                            break;
                        }
                        if ($value['student_firstname'] == null) {
                            $msg['error_list'] = "姓を入力してください。";
                            break;
                        }
                        if ($value['student_lastname'] == null) {
                            $msg['error_list'] = "名を入力してください。";
                            break;
                        }
                        if ($value['student_nickname'] == null) {
                            $msg['error_list'] = "ニックネームを入力してください。";
                            break;
                        }
                        if ($value['student_email'] == null) {
                            $msg['error_list'] = "メールアドレスを入力してください。";
                            break;
                        }
                        if ($value['student_birthday'] == null) {
                            $msg['error_list'] = "生年月日を入力してください。";
                            break;
                        }
                        if (preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d#$%^&*()+=\-\[\]\';,.\/{}|":<>?~\\\\]{8,16}$/', $value['password']) == false) {
                            $msg['error_list'] = "パスワードは少なくとも、英字1字と数字1字を含む、8字～16字の半角英数字または記号で入力してください。";
                            break;
                        }
                        if ( !isset($value['student_sex']) ) {
                            $msg['error_list'] = "性別を入力してください。";
                            break;
                        }
                        if ($value['company_name'] == null) {
                            $msg['error_list'] = "法人を入力してください。";
                            break;
                        }
                        if ($value['password'] == null) {
                            $msg['error_list'] = "パスワードを入力してください。";
                            break;
                        }
                        if ($value['lang_type'] == null) {
                            $msg['error_list'] = "言語を入力してください。";
                            break;
                        }
                        $insert[] = [
                            'student_name' => $value['student_firstname'] . " ".$value['student_lastname'] ,
                            'student_nickname' => $value['student_nickname'],
                            'student_email' =>  $value['student_email'],
                            'student_birthday' => gmdate("Y/m/d", $unix_date),
                            'student_sex' =>  $value['student_sex'],
                            'company_name' => $value['company_name'],
                            'password' => Hash::make($value['password']),
                            'lang_type'=>$value['lang_type'],
                            'is_lms_user'=>1
                        ];
                    }
                
                    if (isset($insert) && count($insert) > 0 && $msg['error_list']=="") {
                        DB::table('student')->insert($insert);
                        $msg['success'] = "インポートの成功。";
                        foreach ($insert as $key => $value) {
                            foreach ($result as $k => $res) {
                                if($k!=$key) {
                                    break;
                                }
                                $mailPattern = SendRemindMailPattern::getRemindmailPatternInfo($mailtype = 32, $lang = $value['lang_type']);
                                if ($mailPattern) {
                                    $mailSubject = $mailPattern[0]->mail_subject;
                                    $mailBody = $mailPattern[0]->mail_body;
                                    $mailBody = str_replace("#STUDENT_NAME#", $value['student_name'], $mailBody);
                                    $mailBody = str_replace("#STUDENT_PASSWORD#", $res, $mailBody);
    
                                    Mail::raw($mailBody, function ($message) use ($value, $mailSubject) {
                                        $message->to($value['student_email'])
                                            ->subject($mailSubject);
                                    });
                                }
                            }
                        }
                    }
                    else {
                        $insert =[];
                    }
                }
            }
          
        }
        return view('groupCourse.student_import', [
            'dataImport' => $insert,
            'showList' => $result,
            'errorMessage' => $msg,
            'breadcrumbs' => $breadcrumbs,
        ]);
    }
    private function checkHeaderStudentImport($headerData)
    {
        $res = true;
        $headerDefault = array_values(UserType::COURSE_STUDENT_IMPORT_HEADER);

        $headerData = array_map('strtolower', $headerData);
        $headerDefault = array_map('strtolower', $headerDefault);
        $headerAdd = array_slice($headerData, 0, count($headerDefault));

        if ($headerAdd != $headerDefault) {
            $res = false;
        }

        return $res;
    }
}
