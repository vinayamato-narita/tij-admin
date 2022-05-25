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
                $errorMessage = 'ファイルのフォーマットが異なります。正しいファイルフォーマットダウンロードしてファイルを指定してください。';
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
                $courseIds = Course::whereIn('course_id', $courseIds)
                            ->where('is_for_lms', 1)
                            ->where('course_type', CourseTypeEnum::GROUP_COURSE)
                            ->pluck('course_id')->toArray();

                $emails = Arr::pluck($data, 'email');
                $emails = Arr::where($emails, function ($value, $key) {
                    return !empty($value);
                });
                $emails = array_unique($emails);
                $emails = Student::whereIn('student_email', $emails)
                            ->where('is_lms_user', 1)
                            ->pluck('student_email')->toArray();
                $psh = DB::table('point_subscription_history')
                    ->join('student', 'student.student_id', '=', 'point_subscription_history.student_id')
                    ->whereIn('student_email', $emails)
                    ->whereIn('course_id', $courseIds)
                    ->where('payment_status', '=', PaymentStatus::SUCCESS)
                    ->get()->toArray();
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

                    $tmpCourseId = 0;
                    foreach ($line as $keyLine => $value) {
                        switch ($keyLine) {

                            case 'course_id':
                                if ($this->mb_trim($value) == null) {
                                    $setSession = false;
                                    $data[$key]['error_list'][] = UserType::COURSE_USER_IMPORT_HEADER[$keyLine] . ": 空白チェック";
                                    break;
                                }

                                if (!in_array($value, $courseIds)) {
                                    $setSession = false;
                                    $data[$key]["error_list"][] = UserType::COURSE_USER_IMPORT_HEADER[$keyLine] . ": コースIDが無効です";
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

                                if (!in_array($value, $emails)) {
                                    $setSession = false;
                                    $data[$key]["error_list"][] = UserType::COURSE_USER_IMPORT_HEADER[$keyLine] . ": メールアドレスが存在しません";
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
                unset($data[0]);
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
            $emails = array_merge($emails,$item);
        }
        $psh = DB::table('point_subscription_history')
            ->join('student', 'student.student_id', '=', 'point_subscription_history.student_id')
            ->whereIn('student_email', $emails)
            ->whereIn('course_id', $courseIds)
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
                ->selectRaw('
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
                    $orderId = $courseId.$k.$studentId.'tij'.time();
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

                    $paymentDate= date('Y-m-d H:i:s');

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
                    $ret = DB::select('call sp_insert_point_subscription(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)',$data);

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
                    foreach($lessonSchedule as $schedule) {
                        $schedule = (array) $schedule;
                        // insert student_point_history
                        $data = array(
                            'student_id' => $studentId,
                            'pay_date' => $paymentDate,
                            'pay_description' => 'レッスン付与 ('.$courseInfo[$courseId]->course_name.')',
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

                        $ret = DB::select('call sp_student_lesson_reserve_register(?,?,?,?,?)',$data);
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

            if (count(UserType::COURSE_USER_KEY_IMPORT) == count($value)) {
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

public function importView(){
    return view('groupCourse.student_import');
}

public function importStudent(Request $request){
    if ($request->isMethod('POST')) {
        $ext = $request->file->getClientOriginalExtension();

        if ($ext !== "xlsx") {
         return response()->json([
             'status' => false,
            'message' => '拡張子が異なります。「xlsx」ファイルを指定してください。'
         ]);
        }
        $data = Excel::toArray(new StudentsImport, $request->file('file'));
        $dataCheck = $this->checkHeaderStudentImport($data[0][0]);
        $dataImport = $this->readDataStudentImport($data[0]);
        unset($dataImport[0]);
        if(!$dataCheck){
            return response()->json([
            'status' => false,
            'message' => 'ファイルのフォーマットが異なります。正しいファイルフォーマットダウンロードしてファイルを指定してください。'
            ]);
        }
        if(empty($dataImport)==true){
            return response()->json([
                'status' => false,
                'message' => 'データを入力してください',
                ]);
        }
      
       $emails=[];
       foreach ($dataImport as $key => $value){
            if($value[0]==null || $value[1]==null ||$value[2]==null||$value[3]==null||$value[4]==null
            || $value[5]==null || $value[6]==null){
                return response()->json([
                    'status' => false,
                    'message' => 'を入力してください。',
                    ]);
            }
            array_push($emails,$value[2]);
       }
        $emailsCheck = Student::whereIn('student_email', $emails)
                    ->pluck('student_email')->toArray();
        if($emailsCheck){
            return response()->json([
                'status' => false,
                'message' => 'メールアドレス：が既に登録されています。',
                'emails'=>$emailsCheck
                ]);
        }
       
        foreach ($dataImport as $key => $value){
            $student = new Student();
            $student->student_name = $value[0];
            $student->student_nickname = $value[1];
            $student->student_email = $value[2];
            $student->student_birthday = $value[3];
            $student->student_sex = $value[4];
            $student->company_name = $value[5];
            $student->password = Hash::make($value[6]);
            $student->save();
            $mailPattern = SendRemindMailPattern::getRemindmailPatternInfo($mailtype=32, $lang="ja");

            if ($mailPattern) {
                $mailSubject = $mailPattern[0]->mail_subject;
                $mailBody = $mailPattern[0]->mail_body;
                $mailBody = str_replace("#STUDENT_NAME#", $value[2], $mailBody);
                $mailBody = str_replace("#STUDENT_PASSWORD#", $value[6], $mailBody);
                
                Mail::raw($mailBody, function ($message) use ($value, $mailSubject) {
                    $message->to($value[2])
                        ->subject($mailSubject);
                });
            }
        }
     

        return response()->json([
            'status'=>true,
            'message'=>'法人ユーザーを登録しました。',
        ]);         
    }
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
