<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Components\BreadcrumbComponent;
use App\Enums\StatusCode;
use Log;
use Illuminate\Support\Facades\DB;

class CsvController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'csv_index']
        ]);

        return view('csv.index', [
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function exportPayment(Request $request) {
        set_time_limit(600);
        ini_set('memory_limit','2048M');
        $data = $request->all();

        if (empty($data)) {
            return response()->json([
                'status' => 400,
                'message' => 'エラーが発生しました。もう一度出力してください'
            ]);
        }
        $paymentDate1 = $data['payment_date_1'] ? date("Y-m-d",strtotime($data['payment_date_1'])) : null;
        $paymentDate2 = $data['payment_date_2'] ? date("Y-m-d",strtotime($data['payment_date_2'])) : null;
        $corporationCode = $data['corporation_code'] != null ? $data['corporation_code'] : '';
        $productCode = $data['product_code'] != null ? $data['product_code'] : '';
        $customerCode = $data['customer_code'] != null ? $data['customer_code'] : '';

        $string = DB::select("CALL sp_get_payment_for_export_csv('".$paymentDate1."','".$paymentDate2."','".$corporationCode."','".$productCode."','".$customerCode."')");
        $fileName = "payment_".$paymentDate1."-".$paymentDate2.".csv";

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = [
            $this->convertShijis('受注番号'),
            $this->convertShijis('生徒番号'),
            $this->convertShijis('法人コード(チャネル)'),
            $this->convertShijis('企業名'),
            $this->convertShijis('得意先コード'),
            $this->convertShijis('商品コード'),
            $this->convertShijis('キャンペーンコード'),
            $this->convertShijis('受注日'),
            $this->convertShijis('基準日'),
            $this->convertShijis('有効期限'),
            $this->convertShijis('回数'),
            $this->convertShijis('販売価格'),
            $this->convertShijis('支払方法'),
            $this->convertShijis('コースID')
        ];

        if (!file_exists(public_path().'/csv_file/users')) {
            mkdir(public_path().'/csv_file/users', 0777, true);
        }
        $localPath = public_path().'/csv_file/users/'.$fileName;
        $file = fopen($localPath, 'w');
        fputcsv($file, $columns);

        foreach ($string as $item) {
            $row['受注番号'] = $this->convertShijis($item->point_subscription_history_id);
            $row['生徒番号'] = $this->convertShijis($item->student_id);
            $row['法人コード(チャネル)'] = $this->convertShijis($this->houzinCodeConvert($item->corporation_code));
            $row['企業名'] = $this->convertShijis($this->convert_text($item->company_name));
            $row['得意先コード'] = $this->convertShijis(str_replace("-", "", $item->customer_code));
            $row['商品コード'] = $this->convertShijis($item->product_code);
            $row['キャンペーンコード'] = $this->convertShijis($item->campaign_code);
            $row['受注日'] = $this->convertShijis(date('Ymd', strtotime( $item->received_orderes_date)));
            $row['基準日'] = $this->convertShijis(date('Ymd', strtotime( $item->start_date)));
            $row['有効期限'] = $this->convertShijis(date('Ymd', strtotime( $item->point_expire_date)));
            $row['回数'] = $this->convertShijis($item->point_count);
            $row['販売価格'] = $this->convertShijis($item->amount);
            $row['支払方法'] = $this->convertShijis($item->pay_way);
            $row['コースID'] = $this->convertShijis($item->course_id);
            fputcsv($file, array($row['受注番号'], $row['生徒番号'], $row['法人コード(チャネル)'], $row['企業名'], $row['得意先コード'],$row['商品コード'],$row['キャンペーンコード'],$row['受注日'],$row['基準日'],$row['有効期限'],$row['回数'],$row['販売価格'],$row['支払方法'],$row['コースID']));
        }
        fclose($file);
        return response()->json([
            'path' => url('/csv_file/users/') .'/'. $fileName,
            'file_name' => $fileName,
            'status' => StatusCode::OK
        ]);
    }

    public function exportLessonHistory(Request $request) {
        set_time_limit(600);
        ini_set('memory_limit','2048M');
        $data = $request->all();
        if (!isset($data["lesson_result_date1"]) || !isset($data["lesson_result_date2"])
        || empty($data["lesson_result_date1"]) || empty($data["lesson_result_date2"])) {
            return response()->json([
                'status' => 400,
                'message' => 'エラーが発生しました。もう一度出力してください'
            ]);
        }

        $lessondateForm = date('Y:m:d', strtotime($data["lesson_result_date1"]));
        $lessondateTo = date('Y:m:d', strtotime($data["lesson_result_date2"]));
        $number = $data["lesson_result_number"] != null ? $data["lesson_result_number"] : '';
        $campaign = $data["lesson_result_campaign"] != null ? $data["lesson_result_campaign"] : '';
        $product = $data["lesson_result_product"] != null ? $data["lesson_result_product"] : '';
        $customer = $data["lesson_result_customer"] != null ? $data["lesson_result_customer"] : '';

        $string = DB::select("CALL sp_get_lesson_history_for_export_csv('".$lessondateForm."','".$lessondateTo."','".$number."','".$campaign."','".$product."','".$customer."')");
        $fileName = 'lesson_'.date('Ymd_His' ).'.csv';

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = [
            $this->convertShijis('講師ID'),
            $this->convertShijis('講師名'),
            $this->convertShijis('支払先コード'),
            $this->convertShijis('受注番号'),
            $this->convertShijis('生徒番号'),
            $this->convertShijis('商品コード'),
            $this->convertShijis('基準日'),
            $this->convertShijis('月数'),
            $this->convertShijis('レッスン実施日'),
            $this->convertShijis('レッスン時間'),
            $this->convertShijis('得意先コード'),
            $this->convertShijis('法人コード'),
            $this->convertShijis('企業名'),
            $this->convertShijis('コース名'),
            $this->convertShijis('レッスン名'),
            $this->convertShijis('使用教材(テキスト)'),
            $this->convertShijis('① 教え方'),
            $this->convertShijis('② 態度'),
            $this->convertShijis('③ わかりやすさ'),
            $this->convertShijis('④ Skypeの音質'),
            $this->convertShijis('コメント(生徒⇒講師)'),
            $this->convertShijis('コメント(生徒⇒事務局)'),
            $this->convertShijis('コメント(講師⇒事務局)'),
            $this->convertShijis('受講生氏名'),
            $this->convertShijis('キャンペーンコード'),
            $this->convertShijis('コメント(講師⇒生徒)'),
            $this->convertShijis('出欠')
        ];

        if (!file_exists(public_path().'/csv_file/users')) {
            mkdir(public_path().'/csv_file/users', 0777, true);
        }
        $localPath = public_path().'/csv_file/users/'.$fileName;
        $file = fopen($localPath, 'w');
        fputcsv($file, $columns);

        foreach ($string as $item) {
            $row['講師ID'] = $this->convertShijis($item->teacher_id);
            $row['講師名'] = $this->convertShijis($this->convert_text($item->teacher_name));
            $row['支払先コード'] = $this->convertShijis($item->payment_des_code);
            $row['受注番号'] = $this->convertShijis($item->orders_id);
            $row['生徒番号'] = $this->convertShijis($item->student_id);
            $row['商品コード'] = $this->convertShijis($item->product_code);
            $row['基準日'] = $this->convertShijis(date('Ymd', strtotime( $item->start_date)));
            $row['月数'] = $this->convertShijis($item->months);
            $row['レッスン実施日'] = $this->convertShijis(date('Ymd', strtotime( $item->lesson_date)));
            $row['レッスン時間'] = $this->convertShijis($item->lesson_time);
            $row['得意先コード'] = $this->convertShijis(str_replace("-", "", $item->customer_code));
            $row['法人コード'] = $this->convertShijis($this->houzinCodeConvert($item->corporation_code));
            $row['企業名'] = $this->convertShijis($this->convert_text($item->company_name));
            $row['コース名'] = $this->convertShijis($this->convert_text($item->item_name));
            $row['レッスン名'] = $this->convertShijis($this->convert_text($item->lesson_name));
            $row['使用教材(テキスト)'] = $this->convertShijis($this->convert_text($item->lesson_text_name));
            $row['① 教え方'] = $this->convertShijis($item->teacher_rating);
            $row['② 態度'] = $this->convertShijis($item->teacher_attitude);
            $row['③ わかりやすさ'] = $this->convertShijis($item->teacher_punctual);
            $row['④ Skypeの音質'] = $this->convertShijis($item->skype_voice_rating_from_student);
            $row['コメント(生徒⇒講師)'] = $this->convertShijis($this->convert_text($item->comment_from_student_to_teacher));
            $row['コメント(生徒⇒事務局)'] = $this->convertShijis($this->convert_text($item->comment_from_student_to_office));
            $row['コメント(講師⇒事務局)'] = $this->convertShijis($this->convert_text($item->comment_from_teacher_to_office));
            $row['受講生氏名'] = $this->convertShijis($this->convert_text($item->student_name));
            $row['キャンペーンコード'] = $this->convertShijis($item->campaign_code);
            $row['コメント(講師⇒生徒)'] = $this->convertShijis($this->convert_text($item->comment_from_teacher_to_student));
            $row['出欠'] = $this->convertShijis($item->skype_voice_rating_from_teacher);
            fputcsv($file, array($row['講師ID'], $row['講師名'], $row['支払先コード'], $row['受注番号'], $row['生徒番号'],$row['商品コード'],$row['基準日'],
            $row['月数'],$row['レッスン実施日'],$row['レッスン時間'],$row['得意先コード'],$row['法人コード'],$row['企業名'],$row['コース名'],
            $row['レッスン名'],$row['使用教材(テキスト)'],$row['① 教え方'],$row['② 態度'],$row['③ わかりやすさ'],$row['④ Skypeの音質'],$row['コメント(生徒⇒講師)'],
            $row['コメント(生徒⇒事務局)'],$row['コメント(講師⇒事務局)'],$row['受講生氏名'],$row['キャンペーンコード'],$row['コメント(講師⇒生徒)'],$row['出欠']));
        }
        fclose($file);
        
        return response()->json([
            'path' => url('/csv_file/users/') .'/'. $fileName,
            'file_name' => $fileName,
            'status' => StatusCode::OK
        ]);
    }

    public function exportSuperGrace(Request $request) {
        set_time_limit(600);
        ini_set('memory_limit','2048M');
        $data = $request->all();

        if (empty($data["super_grace_date"])) {
            return response()->json([
                'status' => 400,
                'message' => 'エラーが発生しました。もう一度出力してください'
            ]);
        }
        $data['super_grace_date'] = str_replace('-','/',substr($data['super_grace_date'],0,7));
        $date = date('Y-m-d H:i:s', strtotime($data["super_grace_date"]."/01" . " -3 month"));
        $campaign = $data["super_grace_campaign"];
        $campaignCode = $data["super_grace_campaign_code"];
        $product = $data["super_grace_product"];
        $customer = $data["super_grace_customer"];
        $companyName = (int) $data["super_grace_company_name"];
        $companyNameStr = $data["super_grace_company"];
        $downloadType = (int) $data["super_grace_download_type"];
        $projectCode = $data["project_code"];

        $result1 = DB::select("CALL lms_sp_get_payment_for_export_csv_supergrace('".$date."','".$campaign."','".$product."','".$customer."','".$campaignCode."','".$companyName."','".$companyNameStr."','".$projectCode."','".$downloadType."')");
        $lessons = DB::select("CALL lms_sp_get_lesson_history_for_export_csv_supergrace('".$date."','".$campaign."','".$product."','".$customer."','".$campaignCode."','".$companyName."','".$companyNameStr."','".$projectCode."','".$downloadType."')");
        
        $result2 = array();
        foreach ($lessons as $lesson) {
            $result2[$lesson->point_subscription_history_id][] = $lesson;
        }

        if ($downloadType == 0) {
            $fileName = 'super_grace_'.date('Ymd_His' ).'.txt';
        } else if ($downloadType == 1) {
            $fileName = 'super_grace_'.date('Ymd_His' ).'.csv';
        }


        $columns = [];
        if (empty($result1)) {
            return response()->json([
                'status' => 400,
                'message' => 'データがありません'
            ]);
        } else {
            if ($downloadType == 1) {
                $columns = $this->getHeaderCSV();
            }
            if (!file_exists(public_path().'/csv_file/users')) {
                mkdir(public_path().'/csv_file/users', 0777, true);
            }

            $localPath = public_path().'/csv_file/users/'.$fileName;
            $file = fopen($localPath, 'w');
            fputcsv($file, $columns);

            $tenSpace = $this->my_create_string(' ', 10);
            $checkEmptyFile = 0;
            foreach ($result1 as $item) {
                if( !$item )
                    continue;

                $value = array(
                    0 => substr($item->employee_number.$tenSpace, 0, 10) ,
                    1 => substr($item->department_number.$tenSpace, 0, 10) ,
                    2 => substr($item->course_code.$tenSpace, 0, 7),
                    3 => 0,
                    4 => 0,//reset
                    5 => 0,
                    6 => empty($item->begin_date) ? '00000000' : $item->begin_date,
                    7 => empty($item->point_expire_date) ? '00000000' : $item->point_expire_date,
                    8 => '00000000', // reset
                    9 => substr('00'.$item->point_count, strlen($item->point_count), 2),
                    10 => 0, // reset;
                    11 => '0000'
                );
                if ($value[9] <=0) {
                    // $this->log_file($exepLogFile, '受講番号:'. $row['point_subscription_history_id'] . ' ,受講科目数 = 0');
                    continue;
                }
                $attend = 0;
                $maxDate = '';
                $completeDate = '00000000';
                $count = 0;
                $minMark = 100;
                $totalMark = 0;
                $allMarkInputed = true;
                $lessonList = array();
                $expiredDate = $value[7];
                $totalPoint = (int)$value[9];
                if (!empty($result2[$item->point_subscription_history_id])) {
                    $lessonList = $result2[$item->point_subscription_history_id];
                    foreach ($lessonList  as $lesson) {
                        if ($lesson->marks === '' || $lesson->marks === null) {
                            $allMarkInputed = false;
                            $lesson->marks = 0;
                        }

                        if ( $count <20) {
                            $value[] = $lesson->lesson_date; // 13
                            if ($lesson->is_test_lesson) {
                                $value[] = substr("000".$lesson->marks, strlen($lesson->marks), 3); // 14 点数
                            }else{
                                $value[] = "00". $lesson->study_result; // 14　出欠
                            }
                            $value[] = '0'; //15
                        }

                        if ($lesson->marks < $minMark ) {
                            $minMark = $lesson->marks;
                        }
                        $totalMark += $lesson->marks;

                        $maxDate  = $lesson->lesson_date;
                        if ($lesson->study_result == 1) {
                            $attend ++;
                        }
                        $count++;
                    }
                }

                for($i = $count; $i < 20; $i ++) {
                    $value[] = '00000000'; //13
                    $value[] = '000';//14
                    $value[] = '0';//15
                }

                // caculate complete status
                $completed = 0;
                if ($item->complete_require_type == 1) {//出席
                    $requireTime = ceil($totalPoint * $item->complete_require / 100 );
                    // 終了条件　=0
                    if ($requireTime == 0) {
                        // 全てレッスン受講
                        if (count($lessonList) >= $totalPoint) {
                            $completed = 1;
                            $completeDate = $lessonList[count($lessonList) -1]->lesson_date;
                        }else{
                            //「有効期限日」になった
                            if ($expiredDate < date('Ymd')) {
                                $completed = 1;
                                $completeDate = $expiredDate;
                            }
                        }
                    }

                    // 終了条件　>0
                    if ($requireTime > 0) {
                        //受講回数　＞＝終了条件
                        if ($attend >= $requireTime) {
                            $completed = 1;
                            $completeDate = $lessonList[$requireTime -1]->lesson_date;
                        }else {
                            //「有効期限日」になった
                            if ($expiredDate < date('Ymd')) {
                                $completed = 2;
                                $completeDate = $expiredDate;
                            }
                        }
                    }

                }else if ($item->complete_require_type == 2) {//　点数　平均
                    //修了条件：０
                    if ($item->complete_require_marks == 0) {
                        // 全てレッスン受講
                        if (count($lessonList) >= $totalPoint) {
                            $completed = 1;
                            $completeDate = $lessonList[count($lessonList) -1]->lesson_date;
                        }else{
                            //「有効期限日」になった
                            if ($expiredDate < date('Ymd')) {
                                $completed = 1;
                                $completeDate = $expiredDate;
                            }
                        }
                    }

                    if ($item->complete_require_marks > 0) {
                        //最終レッスンの点数が入力されてない
                        if ($allMarkInputed ==false) {
                            $completed = 0;
                            $completeDate = '00000000';
                        }else {//最終レッスンの点数が入力された

                            //終了条件満たす
                            if (count($lessonList) > 0 && $totalMark / $totalPoint >= $item->complete_require_marks) {
                                // 全てレッスン受講
                                if (count($lessonList) >= $totalPoint) {
                                    $completed = 1;
                                    $completeDate = $maxDate;
                                }else { // 全てレッスン受講していない
                                    //「有効期限日」になった
                                    if ($expiredDate < date('Ymd')) {
                                        $completed = 1;
                                        $completeDate = $expiredDate;
                                    }
                                }
                            }else {
                                //「有効期限日」になった
                                if ($expiredDate < date('Ymd')) {
                                    $completed = 2;
                                    $completeDate = $expiredDate;
                                }
                            }
                        }
                    }

                }else{// 各テスト
                    //修了条件：０
                    if ($item->complete_require_marks == 0) {
                        // 全てレッスン受講
                        if (count($lessonList) >= $totalPoint) {
                            $completed = 1;
                            $completeDate = $lessonList[count($lessonList) -1]->lesson_date;
                        }else{
                            //「有効期限日」になった
                            if ($expiredDate < date('Ymd')) {
                                $completed = 1;
                                $completeDate = $expiredDate;
                            }
                        }
                    }

                    if ($item->complete_require_marks > 0) {
                        //最終レッスンの点数が入力されてない
                        if ($allMarkInputed ==false) {
                            $completed = 0;
                            $completeDate = '00000000';
                        }else {//最終レッスンの点数が入力された

                            //終了条件満たす && 全てレッスン受講
                            if ($minMark >= $item->complete_require_marks
                                && count($lessonList) >= $totalPoint) {
                                $completed = 1;
                                $completeDate = $maxDate;
                            }else {
                                //「有効期限日」になった
                                if ($expiredDate < date('Ymd')) {
                                    $completed = 2;
                                    $completeDate = $expiredDate;
                                }
                            }
                        }
                    }
                }

                $value[10] = substr('00'.$attend, strlen($attend), 2);
                $value[4] = $completed;
                $value[8] = $completeDate;


                // if (
                //     ($item->complete_require_type == 1 && $ritem->complete_require == 0) ||
                //     ($item->complete_require_type != 1 && $item->complete_require_marks == 0)
                // ) {
                //     $value[8] = $value[7];
                // }

                $value[] = "023";
                $value[] = substr("_".$item->project_code.$this->my_create_string(' ', 15) , 0, 15); // 15 ki tu
                $value[] = substr($item->management_number.$tenSpace, 0, 10);
                $value[] = $tenSpace;
                $value[] = substr("000000000000000", 0, 15 - strlen($item->point_subscription_history_id)) . $item->point_subscription_history_id;

                // katakana name
                $firstKana = $this->kana_name_convert($item->student_first_name_kata);
                $lastKana = $this->kana_name_convert($item->student_last_name_kata);
                $nameKana = $firstKana . " " . $lastKana;
                if (empty($firstKana) && empty($lastKana)) {
                    $value[] = '               '; // 15 space
                } else {

                    $value[] = $this->jp_substr($nameKana.'               ', 0, 15);

                }

                $item->student_name = $this->mb_trim($item->student_name);
                $item->student_name = str_replace(' ', '　', $item->student_name); // 半角スペース　→　全角スペース
                if ($this->jp_strlen($item->student_name) > 20) {
                    $temp = $this->jp_substr($item->student_name, 0, 20) ;
                    $temp1 = mb_substr($temp,0, mb_strlen($temp, "shift-jis") -1,"shift-jis");
                    $last = mb_substr($temp,mb_strlen($temp, "shift-jis") -1, 1,"shift-jis");

                    if (strlen($temp1) == 19 && !preg_match("/[0-9a-zA-Z ,_-]/", $last)) {
                        $value[] = $temp1. " ";
                    }else{
                        $value[] = $temp;
                    }
                }else{
                    $value[] = $this->jp_substr($item->student_name.'                    ', 0, 20);
                }

                if ($this->jp_strlen($item->item_name) > 40) {
                    $temp =  $this->jp_substr($item->item_name, 0, 40) ;
                    $temp1 = mb_substr($temp,0, mb_strlen($temp, "shift-jis") -1,"shift-jis");
                    $last = mb_substr($temp,mb_strlen($temp, "shift-jis") -1, 1,"shift-jis");

                    if (strlen($temp1) == 39 && !preg_match("/[0-9a-zA-Z ,_-]/", $last)) {
                        $value[] = $temp1. " ";
                    }else{
                        $value[] = $temp;
                    }
                    //$value[] = $temp . " " .mb_strlen($temp, "shift-jis") . mb_substr($temp,0, mb_strlen($temp, "shift-jis") -1,"shift-jis");
                }else{
                    $value[] = $this->jp_substr($item->item_name .$this->my_create_string(' ', 40), 0, 40);
                    // $value[] = $item->item_name .$this->my_create_string(' ', 40);
                }

                if ($item->payment_date < '20191001') {
                    $price = round(1.08 * $item->price);
                } else {
                    $price = round(1.1 * $item->price);
                }
    
                $value[] = substr('000000'.$price, strlen($price), 6);
                $value[] = '0';

                $value[] = $this->my_create_string(" ", 75); // 75 space;

                fputcsv($file, $value);
            }

            fclose($file);
        
            return response()->json([
                'path' => url('/csv_file/users/') .'/'. $fileName,
                'file_name' => $fileName,
                'status' => StatusCode::OK
            ]);
        }
    }

    public function exportSuperGraceNormal(Request $request) {
        set_time_limit(600);
        ini_set('memory_limit','2048M');
        $data = $request->all();
        if (empty($data["super_grace_date_normal"])) {
            return response()->json([
                'status' => 400,
                'message' => 'エラーが発生しました。もう一度出力してください'
            ]);
        }

        $data['super_grace_date'] = str_replace('-','/',substr($data['super_grace_date_normal'],0,7));
        $date = date('Y-m-d H:i:s', strtotime($data["super_grace_date"]."/01" . " -3 month"));
        $campaign = $data["super_grace_campaign_normal"];
        $campaignCode = $data["super_grace_campaign_code_normal"];
        $product = $data["super_grace_product_normal"];
        $customer = $data["super_grace_customer_normal"];
        $companyName = (int) $data["super_grace_company_name_normal"];
        $companyNameStr = $data["super_grace_company_normal"];
        $downloadType = (int) $data["super_grace_download_type_normal"];
        $studentId = $data["student_id"];

        $result1 = DB::select("CALL sp_get_payment_for_export_csv_supergrace('".$studentId."','".$date."','".$campaign."','".$product."','".$customer."','".$campaignCode."','".$companyName."','".$companyNameStr."')");
        
        $columns = [];
        $fileName = 'super_grace_normal'.date('Ymd_His' ).'.txt';

        if (!$result1) {
            $superGraceErrMsgNormal = "データがありません";
            return response()->json([
                'status' => 400,
                'message' => 'データがありません'
            ]);
        } else {
            $result2 = DB::select("CALL sp_get_lesson_history_for_export_csv_supergrace('".$studentId."','".$date."','".$campaign."','".$product."','".$customer."','".$campaignCode."','".$companyName."','".$companyNameStr."')");
         
            if ($downloadType == 1) {
                $fileName = 'super_grace_normal'.date('Ymd_His' ).'.csv';
            }

            if ($downloadType == 1) {
                $columns = $this->getHeaderCSV();
            }

            if (!file_exists(public_path().'/csv_file/users')) {
                mkdir(public_path().'/csv_file/users', 0777, true);
            }

            $localPath = public_path().'/csv_file/users/'.$fileName;
            $file = fopen($localPath, 'w');
            fputcsv($file, $columns);

            $key = 0;
            $tenSpace = $this->my_create_string(' ', 10);
            $checkEmptyFile = 0;
            foreach ($result1 as $item) {
                if( !$item )
                    continue;

                $value = array(
                    0 => substr($item->employee_number.$tenSpace, 0, 10) ,
                    1 => substr($item->department_number.$tenSpace, 0, 10) ,
                    2 => substr($item->course_id.$tenSpace, 0, 7),
                    3 => 0,
                    4 => 0,//reset
                    5 => 0,
                    6 => empty($item->start_date) ? '00000000' : $item->start_date,
                    7 => empty($item->point_expire_date) ? '00000000' : $item->point_expire_date,
                    8 => '00000000', // reset
                    9 => substr('00'.$item->point_count, strlen($item->point_count), 2),
                    10 => 0, // reset;
                    11 => '0000'
                );
                if ($value[9] <=0) {
                    continue;
                }
                $pointCount = 0;
                $maxDate = '';
                $count = 0;
                while (!empty($result2[$key])){
                    if ($result2[$key]->point_subscription_history_id > $item->point_subscription_history_id) {
                        break;
                    }
                    if ($result2[$key]->point_subscription_history_id != $item->point_subscription_history_id) {
                        $key++;
                        continue;
                    }
                    if ( $count <20) {
                        $value[] = $result2[$key]->lesson_date; // 13
                        $value[] = "00". $result2[$key]->study_result; // 14
                        $value[] = '0'; //15
                    }
                    $maxDate  = $result2[$key]->lesson_date;
                    if ($result2[$key]->study_result == 1) {
                        $pointCount ++;
                    }
                    $key++;
                    $count ++;
                }
                for($i = $count; $i < 20; $i ++) {
                    $value[] = '00000000'; //13
                    $value[] = '000';//14
                    $value[] = '0';//15
                }

                $value[10] = substr('00'.$pointCount, strlen($pointCount), 2);
                if (round($pointCount/(int)$value[9], 2) *100 >= $item->complete_require ) { // lam tron 2 chu so sau dau phay
                    $value[4] = 1;
                    $value[8] = $maxDate;
                } else if ($value[7] < date('Ymd')) {
                    $value[4] = 2;
                    $value[8] = $value[7];
                }

                $value[] = "023";
                $value[] = $this->my_create_string(' ', 15); // 15 ki tu
                $value[] = substr($item->management_number.$tenSpace, 0, 10);
                $value[] = $tenSpace;
                $value[] = substr("000000000000000", 0, 15 - strlen($item->point_subscription_history_id)) . $item->point_subscription_history_id;
                // katakana name
                $firstKana = $this->kana_name_convert($item->student_first_name_kata);
                $lastKana = $this->kana_name_convert($item->student_last_name_kata);
                $nameKana = $firstKana . " " . $lastKana;
                if (empty($firstKana) && empty($lastKana)) {
                    $value[] = '               '; // 15 space
                } else {

                    $value[] = $this->jp_substr($nameKana.'               ', 0, 15);

                }

                $item->student_name = $this->mb_trim_re($item->student_name);
                $item->student_name = str_replace(' ', '　', $item->student_name);// 半角スペース　→　全角スペース
                if ($this->jp_strlen($item->student_name) > 20) {
                    $temp = $this->jp_substr($item->student_name, 0, 20) ;
                    $temp1 = mb_substr($temp,0, mb_strlen($temp, "shift-jis") -1,"shift-jis");
                    $last = mb_substr($temp,mb_strlen($temp, "shift-jis") -1, 1,"shift-jis");

                    if (strlen($temp1) == 19 && !preg_match("/[0-9a-zA-Z ,_-]/", $last)) {
                        $value[] = $temp1. " ";
                    }else{
                        $value[] = $temp;
                    }
                }else{
                    $value[] = $this->jp_substr($item->student_name.$this->my_create_string(' ', 20), 0, 20);
                }

                if ($this->jp_strlen($item->item_name) > 40) {
                    $temp =  $this->jp_substr($item->item_name, 0, 40) ;
                    $temp1 = mb_substr($temp,0, mb_strlen($temp, "shift-jis") -1,"shift-jis");
                    $last = mb_substr($temp,mb_strlen($temp, "shift-jis") -1, 1,"shift-jis");

                    if (strlen($temp1) == 39 && !preg_match("/[0-9a-zA-Z ,_-]/", $last)) {
                        $value[] = $temp1. " ";
                    }else{
                        $value[] = $temp;
                    }
                    //$value[] = $temp . " " .mb_strlen($temp, "shift-jis") . mb_substr($temp,0, mb_strlen($temp, "shift-jis") -1,"shift-jis");
                }else{
                    $value[] = $this->jp_substr($item->item_name .$this->my_create_string(' ', 40), 0, 40);
                }

                if ($item->payment_date < '20191001') {
                    $price = round(1.08 * $item->price);
                } else {
                    $price = round(1.1 * $item->price);
                }           

                $value[] = substr('000000'.$price, strlen($price), 6);
                $value[] = '0';

                $value[] = $this->my_create_string(" ", 75); // 75 space;
                if ($checkEmptyFile == 0) {
                    // $exporter->initialize();
                    $checkEmptyFile ++;
                    if ($downloadType == 1) {
                        // setHeaderCSV($exporter);
                    }
                }

                // $exporter->addRow($value);
                fputcsv($file, $value);
            }

            fclose($file);
        
            return response()->json([
                'path' => url('/csv_file/users/') .'/'. $fileName,
                'file_name' => $fileName,
                'status' => StatusCode::OK
            ]);
        }
    }

    public function exportStudentBoughtCourse(Request $request) {
        set_time_limit(600);
        ini_set('memory_limit','2048M');
        $data = $request->all();
        if (empty($data["project_course_student_start_date"])
            || empty($data["project_course_student_end_date"])
            || empty($data["payment_date_from"])
            || empty($data["payment_date_to"])
        ){
            return response()->json([
                'status' => 400,
                'message' => 'エラーが発生しました。もう一度出力してください'
            ]);
        }

        $studentDateFrom = date('Y-m', strtotime($data["project_course_student_start_date"]));
        $studentDateTo = date('Y-m', strtotime($data["project_course_student_end_date"]));
        $studentName = isset($data["student_name"]) ? $data["student_name"] : "";
        $paymentDateFrom = date('Y-m-d 00:00:00', strtotime($data["payment_date_from"]));
        $paymentDateTo = date('Y-m-d 23:59:59', strtotime($data["payment_date_to"]));
        
        $string = DB::select("CALL lms_sp_get_student_bought_course('".$studentDateFrom."','".$studentDateTo."','".$paymentDateFrom."','".$paymentDateTo."','".$studentName."')");
        $fileName = 'dat_edu_'.date('Ymd' ).'.txt';

        $columns = [
            $this->convertShijis("受注番号"),
            $this->convertShijis("氏名姓"),
            $this->convertShijis("氏名名"),
            $this->convertShijis("氏名"),
            $this->convertShijis("シメイセイ"),
            $this->convertShijis("シメイメイ"),
            $this->convertShijis("シメイ"),
            $this->convertShijis("年齢入力区分"),
            $this->convertShijis("生年月日"),
            $this->convertShijis("職業コード"),
            $this->convertShijis("性別"),
            $this->convertShijis("代表コンタクト先"),
            $this->convertShijis("勤務先電話番号"),
            $this->convertShijis("メールアドレス"),
            $this->convertShijis("携帯番号"),
            $this->convertShijis("現有効住所区分"),
            $this->convertShijis("DM不可"),
            $this->convertShijis("TOEICスコア"),
            $this->convertShijis("英語レベル"),
            $this->convertShijis("郵便番号"),
            $this->convertShijis("都道府県・地域コード"),
            $this->convertShijis("住所1"),
            $this->convertShijis("住所2"),
            $this->convertShijis("住所3"),
            $this->convertShijis("住所4"),
            $this->convertShijis("受注開始フラグ"),
            $this->convertShijis("教育社管理NO"),
            $this->convertShijis("企業ID"),
            $this->convertShijis("所属部署No"),
            $this->convertShijis("社員番号"),
            $this->convertShijis("共通管理番号"),
            $this->convertShijis("他部署管理ナンバー"),
            $this->convertShijis("品目NO"),
            $this->convertShijis("媒体コード"),
            $this->convertShijis("販売単価"),
            $this->convertShijis("教育社企業負担額"),
            $this->convertShijis("支払方法"),
            $this->convertShijis("顧客グループナンバー"),
        ];

        if (!file_exists(public_path().'/csv_file/users')) {
            mkdir(public_path().'/csv_file/users', 0777, true);
        }
        $localPath = public_path().'/csv_file/users/'.$fileName;
        $file = fopen($localPath, 'w');
        fputcsv($file, $columns);


        foreach ($string as $item) {
            if ($item->payment_date <= '2019-09-30 23:59:59') {
                $price = round((int)$item->price * 1.08);
            } else {
                $price = round((int)$item->price * 1.1);
            }
            
            $row["受注番号"] =  isset($item->point_subscription_id) ? $this->convertShijis($item->point_subscription_id) : null; // 1 シリアル番号
            $row["氏名姓"] =  isset($item->student_first_name) ? mb_substr($this->convertShijis($item->student_first_name), 0, 30, "utf-8"): null; // No 2 .氏名姓
            $row["氏名名"] =  isset($item->student_last_name) ? mb_substr($this->convertShijis($item->student_last_name), 0, 30, "utf-8"): null; // No 3 氏名名
            $row["氏名"] =  isset($item->student_name) ? mb_substr($this->convertShijis($item->student_name), 0, 40, "utf-8"): null; //No 4 氏名
            $row["シメイセイ"] =  isset($item->student_first_name_kata) ? mb_substr($this->convertShijis($item->student_first_name_kata), 0, 30, "utf-8"): null; // No 5 シメイセイ
            $row["シメイメイ"] =  isset($item->student_last_name_kata) ? mb_substr($this->convertShijis($item->student_last_name_kata), 0, 30, "utf-8"): null; //No 6 シメイメイ
            $row["シメイ"] =  isset($item->student_last_name_kata) || isset($item->student_first_name_kata) ?  mb_substr($this->convertShijis($item->student_first_name_kata). ' ' .$this->convertShijis($item->student_last_name_kata), 0, 40, "utf-8") : null; //No 7 シメイセイ　＋　(半角スペース)　＋　シメイメイ
            $row["年齢入力区分"] =  '9'; //No 8 年齢入力区分
            $row["生年月日"] = ''; //date('Ymd', strtotime( $item->student_birthday)), //生年月日
            $row["職業コード"] = '11'; //職業コード
            $row["性別"] = ''; //isset($student_sex) ? $student_sex : null, //性別
            $row["代表コンタクト先"] = !empty($item->student_home_tel)? (preg_replace("/[^0-9]/","",$this->convertShijis($item->student_home_tel))) :''; //代表コンタクト先
            $row["勤務先電話番号"] = ''; //勤務先電話番号
            $row["メールアドレス"] = isset($item->student_email) ? $this->convertShijis($item->student_email) : null; //メールアドレス
            $row["携帯番号"] = ''; //No 15 携帯電話番号
            $row["現有効住所区分"] = null; //No 16 現有効住所区分
            $row["DM不可"] = 2; //!empty($item->is_sending_dm) ? 0 : 1, //17 DM不可：逆に：１：送信しない、０：送信する
            $row["TOEICスコア"] = null; //TOEICスコア
            $row["英語レベル"] = null; //・No19　英語レベル
            $row["郵便番号"] = isset($item->postcode) ? (preg_replace("/[^0-9]/","",$this->convertShijis($item->postcode))) : null; //No20　郵便番号
            $row["都道府県・地域コード"] = isset($item->prefecture_number) ? $this->convertShijis($item->prefecture_number) : null; //・No21　都道府県・地域コード
            $row["住所1"] = isset($item->student_address) ? mb_substr($this->convertShijis($item->student_address), 0, 50, "utf-8") : null; //・No22　住所１
            $row["住所2"] = isset($item->student_address1) ? mb_substr($this->convertShijis($item->student_address1), 0, 50, "utf-8") : null; //・No22　住所2
            $row["住所3"] = isset($item->student_address2) ? mb_substr($this->convertShijis($item->student_address2), 0, 50, "utf-8") : null; //・No22　住所3
            $row["住所4"] = isset($item->student_address3) ? mb_substr($this->convertShijis($item->student_address3), 0, 50, "utf-8") : null; //・No22　住所3
            $row["受注開始フラグ"] = 9; //・No26　受注開始フラグ
            $row["教育社管理NO"] = 1;
            $row["企業ID"] = isset($item->project_code) ? $this->convertShijis($item->project_code) : null; //・No28　企業ID
            $row["所属部署No"] = isset($item->department_number) ? mb_substr($this->convertShijis($item->department_number), 0, 30, "utf-8") : null;//・No29　所属部署名
            $row["社員番号"] = isset($item->employee_number) ? mb_substr($this->convertShijis($item->employee_number), 0, 20, "utf-8") :null;//・No30　社員番号
            $row["共通管理番号"] = isset($item->common_mgt_no) ? mb_substr($this->convertShijis($item->common_mgt_no), 0, 10, "utf-8") : null; //・No31　共通管理番号
            $row["他部署管理ナンバー"] = !empty($item->other_department_management_number) ? substr($this->convertShijis($item->other_department_management_number), 4) : null;//・No32　他部署管理ナンバー
            $row["品目NO"] = isset($item->paypal_item_number) ? mb_substr($this->convertShijis($item->paypal_item_number), 0, strlen($item->paypal_item_number) -3 , "utf-8") : null;//・No33　品目No
            $row["媒体コード"] = isset($item->bill_address) ? $this->convertShijis($item->bill_address) : null;//・No34　媒体コード
            $row["販売単価"] = $price;//・No35　販売価格
            $row["教育社企業負担額"] = null;//・No36　教育社企業負担額
            $row["支払方法"] = null;//・No37　支払い方法
            $row["顧客グループナンバー"] = isset($item->point_subscription_id) ? $this->convertShijis($item->point_subscription_id) : null;//・No38　顧客グループナンバー

            fputcsv($file, array($row["受注番号"],$row["氏名姓"],$row["氏名名"],$row["氏名"],$row["シメイセイ"],$row["シメイメイ"],$row["シメイ"],$row["年齢入力区分"],$row["生年月日"],$row["職業コード"],$row["性別"],$row["代表コンタクト先"],$row["勤務先電話番号"],$row["メールアドレス"],$row["携帯番号"],$row["現有効住所区分"],$row["DM不可"],$row["TOEICスコア"],$row["英語レベル"],$row["郵便番号"],$row["都道府県・地域コード"],$row["住所1"],$row["住所2"],$row["住所3"],$row["住所4"],$row["受注開始フラグ"],$row["教育社管理NO"],$row["企業ID"],$row["所属部署No"],$row["社員番号"],$row["共通管理番号"],$row["他部署管理ナンバー"],$row["品目NO"],$row["媒体コード"],$row["販売単価"],$row["教育社企業負担額"],$row["支払方法"],$row["顧客グループナンバー"]));
        }

        fclose($file);
        
        return response()->json([
            'path' => url('/csv_file/users/') .'/'. $fileName,
            'file_name' => $fileName,
            'status' => StatusCode::OK
        ]);
    }

    public function exportLessonSummaryProcess(Request $request) {
        set_time_limit(600);
        ini_set('memory_limit','2048M');
        $data = $request->all();
        if (!isset($data["sm_lesson_date_from"])
        || !isset($data["sm_lesson_date_to"]) || !isset($data["sm_expire_date_from"])
        || !isset($data["sm_expire_date_to"])
        ) {
            echo "エラーが発生しました。もう一度出力してください";
        }

        $lessondateForm = empty($data["sm_lesson_date_from"]) ? '' : date('Y-m-d', strtotime($data["sm_lesson_date_from"]));
        $lessondateTo = empty($data["sm_lesson_date_to"]) ? '' : date('Y-m-d', strtotime($data["sm_lesson_date_to"]));
        $expiredateForm = empty($data["sm_expire_date_from"]) ? '' : date('Y-m-d', strtotime($data["sm_expire_date_from"]));
        $expiredateTo = empty($data["sm_expire_date_to"]) ? '' : date('Y-m-d', strtotime($data["sm_expire_date_to"]));
        $companyCode = $data["sm_company_code"];
        $projectCode = $data["sm_project_code"];

        $string = DB::select("CALL sp_get_lesson_summary_for_export_csv('".$lessondateForm."','".$lessondateTo."','".$expiredateForm."','".$expiredateTo."','".$companyCode."','".$projectCode."')");
        $fileName = 'lesson_summary_'.date('YmdHis' ).'.csv';

        $columns = [
            $this->convertShijis('講師ID'),
            $this->convertShijis('講師名'),
            $this->convertShijis('受講生氏名'),
            $this->convertShijis('生徒番号'),
            $this->convertShijis('企業ID'),
            $this->convertShijis('企業名'),
            $this->convertShijis('法人コード'),
            $this->convertShijis('得意先コード'),
            $this->convertShijis('受注番号'),
            $this->convertShijis('商品コード'),
            $this->convertShijis('キャンペーンコード'),
            $this->convertShijis('セットコース名'),
            $this->convertShijis('コース名'),
            $this->convertShijis('ポイント付与数'),
            $this->convertShijis('基準日'),
            $this->convertShijis('受講開始日'),
            $this->convertShijis('有効期限'),
            $this->convertShijis('月数'),
            $this->convertShijis('レッスン実施日'),
            $this->convertShijis('レッスン時間'),
            $this->convertShijis('レッスン名'),
            $this->convertShijis('使用教材(テキスト)'),
            $this->convertShijis('① 教え方'),
            $this->convertShijis('② 態度'),
            $this->convertShijis('③ わかりやすさ'),
            $this->convertShijis('④ Skypeの音質'),
            $this->convertShijis('コメント(生徒⇒講師)'),
            $this->convertShijis('コメント(生徒⇒事務局)'),
            $this->convertShijis('コメント(講師⇒事務局)'),
            $this->convertShijis('コメント(講師⇒生徒)'),
            $this->convertShijis('出欠'),
            $this->convertShijis('修了条件'),
            $this->convertShijis('修了状況')
        ];

        if (!file_exists(public_path().'/csv_file/users')) {
            mkdir(public_path().'/csv_file/users', 0777, true);
        }
        $localPath = public_path().'/csv_file/users/'.$fileName;
        $file = fopen($localPath, 'w');
        fputcsv($file, $columns);

        foreach ($string as $item) {
            $completeStatus = '';
            $pointId = $item->point_subscription_history_id;
            $courseId = $item->course_id;
            if (empty($pointId) && $courseId == 1) {
                $completeStatus = $item->skype_voice_rating_from_teacher == 0 ? '"修了"' : '"未了"';
            } else {
                if ($item->attend/ $item->point_count * 100 >= $item->complete_require) {
                    $completeStatus = '"修了"';
                }else {
                    $completeStatus  = '"未了"';
                }
            }

            $row['講師ID'] = $this->convertShijis($item->teacher_id);
            $row['講師名'] = $this->convert_text($this->convertShijis($item->teacher_name));
            $row['受講生氏名'] = $this->convert_text($this->convertShijis($item->student_name));
            $row['生徒番号'] = $this->convertShijis($item->student_id);
            $row['企業ID'] = $this->convertShijis($item->project_code);
            $row['企業名'] = $this->convert_text($this->convertShijis($item->company_name));
            $row['法人コード'] = $this->houzinCodeConvert($this->convertShijis($item->corporation_code));
            $row['得意先コード'] = str_replace("-", "", $this->convertShijis($item->customer_code));
            $row['受注番号'] = $this->convertShijis($item->point_subscription_history_id);
            $row['商品コード'] = $this->convertShijis($item->course_code);
            $row['キャンペーンコード'] = $this->convertShijis($item->campaign_code);
            $row['セットコース名'] = $this->convert_text($this->convertShijis($item->set_course_name));
            $row['コース名'] = $this->convert_text($this->convertShijis($item->course_name));
            $row['ポイント付与数'] = $this->convertShijis($item->point_count);
            $row['基準日'] = $this->convertShijis($item->start_date);
            $row['受講開始日'] = $this->convertShijis($item->begin_date);
            $row['有効期限'] = $this->convertShijis($item->expire_date);
            $row['月数'] = $this->convertShijis($item->months);
            $row['レッスン実施日'] = $this->convertShijis($item->lesson_date);
            $row['レッスン時間'] = $this->convert_text($this->convertShijis($item->lesson_time));
            $row['レッスン名'] = $this->convert_text($this->convertShijis($item->lesson_name));
            $row['使用教材(テキスト)'] = $this->convert_text($this->convertShijis($item->lesson_text_name));
            $row['① 教え方'] = $this->convertShijis($item->teacher_rating);
            $row['② 態度'] = $this->convertShijis($item->teacher_attitude);
            $row['③ わかりやすさ'] = $this->convertShijis($item->teacher_punctual);
            $row['④ Skypeの音質'] = $this->convertShijis($item->skype_voice_rating_from_student);
            $row['コメント(生徒⇒講師)'] = $this->convert_text($this->convertShijis($item->comment_from_student_to_teacher));
            $row['コメント(生徒⇒事務局)'] = $this->convert_text($this->convertShijis($item->comment_from_student_to_office));
            $row['コメント(講師⇒事務局)'] = $this->convert_text($this->convertShijis($item->comment_from_teacher_to_office));
            $row['コメント(講師⇒生徒)'] = $this->convert_text($this->convertShijis($item->comment_from_teacher_to_student));
            $row['出欠'] = $item->skype_voice_rating_from_teacher == 0 ? '"出"' : '"欠"';
            $row['修了条件'] = $this->convertShijis($item->complete_require);
            $row['修了状況'] = $this->convertShijis($completeStatus);

            fputcsv($file, array($row['講師ID'],$row['講師名'],$row['受講生氏名'],$row['生徒番号'],$row['企業ID'],$row['企業名'],$row['法人コード'],$row['得意先コード'],$row['受注番号'],$row['商品コード'],$row['キャンペーンコード'],$row['セットコース名'],$row['コース名'],$row['ポイント付与数'],$row['基準日'],$row['受講開始日'],$row['有効期限'],$row['月数'],$row['レッスン実施日'],$row['レッスン時間'],$row['レッスン名'],$row['使用教材(テキスト)'],$row['① 教え方'],$row['② 態度'],$row['③ わかりやすさ'],$row['④ Skypeの音質'],$row['コメント(生徒⇒講師)'],$row['コメント(生徒⇒事務局)'],$row['コメント(講師⇒事務局)'],$row['コメント(講師⇒生徒)'],$row['出欠'],$row['修了条件'],$row['修了状況']));
        }

        fclose($file);
        
        return response()->json([
            'path' => url('/csv_file/users/') .'/'. $fileName,
            'file_name' => $fileName,
            'status' => StatusCode::OK
        ]);
    }
    
    
        
    private function convertShijis($text) {
        return mb_convert_encoding($text, "SJIS", "UTF-8");
    }

    private function houzinCodeConvert($str) {
        $str = str_replace("-", "", $str);
        return mb_convert_kana($str, "n", mb_detect_encoding($str));
    }

    public  function convert_text($comment)
    {
        if (!isset($comment)) {
            return $comment;
        }
        $comment = str_replace('"', '""', $comment);
        if (isset($comment)) {
            $comment = '"'.$comment.'"';
        }
        $comment = str_replace("\r", ' ', $comment);
        $comment = str_replace("\n", ' ', $comment);
        
        return $comment;
    }

    function getHeaderCSV() {
        // global $studentTypeExport;
        $columns = [
            $this->convertShijis('社員番号'),
            $this->convertShijis('所属番号'),
            $this->convertShijis('コース番号'),
            $this->convertShijis('再受講区分'),
            $this->convertShijis('受験状況区分'),
            $this->convertShijis('優秀賞区分'),
            $this->convertShijis('開講年月日'),
            $this->convertShijis('在籍年月日'),
            $this->convertShijis('修了除籍年月日'),
            $this->convertShijis('受講科目数'),
            $this->convertShijis('修了科目数'),
            $this->convertShijis('平均点')
        ];
        for ($i = 1; $i <= 20; $i ++) {
            $columns[] = $this->convertShijis('受付日_' .substr("00". $i, strlen($i), 2));
            $columns[] = $this->convertShijis('点数_' . substr("00". $i, strlen($i), 2));
            $columns[] = $this->convertShijis('合否_' . substr("00". $i, strlen($i), 2));
        }

        $columns [] = $this->convertShijis('団体コード');
        $columns [] = $this->convertShijis('データコード');
        $columns [] = $this->convertShijis('共通管理番号');
        $columns [] = $this->convertShijis('コースコード');
        $columns [] = $this->convertShijis('受講番号');
        $columns [] = $this->convertShijis('カナ氏名');
        $columns [] = $this->convertShijis('漢字氏名');
        $columns [] = $this->convertShijis('コース名');
        $columns [] = $this->convertShijis('受講料');
        $columns [] = $this->convertShijis('教訓対象区分');
        $columns [] = $this->convertShijis('備考');
        foreach ($columns as $key => &$value) {
            if(mb_detect_encoding($value) == 'UTF-8') {
                $value = mb_convert_encoding($value, "sjis-win", "UTF-8");
            }
        }
        return $columns;
    }

    public function my_create_string($str, $number) {
        $return ="";
        for ($i =0; $i < $number; $i ++) {
            $return .= $str;
        }
        return $return;
    }

    public function kana_name_convert($str) {
        $str = mb_convert_kana($str, "sk", mb_detect_encoding($str));
        $str = trim($str);
        return $str;
    }

    public function jp_strlen ($value) {
        return strlen(mb_convert_encoding($value, "sjis-win", mb_detect_encoding($value)));
    }
    public function jp_substr($str, $start, $length) {
        return substr(mb_convert_encoding($str, 'sjis-win', mb_detect_encoding($str)), $start, $length);
    }
    public function mb_trim($str,$regex = "^[ 　]*|[ 　]*$") {
        return mb_ereg_replace($regex, "", $str);
    }
    public function mb_trim_re($str,$regex = "^[ 　]*|[ 　]*$") {
        return mb_ereg_replace($regex, "", $str);
     }
}
