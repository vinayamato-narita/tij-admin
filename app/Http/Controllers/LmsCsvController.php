<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Components\BreadcrumbComponent;
use App\Enums\StatusCode;
use App\Enums\LangType;
use App\Http\Requests\LmsCsvRequest;
use Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Log;

class LmsCsvController extends BaseController
{
    public function import(Request $request)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'lms_csv_import']
        ]);

        return view('lms-csv.import', [
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    public function uploadCsv(LmsCsvRequest $request)
    {
        $message = "";
        $errorFlag = false;
        $notErrorData = 0;
        $errorSendMailData = [];
        $errorSendMailDataFlag = false;
        if(!$request->isMethod('POST')){
            return response()->json([
                'status' => 'NG',
            ], StatusCode::BAD_REQUEST);  
        }

        $fileCsv = $request->upload_csv;
        if ($fileCsv->getSize() == 0) {
            $message = "ファイルを指定してください。";
        }
        $csv = $this->readCSV($fileCsv);
        
        if (empty($csv) || !is_array($csv) || count($csv) < 2) {
            $message = 'ファイル内容がありません。';
        }
        if ($message != "") {
            return response()->json([
                'status' => 'OK',
                'csv' => $csv,
                'message' => $message,
                'errorSendMailDataFlag' => $errorSendMailDataFlag,
                'errorFlag' => $errorFlag,
                'notErrorData' => $notErrorData,
            ], StatusCode::OK);
        }
        $defautHeaderArr = array(
            '氏名姓',
            '氏名名',
            'シメイセイ',
            'シメイメイ',
            'ニックネーム',
            'パスワード',
            'メールアドレス',
            'Skype名',
            'デフォルト言語',
            'タイムゾーン',
            '電話番号',
            '郵便番号',
            '都道府県',
            '住所1',
            '住所2',
            '住所3',
            '住所4',
            '部署名',
            '社員番号',
            '所属番号',
            '企業ID',
            'コースコード',
            '開講希望月',
            '受注日',
            '基準日',
            '受講開始日',
            '有効期限',
            '共通管理番号',
            '請求区分',
            '生徒番号',
            'メール送信',
        );
        
        $columnNum = count($defautHeaderArr);
        $importHeader = $csv[0];
        if (count($importHeader) != $columnNum) {
            $message = 'ヘッダーが正しくありません。';
        } 
        if ($message != "") {
            return response()->json([
                'status' => 'OK',
                'csv' => $csv,
                'message' => $message,
                'errorSendMailDataFlag' => $errorSendMailDataFlag,
                'errorFlag' => $errorFlag,
                'notErrorData' => $notErrorData,
            ], StatusCode::OK);
        }

        foreach ($defautHeaderArr as $headerIndex => $headerName) {
            if (!isset($importHeader[$headerIndex]) || trim($importHeader[$headerIndex]) != $headerName) {
                $message = 'ヘッダーが正しくありません。（'.$headerName.'がありません。）';
                break;
            }
        }

        $countRow = count($csv) - 1;
        foreach ($csv as $key => &$value){
            if ($key == 0 || (!is_array($value) && $countRow == $key)) {
                continue;
            }

            if (!is_array($value) || count($value) != $columnNum) {
                $value['error_list'] = array('class' => 'error-common');
                $errorFlag = true;
                continue;
            }

            $errorList = $this->checkRow($value);
            if(count(array_filter($errorList)) != 0) {
                $value['error_list'] = $errorList;
            }
            if(!empty($errorList['error_and_send_mail_user_id'])){
                $tmpValue = $value;
                $tmpValue[STUDENT_ID] = $errorList['error_and_send_mail_user_id'];
                $errorSendMailData[$key] = $tmpValue;
                $errorSendMailDataFlag = true;
            }
        }
 
        Session::put('ERROR_SEND_MAIL_DATA', $errorSendMailData);
        Session::put('ERROR_SEND_MAIL_DATA_TMP', $errorSendMailData);
        $notErrorData = count($csv) - count($errorSendMailData);
        if (!$errorFlag) {
            Session::put('LMS_CSV_CONTENT', $csv);
        }
        return response()->json([
            'status' => 'OK',
            'csv' => $csv,
            'message' => $message,
            'errorSendMailDataFlag' => $errorSendMailDataFlag,
            'errorFlag' => $errorFlag,
            'notErrorData' => $notErrorData,
        ], StatusCode::OK);
    }

    public function readCSV($csvFile) 
    {
        $file_handle = fopen($csvFile, 'r');

        while (!feof($file_handle) ) {
            $row = fgetcsv($file_handle, 1024);
            if ($row != false) {
                $line_of_text[] = $row;
            }
        }
        fclose($file_handle);

        return $line_of_text;
    }

    public function checkRow($value) 
    {
        $errorList = array();
        $projectCode = $value[PROJECT_CODE];
        $courseId = $value[COURSE_ID];
        $studentId = $value[STUDENT_ID];
        for($i = 0; $i < count($value); $i ++) {
            $errorList[$i] = $this->checkData($value[$i], $i, $projectCode, $courseId, $studentId);
        }

        $checkDb = $this->check_db($value)[0];
        
        if (empty($errorList[PROJECT_CODE]) && !$checkDb->check_project) {
            $errorList[PROJECT_CODE] = array(
                'class' => 'error-project'
            );
        }

        if (empty($errorList[CORPORATION_FLAG]) && !$checkDb->check_corporation) {
            $errorList[CORPORATION_FLAG] = array(
                'class' => 'error-corporation'
            );
        }

        if ($courseId != 0) {
            if (empty($errorList[COURSE_ID]) && !$checkDb->check_course_exist) {
                $errorList[COURSE_ID] = $ret = array(
                    'class' => 'error-course-id'
                );
            }
            
            if (empty($errorList[COURSE_ID]) && (empty($checkDb->check_project_course) || !empty($checkDb->check_project_course_1) || !empty($checkDb->check_project_course_2))){
                $errorList[COURSE_ID] = $ret = array(
                    'class' => 'error-project-course'
                );
            }

            if (empty($errorList[COURSE_ID]) && !$checkDb->check_can_buy) {
                $errorList[COURSE_ID] = $ret = array(
                    'class' => 'error-cannot-buy'
                );
            }
        }

        if ($value[STUDENT_ID] == '') {
            if (empty($errorList[STUDENT_EMAIL]) && $checkDb->check_mail_exist) {
                $errorList[STUDENT_EMAIL] = array(
                    'class' => 'error-email-noneId'
                );
                $errorList[STUDENT_ID] = array(
                    'class' => 'error-email-noneId'
                );

                if (!empty($checkDb->student_id)) {
                    $errorList['student_id'] = $checkDb->student_id;
                }
                // email exist, student id empty, student - project registerd
                if (empty($courseId) && !empty($checkDb->check_project_student_exist)){
                    $errorList['error_and_send_mail_user_id'] = $checkDb->student_id;
                    $errorList[STUDENT_EMAIL] = array(
                        'class' => 'error-email-noneId-sendMail'
                    );
                    $errorList[STUDENT_ID] = array(
                        'class' => 'error-email-noneId-sendMail'
                    );
                }
            }
        }else {
            if (empty($errorList[STUDENT_ID]) && !$checkDb->check_student_id_exist) {
                $errorList[STUDENT_ID] = array(
                    'class' => 'error-student-id'
                );
            }

            if (empty($errorList[STUDENT_EMAIL]) && !$checkDb->check_mail_and_id) {
                $errorList[STUDENT_EMAIL] = array(
                    'class' => 'error-email'
                );
            }
        }
        return $errorList;
    }

    public function check_db($value) 
    {
        $isLmsUser  = 1;
        $ret = DB::select('CALL lms_sp_csv_check_db(?,?,?,?,?,?)', 
            array(
                $value[STUDENT_ID],
                $value[STUDENT_EMAIL],
                $value[PROJECT_CODE],
                (int) $value[COURSE_ID],
                $value[CORPORATION_FLAG],
                (int) $isLmsUser
            )
        );
        
        return $ret;
    }

    public function checkData($column, $type, $projectCode = "", $courseId, $studentId)
    {
        $ret = array();

        switch ($type) {
            // field 氏名
            case STUDENT_FIRST_NAME: 
            //echo $column;
                if ($column === '' && $studentId != ''){
                    break;
                }elseif ($column === ''){
                    $ret = array(
                        'class' => 'error-empty'
                    );
                } elseif ( mb_strlen($column,'UTF-8') > 100){
                    $ret = array(
                        'class' => 'error-length'
                    );
                } elseif (!$this->NGCharCheck($column)){
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                }

                break;
            case STUDENT_LAST_NAME: 
            //echo $column;
                if ($column === '' && $studentId != ''){
                    break;
                }elseif ($column === ''){
                    $ret = array(
                        'class' => 'error-empty'
                    );
                } elseif ( mb_strlen($column,'UTF-8') > 100){
                    $ret = array(
                        'class' => 'error-length'
                    );
                } elseif (!$this->NGCharCheck($column)){
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                }

                break;
            // field シメイセイ
            case STUDENT_FIRSTNAME_KANA:
                if ($column === '' && $studentId != ''){
                    break;
                }elseif ($column == ''){
                    $ret = array(
                        'class' => 'error-empty'
                    );
                }
                elseif(mb_strlen($column,'UTF-8') > 100){
                    $ret = array(
                        'class' => 'error-length'
                    );
                } elseif(!$this->checkKatakana($column)){
                    $ret = array(
                        'class' => 'error-kana'
                    );
                } 
                elseif(!$this->NGCharCheck($column)){
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                }
                

                break;
            // field シメイセイ
            case STUDENT_LASTNAME_KANA:
                if ($column === '' && $studentId != ''){
                    break;
                }elseif ($column === ''){
                    $ret = array(
                        'class' => 'error-empty'
                    );
                }
                elseif(mb_strlen($column,'UTF-8') > 100){
                    $ret = array(
                        'class' => 'error-length'
                    );
                } elseif(!$this->checkKatakana($column)){
                    $ret = array(
                        'class' => 'error-kana'
                    );
                } 
                elseif(!$this->NGCharCheck($column)){
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                }
                

                break;
            // field ニックネーム
            case STUDENT_NICKNAME:
                if ($column === ''){
                    break;
                } elseif (mb_strlen($column,'UTF-8') > 16 ){
                    $ret = array(
                        'class' => 'error-length'
                    );
                } elseif (!$this->spaceCheck($column)) {
                    $ret = array(
                        'class' => 'error-space'
                    );
                } elseif (!$this->checkHasNotHankakuKatakana($column)) {
                    $ret = array(
                        'class' => 'error-hankaku-kana'
                    );
                }elseif(!$this->NGCharCheck($column)){
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                }
                break;
            // field パスワード
            case PASSWORD:
                if ($column === '' && $studentId != ''){
                    break;
                }elseif($column === ''){
                     $ret = array(
                        'class' => 'error-empty'
                    );
                }elseif(mb_strlen($column,'UTF-8') > 16 || mb_strlen($column,'UTF-8') <8){
                    $ret = array(
                        'class' => 'error-length'
                    );
                }elseif(!$this->passwordValidate($column)){
                    $ret = array(
                        'class' => 'error-pass'
                    );
                }
                break;
            // field メールアドレス
            case STUDENT_EMAIL:
                if($column === ''){
                     $ret = array(
                        'class' => 'error-empty'
                    );
                }elseif(mb_strlen($column,'UTF-8') > 255){
                     $ret = array(
                        'class' => 'error-length'
                    );
                }elseif(!$this->isValidEmail($column)){
                    $ret = array(
                        'class' => 'error-email-name'
                    ); 
                }elseif(!$this->NGCharCheck($column)){
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                }
                break;
            // field Skype名
            case SKYPE_NAME:
                if($column === ''){
                     break;
                }elseif(!$this->checkSkype($column)){
                    $ret = array(
                        'class' => 'error-skype-name'
                    ); 
                }
                break;
            // field 共通管理番号
            case MANAGEMENT_NUMBER:
                if($column === ''){
                    break;
                }elseif(strlen($column) >10){
                    $ret = array(
                        'class' => 'error-length'
                    );
                }elseif(!$this->checkManagementNumber($column)){
                    $ret = array(
                        'class' => 'error-alphanumeric-symbol'
                    );
                }
                break;
            // 自宅電話番号
            case STUDENT_TEL:
                if($column == "") {
                    break;
                }
                elseif(mb_strlen($column,'UTF-8') > 20){
                    $ret = array(
                        'class' => 'error-length'
                    );
                } elseif (!$this->spaceCheck($column)){
                    $ret = array(
                        'class' => 'error-space'
                    );
                } elseif(!$this->NGCharCheck($column)){
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                }elseif ($column != "" && !$this->checkPhoneNumber($column)) {
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                }
                break;
            //郵便番号
            case POSTCODE:
                if($column == "") {
                    break;
                }
                if (mb_strlen($column,'UTF-8') > 10){
                    $ret = array(
                        'class' => 'error-length'
                    );
                } elseif (!$this->spaceCheck($column)){
                    $ret = array(
                        'class' => 'error-space'
                    );
                } elseif(!$this->NGCharCheck($column)){
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                }elseif (!$this->checkPostCode($column)) {
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                }
                break;
            //都道府県
            case PREFECTURE:
                if($column == "") {
                    break;
                }elseif (!$this->spaceCheck($column)){
                    $ret = array(
                        'class' => 'error-space'
                    );
                }elseif(!$this->NGCharCheck($column)){
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                }else{ 
                    if (!$this->checkPrefecture($column)) {
                        $ret = array(
                            'class' => 'error-prefecture'
                        );
                    }
                }
                break;
            // [住所1] [住所2] [住所3] [住所4]
            case ADDRESS1 :
                if ($column == "") {
                    break;
                }
                elseif(mb_strlen($column,'UTF-8') > 50){
                    $ret = array(
                        'class' => 'error-length'
                    );
                }elseif(!$this->checkHasNotHankakuKatakana($column)){
                    $ret = array(
                        'class' => 'error-hankaku-kana'
                    );
                } elseif(!$this->NGCharCheck($column)){
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                }
                break;
            case ADDRESS2 :
            case ADDRESS3 :
            case ADDRESS4 :
                if ($column == "") {
                    break;
                }

                if(mb_strlen($column,'UTF-8') > 50){
                    $ret = array(
                        'class' => 'error-length'
                    );
                }elseif(!$this->checkHasNotHankakuKatakana($column)){
                    $ret = array(
                        'class' => 'error-hankaku-kana'
                    );
                } elseif(!$this->NGCharCheck($column)){
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                }
                break;
            // 部署名
            case DEPARTMENT_NAME :
                if ($column == "") {
                    break;
                }

                if(mb_strlen($column,'UTF-8') > 100){
                    $ret = array(
                        'class' => 'error-length'
                    );
                }elseif(!$this->checkHasNotHankakuKatakana($column)){
                    $ret = array(
                        'class' => 'error-hankaku-kana'
                    );
                } elseif(!$this->NGCharCheck($column)){
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                }
                break;
            // 社員番号
            case EMPLOYEE_NUMBER :
                if ($column == "") {
                    break;
                }

                if(mb_strlen($column,'UTF-8') > 100){
                    $ret = array(
                        'class' => 'error-length'
                    );
                } elseif(!$this->checkHankakuCharacterAndNumberOnly($column)){
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                } elseif(!$this->NGCharCheck($column)){
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                }
                break;
            //所属番号
            case DEPARTMENT_NUMBER :
                if ($column == "") {
                    break;
                }

                if(mb_strlen($column,'UTF-8') > 100){
                    $ret = array(
                        'class' => 'error-length'
                    );
                } elseif(!$this->checkHankakuCharacterAndNumberOnly($column)){
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                } elseif(!$this->NGCharCheck($column)){
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                }
                break;
            //企業ID
            case PROJECT_CODE :
                if($column === ''){
                    $ret = array(
                        'class' => 'error-empty'
                    );
                } elseif (!$this->NGCharCheck($column)) {
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                } elseif (!$this->spaceCheck($column)){
                    $ret = array(
                        'class' => 'error-space'
                    );
                } elseif(!$this->checkHankakuNumberOnly($column)){
                    $ret = array(
                        'class' => 'error-project'
                    );
                }
                break;
            // field コースコード（EduCross）
            case COURSE_ID:
                if($column === '') {
                    $ret = array(
                        'class' => 'error-empty'
                    );
                }
                elseif($column === '0'){
                    break;
                }elseif(!$this->checkHankakuNumberOnly($column)){
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                }
                break;
            // 開講希望月
            case COURSE_BEGIN_MONTH:
                if($courseId === '' || $courseId === '0'){
                    break;
                }
                if($column === ''){
                    $ret = array(
                        'class' => 'error-empty'
                    );
                } elseif(!$this->checkCourseBeginMonth($column)) {
                    $ret = array(
                        'class' => 'error-course-begin-month'
                    );
                }
                break;
            // field 受注日
            case ORDER_DATE:
                if($courseId === '' || $courseId === '0'){
                    break;
                }

                if($column === ''){
                     $ret = array(
                        'class' => 'error-empty'
                    );
                }elseif(mb_strlen($column,'UTF-8') !==  8){
                      $ret = array(
                        'class' => 'error-length'
                    );
                }elseif(!$this->validateDate($column)){
                    $ret = array(
                        'class' => 'error-date'
                    );
                }
                break;
            // field 基準日
            case START_DATE:
                if($courseId === '' || $courseId === '0'){
                    break;
                }

                if($column === ''){
                    $ret = array(
                        'class' => 'error-empty'
                    );
                }elseif(mb_strlen($column,'UTF-8') !==  8){
                     $ret = array(
                        'class' => 'error-length'
                    );
                }elseif(!$this->validateDate($column)){
                    $ret = array(
                        'class' => 'error-date'
                    );
                }
                break;
            // field 開講開始日
            case BEGIN_DATE:
                if($courseId === '' || $courseId === '0'){
                    break;
                }

                if($column === ''){
                    $ret = array(
                        'class' => 'error-empty'
                    );
                }elseif(mb_strlen($column,'UTF-8') !==  8){
                     $ret = array(
                        'class' => 'error-length'
                    );
                }elseif(!$this->validateDate($column)){
                    $ret = array(
                        'class' => 'error-date'
                    );
                }
                break;

            // field 有効期限
            case EXPIRE_DATE:
                if($column === '' || $courseId === '0'){
                    break;
                }elseif (!$this->spaceCheck($column)) {
                    $ret = array(
                        'class' => 'error-space'
                    );
                }elseif(mb_strlen($column) !==  8){
                     $ret = array(
                        'class' => 'error-length'
                    );
                }elseif(!$this->validateDate($column)){
                    $ret = array(
                        'class' => 'error-date'
                    );
                }
                break;
            
            // 請求区分
            case CORPORATION_FLAG :
                // if($courseId === '' || $courseId === '0'){
                //     break;
                // }
                if($column === ''){
                    $ret = array(
                        'class' => 'error-empty'
                    );
                }
                elseif($column !== "1" && $column !== "0"){
                     $ret = array(
                        'class' => 'error-corporation'
                    );
                }
                break;
            // field 生徒番号
            case STUDENT_ID:
                if($column !== ''){
                    if (!$this->checkHankakuNumberOnly($column)) {
                        $ret = array(
                        'class' => 'error-special-text'
                    ); 
                    }
                }
                break;
            // メール送信フラッグ
            case SEND_MAIL_FLAG :
                if ($column === "") {
                    $ret = array(
                        'class' => 'error-empty'
                    );
                } elseif ($column != 0 && $column != 1) {
                    $ret = array(
                        'class' => 'error-common'
                    );
                }
                break;
            case LANG_TYPE :
                $column = trim($column);
                if($column === '') {
                    break;
                } elseif (!$this->checkLangtype($column)) {
                    $ret = array(
                        'class' => 'error-lang-type'
                    );
                }
                break;
            case TIME_ZONE :
                $column = trim($column);
                if($column === '') {
                    break;
                } elseif (!$this->checkTimezone($column)) {
                    $ret = array(
                        'class' => 'error-time-zone'
                    );
                }
                break;
            default:
                break;
        }

        return $ret;
    }

    /*
   * Validate character
   */
    public function NGCharCheck($data) 
    {
        if (!strlen($data)) {
            return true;       
        }

        if (strpos($data, "\\") !== false) {
            return false;
        }
        if (strpos($data, "￥") !== false) {
            return false;
        }
        if (preg_match("/[\t\r\n\/\<>\"\&']/", $data)) {
            return false;
        }

        if (strlen($data) !== strlen(mb_convert_encoding(mb_convert_encoding($data,'SJIS','UTF-8'),'UTF-8','SJIS'))) {
            return false;
        }
        return true;
    }

    public function checkKatakana($check)
    {
        mb_regex_encoding('UTF-8');
        return $check== "" || mb_ereg("^[A-Za-zア-ン゛゜ァ-ォャ-ョーヴ 　]+$", $check);
    }

    public function spaceCheck($data) 
    {
        if (preg_match("/[ 　]+/u", $data)) {
            return false;
        }

        return true;
    }

    public function checkHasNotHankakuKatakana($data) 
    {
        if (preg_match('/[ｦ-ﾟｰ]+/u', $data)) {
            return false;
        }

        return true;
    }  

    public function passwordValidate($check) 
    {
        $length = mb_strlen($check, 'UTF-8');
        if ($length < 8 || $length > 16) {
            return false;
        }
        if(!preg_match('/^[A-Za-z0-9]+$/i', $check)){
            return false;
        }
        
        if(!preg_match('/[A-Za-z]/', $check)){
            return false;
        }
        
        if(!preg_match('/[0-9]/', $check)){
           return false;
        }
        return true;
    }

    public function isValidEmail($email)
    { 
         return filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match('/@.+\./', $email);
    }

    public function checkSkype($check)
    {
        return preg_match('/^[a-zA-Z][a-zA-Z0-9_\-\.\,\:]{5,31}$/i', $check);
    }

    public function checkManagementNumber($str) 
    {
        return preg_match('/^[!-~]+$/', $str);
    }

    public function checkPhoneNumber($check) 
    {
        return preg_match("/^[0-9-]+$/", $check);
    }

    public function checkPostCode($check) 
    {
        return preg_match("/^[0-9-]+$/", $check);
    }

    public function checkPrefecture($data) 
    {
        if (!preg_match("/^[0-9]+$/", $data) || $data < 1 || $data >47) {
            return false;
        }
        return true;
    }

    public function checkHankakuCharacterAndNumberOnly($check) 
    {
        return preg_match('/^[a-zA-Z0-9]+$/', $check);
    }

    public function checkHankakuNumberOnly($check) 
    {
        return preg_match('/^[0-9]+$/', $check);
    }

    public function checkCourseBeginMonth($date)
    {
        $d = \DateTime::createFromFormat('Ymd', $date."01");
        return $d && $d->format('Ym') == $date;
    }

    public function validateDate($date)
    {
        $d = \DateTime::createFromFormat('Ymd', $date);
        return $d && $d->format('Ymd') == $date;
    }

    public function checkLangtype($check) 
    {
        return preg_match("/^(jp|en|vn)$/", $check);
    }

    public function checkTimezone($check) 
    {
        return (is_numeric($check) && (int) $check <= 75 && (int) $check >= 1);
    }

    public function importCsv(request $request) 
    {
        $message = "";
        $errorFlag = false;
        $csv = [];
        $notErrorData = 0;
        $errorSendMailDataFlag = false;
        $errorSendMailDataTMP = [];
        $finaldata = [];
        
        $resultlogFile = date('Y-m-d').'result.log';
        if(!$request->isMethod('POST')){
            return response()->json([
                'status' => 'NG',
                'errorSendMailDataFlag' => $errorSendMailDataFlag,
            ], StatusCode::BAD_REQUEST);  
        }

        $content = Session::get('LMS_CSV_CONTENT');
        Session::forget('LMS_CSV_CONTENT');
        if (Session::get('ERROR_SEND_MAIL_DATA_TMP')) {
            $errorSendMailDataTMP = Session::get('ERROR_SEND_MAIL_DATA_TMP');
        }
        if (Session::get('ERROR_SEND_MAIL_DATA')) {
            $errorSendMailDataFlag = true;;
        }

        $row = 1;
        $mailPatern = $this->getRemindMailCsv();

        foreach ($content as $key => $data) {
            
            $row ++;
            if ($data[STUDENT_ID] ==='') {
                $st_id = 0;
            } else {
                $st_id = $data[STUDENT_ID];
            }

            if ($data[LANG_TYPE] == 'en') {
                $st_langtype = 2;
            } else if ($data[LANG_TYPE] == 'vn') {
                $st_langtype = 3;
            } else {
                $st_langtype = 1;
            }

            if ($data[TIME_ZONE] === "") {
                $data[TIME_ZONE] = 1;
            }

            if ($row > 2 && !isset($errorSendMailDataTMP[$key])) {
                try{

                    $result = $this->insert_csv_row(
                        trim($data[STUDENT_FIRST_NAME]),
                        trim($data[STUDENT_LAST_NAME]),
                        trim($data[STUDENT_FIRSTNAME_KANA]),
                        trim($data[STUDENT_LASTNAME_KANA]),
                        trim($data[STUDENT_NICKNAME]),
                        trim($data[PASSWORD]),
                        trim($data[STUDENT_EMAIL]),
                        trim($data[SKYPE_NAME]),
                        trim($data[MANAGEMENT_NUMBER]),
                        trim($data[STUDENT_TEL]),
                        trim($data[POSTCODE]),
                        trim($data[PREFECTURE]),
                        trim($data[ADDRESS1]),
                        trim($data[ADDRESS2]),
                        trim($data[ADDRESS3]),
                        trim($data[ADDRESS4]),
                        trim($data[DEPARTMENT_NAME]),
                        trim($data[EMPLOYEE_NUMBER]),
                        trim($data[DEPARTMENT_NUMBER]),
                        trim($data[PROJECT_CODE]),
                        trim($data[COURSE_ID]),
                        trim($data[COURSE_BEGIN_MONTH]),
                        trim($data[ORDER_DATE]),
                        trim($data[START_DATE]),
                        trim($data[BEGIN_DATE]),
                        trim($data[EXPIRE_DATE]),
                        trim($data[CORPORATION_FLAG]),
                        $st_id,
                        trim($st_langtype),
                        trim($data[TIME_ZONE])
                    );
                   
                    $courseId = $data[COURSE_ID];
                    $dataInfo = array(
                        "course_id" => $data[COURSE_ID],
                        "order_date" => $data[ORDER_DATE],
                        "student_email" => $data[STUDENT_EMAIL],
                        "student_password" => $data[PASSWORD],
                        "start_date" => $data[START_DATE],
                        "begin_date" => $data[BEGIN_DATE]
                    );
                    
                    if (isset($result[0]->error)) {
                        $ret = $result[0]->error;
                    } else {
                        $ret = isset($result[0]->update_flg) ? $result[0]->update_flg : -1;
                    }
                    $this->log_file($resultlogFile, $ret);
                    if(is_numeric($ret) && $ret != -1){
                        if($ret == 1 ){
                            if ($courseId >0){
                                array_push($data, "コース追加―完了");
                                //mailtype 31

                                if (!empty($mailPatern[31]) && $data[SEND_MAIL_FLAG]) {
                                    $mailType = 31;
                                    $dataInfoSendMail = $this->getDataForSendMail($mailType, $result[0]->lang_type, $mailPatern);
                                    $this->doSendMail(array_merge($result[0], $dataInfoSendMail, $dataInfo), 4);
                                }

                            }else{
                                array_push($data, "更新なし");
                            }
                            $this->log_file($resultlogFile, implode(',',$data));
                        }else if($ret == 0){ // add state
                            if($courseId >0) {
                                array_push($data, "生徒追加・コース追加―完了");
                                //mailtype 30

                                if (!empty($mailPatern[30]) && $data[SEND_MAIL_FLAG]) {
                                    $mailType = 30;
                                    $dataInfoSendMail = $this->getDataForSendMail($mailType, $result[0]->lang_type, $mailPatern);
                                    $this->doSendMail(array_merge($result[0], $dataInfoSendMail, $dataInfo), 3);
                                }
                            } else{
                                array_push($data, "生徒追加―完了");
                                //mailtype 29, 32
                                //ユーザー登録のみ（個人請求）
                                //ユーザー登録のみ（法人請求）

                                if (!empty($mailPatern[29]) && $data[SEND_MAIL_FLAG] && $data[CORPORATION_FLAG] == 0) {
                                    $mailType = 29;
                                    $dataInfoSendMail = $this->getDataForSendMail($mailType, $result[0]->lang_type, $mailPatern);
                                    $this->doSendMail(array_merge($result[0], $dataInfoSendMail, $dataInfo), 1);
                                }else if(!empty($mailPatern[32]) && $data[SEND_MAIL_FLAG] && $data[CORPORATION_FLAG] == 1) {
                                    $mailType = 32;
                                    $dataInfoSendMail = $this->getDataForSendMail($mailType, $result[0]->lang_type, $mailPatern);
                                    $this->doSendMail(array_merge($result[0], $dataInfoSendMail, $dataInfo), 2);
                                }
                            }
                            $this->log_file($resultlogFile, implode(',',$data));
                        }elseif($ret == 3){
                            array_push($data, "生徒追加―エラー");
                            $this->log_file($resultlogFile, implode(',',$data));
                        }elseif($ret == 4){
                            array_push($data, "コース追加―エラー");
                            $this->log_file($resultlogFile, implode(',',$data));
                        }elseif($ret == 5){
                            array_push($data, "購入不可エラー");
                            $this->log_file($resultlogFile, implode(',',$data));
                        }
                        else{
                            array_push($data, "エラー"); // add state
                            $this->log_file($resultlogFile, implode(',',$data));
                        }
                    }else{
                        array_push($data, "エラー"); // add state
                        $this->log_file($resultlogFile, implode(',',$data));
                    }
                }
                catch (Exception $e){
                    array_push($data, "エラー"); // add state
                    $this->log_file($resultlogFile, implode(',',$data));
                    $errMsg = ($row-1)."行目以降の登録に失敗しました。".($row-1)."行目以降のデータを再登録してください。";
                }
            }else if($row == 2){
                array_push($data, "状況"); // add state first column
            }else {
                array_push($data, "");
            }

            array_push($finaldata, $data); // store $data to $finaldata to show table
        }

        Session::forget('ERROR_SEND_MAIL_DATA_TMP');

        return response()->json([
            'status' => 'OK',
            'finaldata' => $finaldata,
            'errorSendMailDataTMP' => $errorSendMailDataTMP,
            'errorSendMailDataFlag' => $errorSendMailDataFlag,
        ], StatusCode::OK);
    }

    public function getRemindMailCsv() 
    {
        $query = DB::table("send_remind_mail_patterns as srmp")
        ->leftJoin('send_remind_mail_pattern_infos as srmpe', function($join) {
            $join->on('srmp.id', '=', 'srmpe.send_remind_mail_pattern_id');
            $join->where('srmpe.lang_type', 'en');
        })
        ->leftJoin('send_remind_mail_pattern_infos as srmpv', function($join) {
            $join->on('srmp.id', '=', 'srmpv.send_remind_mail_pattern_id');
            $join->where('srmpv.lang_type', 'vn');
        })
        ->select('srmp.mail_subject as mail_subject',
            DB::raw("COALESCE(srmpe.mail_subject, srmp.mail_subject) AS mail_subject_en"),
            DB::raw("COALESCE(srmpv.mail_subject, srmp.mail_subject) AS mail_subject_vn"),
            'srmp.mail_body as mail_body',
            DB::raw("COALESCE(srmpe.mail_body, srmp.mail_body) AS mail_body_en"),
            DB::raw("COALESCE(srmpv.mail_body, srmp.mail_body) AS mail_body_vn"),
            'srmp.send_remind_mail_timing_type as send_remind_mail_timing_type')
        ->whereIn('send_remind_mail_timing_type', [29, 30, 31, 32, 44])
        ->get()
        ->keyBy('send_remind_mail_timing_type');
        return $query;
    }

    public function log_file($fileName, $msg)
    {
        Storage::disk('local')->append($fileName, "[" . date("Y/m/d H:i:s", time()) . "] " . $msg);
    }

    public function insert_csv_row(
            $student_first_name,
            $student_last_name,
            $student_first_name_kana,
            $student_last_name_kana,
            $student_nickname,
            $student_password,
            $student_email,
            $student_skypename,
            $management_number,
            $student_home_tel,
            $postcode,
            $prefecture_name,
            $student_address,
            $student_address1,
            $student_address2,
            $student_address3,
            $department_name,
            $employee_number,
            $department_number,
            $project_code,
            $course_id,
            $course_begin_month,
            $received_order_date,
            $start_date,
            $begin_date,
            $expired_date,
            $corporation_flag,
            $student_id,
            $lang_type,
            $time_zone
            ) 
    {
        $ret = DB::select('CALL lms_sp_import_student_csv(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', 
            array(
                $student_first_name,
                $student_last_name,
                $student_first_name_kana,
                $student_last_name_kana,
                $student_nickname,
                $student_password,
                $student_email,
                $student_skypename,
                $student_home_tel,
                $postcode,
                (int) $prefecture_name,
                $student_address,
                $student_address1,
                $student_address2,
                $student_address3,
                $department_name,
                $employee_number,
                $department_number,
                $project_code,
                (int) $course_id,
                $course_begin_month,
                $received_order_date,
                $start_date,
                $begin_date,
                $expired_date,
                $management_number,
                $corporation_flag,
                (int) $student_id,
                $lang_type,
                (int) $time_zone
            )
        );

        return $ret;
    }

    public function getDataForSendMail($mailType,$langType,$mail) 
    {
        $dataInfo = [];
        if ($langType == 2) {
            $dataInfo['mail_body'] = $mail[$mailType]['mail_body_en'];
            $dataInfo['mail_subject'] = $mail[$mailType]['mail_subject_en'];
            $dataInfo['mail_domain'] = $mail[$mailType]['mail_domain'];
            $dataInfo['mail_from_name'] = $mail[$mailType]['mail_from_name'];
        } else if ($langType == 3) {
            $dataInfo['mail_body'] = $mail[$mailType]['mail_body_vn'];
            $dataInfo['mail_subject'] = $mail[$mailType]['mail_subject_vn'];
            $dataInfo['mail_domain'] = $mail[$mailType]['mail_domain'];
            $dataInfo['mail_from_name'] = $mail[$mailType]['mail_from_name'];
        } else {
            $dataInfo['mail_body'] = $mail[$mailType]['mail_body'];
            $dataInfo['mail_subject'] = $mail[$mailType]['mail_subject'];
            $dataInfo['mail_domain'] = $mail[$mailType]['mail_domain'];
            $dataInfo['mail_from_name'] = $mail[$mailType]['mail_from_name'];
        }

        return $dataInfo;
    }

    public function doSendMail($data, $type = 0) 
    {
        if ($type == 1 || $type == 2) {
            $data["course_name"] = "";
            $data["amount"] = "";
            $data["tax"] = "";
            $data["start_date"] =  "" ;
            $data["begin_date"] =  "" ;
            $data["expire_date"] = "" ;
            $data["order_date"] = "";
            $data["order_id"] = "";
        }

        if ($type == 4) {
            $data["student_password"] = "";
        }
        $mail_body = $data["mail_body"];
        $mail_body = str_replace("#STUDENT_NAME#",$data["student_name"],$mail_body);
        $mail_body = str_replace("#STUDENT_PASSWORD#",$data["student_password"],$mail_body);
        $mail_body = str_replace("#STUDENT_EMAIL#",$data["student_email"],$mail_body);

        $mail_body = str_replace("#COURSE_ID#",$data["course_id"],$mail_body);
        $mail_body = str_replace("#COURSE_NAME#",$data["course_name"],$mail_body);
        $mail_body = str_replace("#COURSE_PRICE#",$data["amount"],$mail_body);
        $mail_body = str_replace("#COURSE_TAX#",$data["tax"],$mail_body);
        $mail_body = str_replace("#START_DATE#",empty($data["begin_date"]) ? "" : date("Y-m-d",strtotime($data["begin_date"])),$mail_body);
        $mail_body = str_replace("#EXPIRE_DATE#",empty($data["expire_date"]) ? "" : date("Y-m-d",strtotime($data["expire_date"])),$mail_body);
        $mail_body = str_replace("#ORDER_DATE#",empty($data["order_date"]) ? "" : date("Y-m-d",strtotime($data["order_date"])),$mail_body);
        $mail_body = str_replace('#STUDENT_ADDRESS#',  $data["postcode"]. ' ' .
                                                        $data["prefecture_name"].
                                                        $data["student_address"].
                                                        $data["student_address1"].
                                                        $data["student_address2"].
                                                        $data["student_address3"], $mail_body);
        if($type == 1 || $type == 2) {
            $mail_body = str_replace("#COURSE_TOTAL#","",$mail_body);
        } else {
            $mail_body = str_replace("#COURSE_TOTAL#",$data["amount"] + $data["tax"],$mail_body);
        }
        $mail_body = str_replace("#ORDER_ID#",$data["order_id"],$mail_body);
        
        return $this->Common->sendMail($data["student_email"],$data["mail_domain"],$data["mail_from_name"], $data["mail_subject"], $mail_body);

        Mail::raw($mail_body, function ($message) use ($data) {
            $message->to($data["student_email"])
                ->subject($data["mail_subject"]);
        });
    }

    public function importSendMail()
    {
        $errorSendMailData = array();
        $sentMail = 1;
        $errorSendMailDataFlag = 0;
        if (Session::get('ERROR_SEND_MAIL_DATA')) {
            $errorSendMailData = Session::get('ERROR_SEND_MAIL_DATA');
            $sentMail = 0;
            $errorSendMailDataFlag = 1;
        }

        return view('lms-csv.import-send-mail', [
            'sentMail' => $sentMail,
            'errorSendMailData' => $errorSendMailData,
            'errorSendMailDataFlag' => $errorSendMailDataFlag,
        ]);
    }

    public function sendMail(Request $request)
    {
        if(!$request->isMethod('POST')){
            return response()->json([
                'status' => 'NG',
            ], StatusCode::BAD_REQUEST);  
        }
        $kewRowArr = explode(",", $request->keyRows);
        if (Session::get('ERROR_SEND_MAIL_DATA')) {
            $errorSendMailData = Session::get('ERROR_SEND_MAIL_DATA');
            $mailTemp = $this->getRemindMailCsv();
            $mailPatern = $mailTemp[44];
            foreach ($kewRowArr as $id) {
                if (!isset($errorSendMailData[$id])){
                    continue;
                }
                $this->doSendMailExistUser($errorSendMailData[$id], $mailPatern);
            }
        }
        Session::forget('ERROR_SEND_MAIL_DATA');

        return response()->json([
            'status' => 'OK',
        ], StatusCode::OK);  
    }

    public function doSendMailExistUser ($data, $mailInfo) 
    {
        $mail_body = $mailInfo->mail_body;
        $mail_subject = $mailInfo->mail_subject;
        if ($data[LANG_TYPE] == 'en') {
            $mail_body = $mailInfo->mail_body_en;
            $mail_subject = $mailInfo->mail_subject_en;
        } else if ($data[LANG_TYPE] == 'vn') {
            $mail_body = $mailInfo->mail_body_vn;
            $mail_subject = $mailInfo->mail_subject_vn;
        }
        $mail_body = str_replace("#STUDENT_NAME#", $data[STUDENT_FIRST_NAME]." ".$data[STUDENT_LAST_NAME] ,$mail_body);

        Mail::raw($mail_body, function ($message) use ($data, $mail_subject) {
            $message->to($data[STUDENT_EMAIL])
                ->subject($mail_subject);
        });
    }

    public function setCourseImport() 
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'lms_set_course_import']
        ]);

        return view('lms-csv.set-course-import', [
            'breadcrumbs' => $breadcrumbs,
        ]);

        
    }

    public function uploadSetCourseCsv(LmsCsvRequest $request)
    {
        $message = "";
        set_time_limit(1000);
        if(!$request->isMethod('POST')){
            return response()->json([
                'status' => 'NG',
            ], StatusCode::BAD_REQUEST);  
        }

        $fileCsv = $request->upload_csv;
        if ($fileCsv->getSize() == 0) {
            $message = "ファイルを指定してください。";
        }
        $csv = $this->readCSV($fileCsv);
        
        if (empty($csv) || !is_array($csv) || count($csv) < 2) {
            $message = 'ファイル内容がありません。';
        }
        if ($message != "") {
            return response()->json([
                'status' => 'OK',
                'message' => $message,
            ], StatusCode::OK);
        }

        $defautHeaderArr = array(
            '氏名姓',
            '氏名名',
            'シメイセイ',
            'シメイメイ',
            'ニックネーム',
            'パスワード',
            'メールアドレス',
            'Skype名',
            'デフォルト言語',
            'タイムゾーン',
            '電話番号',
            '郵便番号',
            '都道府県',
            '住所1',
            '住所2',
            '住所3',
            '住所4',
            '部署名',
            '社員番号',
            '所属番号',
            '企業ID',
            'セットコード',
            '開講希望月',
            '受注日',
            '基準日',
            '受講開始日',
            '有効期限',
            '共通管理番号',
            '請求区分',
            '生徒番号',
            'メール送信',
        );
        
        $columnNum = count($defautHeaderArr);
        $importHeader = $csv[0];
        if (count($importHeader) != $columnNum) {
            $message = 'ヘッダーが正しくありません。';
        } 
        if ($message != "") {
            return response()->json([
                'status' => 'OK',
                'message' => $message,
            ], StatusCode::OK);
        }

        /*foreach ($defautHeaderArr as $headerIndex => $headerName) {
            if (!isset($importHeader[$headerIndex]) || trim($importHeader[$headerIndex]) != $headerName) {
                $message = 'ヘッダーが正しくありません。（'.$headerName.'がありません。）';
                return response()->json([
                    'status' => 'OK',
                    'message' => $message,
                ], StatusCode::OK);
            }
        }*/

        $errorFlag = false;
        $setCourseIds = [];
        foreach ($csv as $key => &$value){
            if ($key == 0) {
                continue;
            }

            if (!is_array($value) || count($value) != $columnNum) {
                $value['error_list'] = array('class' => 'error-common');
                $errorFlag = true;
                continue;
            }

            $errorList = $this->_checkRow($value);
            if (!empty($errorList)) {
                $errorFlag = true;
                $value['error_list'] = $errorList;
            }
            $setCourseIds[] = $value[COURSE_ID];
        }

        if ($errorFlag) {
            return response()->json([
                'status' => 'OK',
                'csv' => $csv,
                'errorFlag' => $errorFlag,
            ], StatusCode::OK);
        }

        $results = DB::table('course_set_course')
            ->select('set_course_id', 'course_id')
            ->whereIn('set_course_id', $setCourseIds)
            ->get()
            ->toArray();

        $childCourseList = array();
        foreach ($results as $item) {
            $childCourseList[$item['set_course_id']][] =  $item['course_id'];
        }
        Session::put('LMS_SETCOURSE_IMPORT', $csv);

        return response()->json([
            'status' => 'OK',
            'csv' => $csv,
            'errorFlag' => $errorFlag,
            'childCourseList' => $childCourseList,
        ], StatusCode::OK);
    }

    private function _checkRow($value) 
    {
        $errorList = array();
        $projectCode = $value[PROJECT_CODE];
        $courseId = $value[COURSE_ID];
        for($i = 0; $i < count($value); $i ++) {
            // dont check sum colum when add course only
            if (!empty($value[STUDENT_ID]) && $value[$i] == "" && in_array($i, $this->canEmptyWhenStudentExist)) {
                $checkData = array();
            }else {
                $checkData = $this->_checkData($value[$i], $i, $projectCode, $courseId);
            }

            if (!empty($checkData)) {
                $errorList[$i] = $checkData;
            }
        }

        $checkDb =   $this->_check_db($value);
        if (empty($errorList[PROJECT_CODE]) && empty($checkDb->check_project)) {
            $errorList[PROJECT_CODE] = array(
                'class' => 'error-project'
            );
        }

        if (empty($errorList[CORPORATION_FLAG]) && empty($checkDb->check_corporation)) {
            $errorList[CORPORATION_FLAG] = array(
                'class' => 'error-corporation'
            );
        }

        if ($courseId != 0) {
            if (empty($errorList[COURSE_ID]) && empty($checkDb->check_course_exist)) {
                $errorList[COURSE_ID] = $ret = array(
                    'class' => 'error-course-id'
                );
            }

            if (empty($errorList[COURSE_ID]) && (empty($checkDb->check_project_course) || !empty($checkDb->check_project_course_1) || !empty($checkDb->check_project_course_2))){
                $errorList[COURSE_ID] = $ret = array(
                    'class' => 'error-project-course'
                );
            }

            if (empty($errorList[COURSE_ID]) && empty($checkDb->check_can_buy)) {
                $errorList[COURSE_ID] = $ret = array(
                    'class' => 'error-cannot-buy'
                );
            }
        }

        if ($value[STUDENT_ID] == '') {
            if (empty($errorList[STUDENT_EMAIL]) && !empty($checkDb->check_mail_exist)) {
                $errorList[STUDENT_EMAIL] = array(
                    'class' => 'error-email-noneId'
                );
                $errorList[STUDENT_ID] = array(
                    'class' => 'error-email-noneId'
                );
                if (!empty($checkDb->student_id)) {
                    $errorList['student_id'] = $checkDb->student_id;
                }
            }
        }else {
            if (empty($errorList[STUDENT_ID]) && empty($checkDb->check_student_id_exist)) {
                $errorList[STUDENT_ID] = array(
                    'class' => 'error-student-id'
                );
            }

            if (empty($errorList[STUDENT_EMAIL]) && empty($checkDb->check_mail_and_id)) {
                $errorList[STUDENT_EMAIL] = array(
                    'class' => 'error-email'
                );
            }
        }
        return $errorList;
    }

    public function _checkData($column, $type, $projectCode = "", $courseId)
    {
        $ret = array();
        switch ($type) {
            // field 氏名
            case STUDENT_FIRST_NAME:
            //echo $column;
                if ($column === ''){
                    $ret = array(
                        'class' => 'error-empty'
                    );
                } elseif ( mb_strlen($column,'UTF-8') > 100){
                    $ret = array(
                        'class' => 'error-length'
                    );
                } elseif (!$this->NGCharCheck($column)){
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                }

                break;
            case STUDENT_LAST_NAME:
            //echo $column;
                if ($column === ''){
                    $ret = array(
                        'class' => 'error-empty'
                    );
                } elseif ( mb_strlen($column,'UTF-8') > 100){
                    $ret = array(
                        'class' => 'error-length'
                    );
                } elseif (!$this->NGCharCheck($column)){
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                }

                break;
            // field シメイセイ
            case STUDENT_FIRSTNAME_KANA:
                if ($column == ''){
                    $ret = array(
                        'class' => 'error-empty'
                    );
                }
                elseif(mb_strlen($column,'UTF-8') > 100){
                    $ret = array(
                        'class' => 'error-length'
                    );
                } elseif(!$this->checkKatakana($column)){
                    $ret = array(
                        'class' => 'error-kana'
                    );
                }
                elseif(!$this->NGCharCheck($column)){
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                }


                break;
            // field シメイセイ
            case STUDENT_LASTNAME_KANA:
                if ($column === ''){
                    $ret = array(
                        'class' => 'error-empty'
                    );
                }
                elseif(mb_strlen($column,'UTF-8') > 100){
                    $ret = array(
                        'class' => 'error-length'
                    );
                } elseif(!$this->checkKatakana($column)){
                    $ret = array(
                        'class' => 'error-kana'
                    );
                }
                elseif(!$this->NGCharCheck($column)){
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                }


                break;
            // field ニックネーム
            case STUDENT_NICKNAME:
                if($column === ''){
                    break;
                } elseif (mb_strlen($column,'UTF-8') > 16 ){
                    $ret = array(
                        'class' => 'error-length'
                    );
                } elseif (!$this->spaceCheck($column)) {
                    $ret = array(
                        'class' => 'error-space'
                    );
                } elseif (!$this->checkHasNotHankakuKatakana($column)) {
                    $ret = array(
                        'class' => 'error-hankaku-kana'
                    );
                }elseif(!$this->NGCharCheck($column)){
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                }
                break;
            // field パスワード
            case PASSWORD:
                if($column === ''){
                     $ret = array(
                        'class' => 'error-empty'
                    );
                }elseif(mb_strlen($column,'UTF-8') > 16 || mb_strlen($column,'UTF-8') <8){
                    $ret = array(
                        'class' => 'error-length'
                    );
                }elseif(!$this->passwordValidate($column)){
                    $ret = array(
                        'class' => 'error-pass'
                    );
                }
                break;
            // field メールアドレス
            case STUDENT_EMAIL:
                if($column === ''){
                     $ret = array(
                        'class' => 'error-empty'
                    );
                }elseif(mb_strlen($column,'UTF-8') > 256){
                     $ret = array(
                        'class' => 'error-length'
                    );
                }elseif(!$this->isValidEmail($column)){
                    $ret = array(
                        'class' => 'error-email-name'
                    );
                }elseif(!$this->NGCharCheck($column)){
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                }
                break;
            // field Skype名
            case SKYPE_NAME:
                if($column === ''){
                     break;
                }elseif(!$this->checkSkype($column)){
                    $ret = array(
                        'class' => 'error-skype-name'
                    );
                }
                break;
            // 自宅電話番号
            case STUDENT_TEL:
                if($column == "") {
                    break;
                }
                elseif(mb_strlen($column,'UTF-8') > 20){
                    $ret = array(
                        'class' => 'error-length'
                    );
                } elseif (!$this->spaceCheck($column)){
                    $ret = array(
                        'class' => 'error-space'
                    );
                } elseif(!$this->NGCharCheck($column)){
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                }elseif ($column != "" && !$this->checkPhoneNumber($column)) {
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                }
                break;
            //郵便番号
            case POSTCODE:
                if($column == "") {
                    break;
                }
                if (mb_strlen($column,'UTF-8') > 10){
                    $ret = array(
                        'class' => 'error-length'
                    );
                } elseif (!$this->spaceCheck($column)){
                    $ret = array(
                        'class' => 'error-space'
                    );
                } elseif(!$this->NGCharCheck($column)){
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                }elseif (!$this->checkPostCode($column)) {
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                }
                break;
            //都道府県
            case PREFECTURE:
                if($column == "") {
                    break;
                }elseif (!$this->spaceCheck($column)){
                    $ret = array(
                        'class' => 'error-space'
                    );
                }elseif(!$this->NGCharCheck($column)){
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                }else{
                    if (!$this->checkPrefecture($column)) {
                        $ret = array(
                            'class' => 'error-prefecture'
                        );
                    }
                }
                break;
            // [住所1] [住所2] [住所3] [住所4]
            case ADDRESS1 :
                if ($column == "") {
                    break;
                }
                elseif(mb_strlen($column,'UTF-8') > 50){
                    $ret = array(
                        'class' => 'error-length'
                    );
                }elseif(!$this->checkHasNotHankakuKatakana($column)){
                    $ret = array(
                        'class' => 'error-hankaku-kana'
                    );
                } elseif(!$this->NGCharCheck($column)){
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                }
                break;
            case ADDRESS2 :
            case ADDRESS3 :
            case ADDRESS4 :
                if ($column == "") {
                    break;
                }

                if(mb_strlen($column,'UTF-8') > 50){
                    $ret = array(
                        'class' => 'error-length'
                    );
                }elseif(!$this->checkHasNotHankakuKatakana($column)){
                    $ret = array(
                        'class' => 'error-hankaku-kana'
                    );
                } elseif(!$this->NGCharCheck($column)){
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                }
                break;
            // 部署名
            case DEPARTMENT_NAME :
                if ($column == "") {
                    break;
                }

                if(mb_strlen($column,'UTF-8') > 30){
                    $ret = array(
                        'class' => 'error-length'
                    );
                }elseif(!$this->checkHasNotHankakuKatakana($column)){
                    $ret = array(
                        'class' => 'error-hankaku-kana'
                    );
                } elseif(!$this->NGCharCheck($column)){
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                }
                break;
            // 社員番号
            case EMPLOYEE_NUMBER :
                if ($column == "") {
                    break;
                }

                if(mb_strlen($column,'UTF-8') > 10){
                    $ret = array(
                        'class' => 'error-length'
                    );
                } elseif(!$this->checkHankakuNumberOnly($column)){
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                } elseif(!$this->NGCharCheck($column)){
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                }
                break;
            //所属番号
            case DEPARTMENT_NUMBER :
                if ($column == "") {
                    break;
                }

                if(mb_strlen($column,'UTF-8') > 10){
                    $ret = array(
                        'class' => 'error-length'
                    );
                } elseif(!$this->checkHankakuNumberOnly($column)){
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                } elseif(!$this->NGCharCheck($column)){
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                }
                break;
            //企業ID
            case PROJECT_CODE :
                if($column === ''){
                    $ret = array(
                        'class' => 'error-empty'
                    );
                } elseif (!$this->NGCharCheck($column)) {
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                } elseif (!$this->spaceCheck($column)){
                    $ret = array(
                        'class' => 'error-space'
                    );
                } elseif(!$this->checkHankakuNumberOnly($column)){
                    $ret = array(
                        'class' => 'error-project'
                    );
                }
                break;
            // field コースコード（EduCross）
            case COURSE_ID:
                if($column === '') {
                    $ret = array(
                        'class' => 'error-empty'
                    );
                }
                elseif($column <= 1){
                    $ret = array(
                        'class' => 'error-course-id'
                    );
                }elseif(!$this->checkHankakuNumberOnly($column)){
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                }
                break;
            // 開講希望月
            case COURSE_BEGIN_MONTH:
                if($courseId === '' || $courseId === '0'){
                    break;
                }
                if($column === ''){
                    $ret = array(
                        'class' => 'error-empty'
                    );
                } elseif(!$this->checkCourseBeginMonth($column)) {
                    $ret = array(
                        'class' => 'error-course-begin-month'
                    );
                }
                break;
            // field 受注日
            case ORDER_DATE:
                if($courseId === '' || $courseId === '0'){
                    break;
                }

                if($column === ''){
                     $ret = array(
                        'class' => 'error-empty'
                    );
                }elseif(mb_strlen($column,'UTF-8') !==  8){
                      $ret = array(
                        'class' => 'error-length'
                    );
                }elseif(!$this->validateDate($column)){
                    $ret = array(
                        'class' => 'error-date'
                    );
                }
                break;
            // field 基準日
            case START_DATE:
                if($courseId === '' || $courseId === '0'){
                    break;
                }

                if($column === ''){
                    $ret = array(
                        'class' => 'error-empty'
                    );
                }elseif(mb_strlen($column,'UTF-8') !==  8){
                     $ret = array(
                        'class' => 'error-length'
                    );
                }elseif(!$this->validateDate($column)){
                    $ret = array(
                        'class' => 'error-date'
                    );
                }
                break;
            // field 開講開始日
            case BEGIN_DATE:
                if($courseId === '' || $courseId === '0'){
                    break;
                }

                if($column === ''){
                    $ret = array(
                        'class' => 'error-empty'
                    );
                }elseif(mb_strlen($column,'UTF-8') !==  8){
                     $ret = array(
                        'class' => 'error-length'
                    );
                }elseif(!$this->validateDate($column)){
                    $ret = array(
                        'class' => 'error-date'
                    );
                }
                break;

            // field 有効期限
            case EXPIRE_DATE:
                if($column === '' || $courseId === '0'){
                    break;
                }elseif (!$this->spaceCheck($column)) {
                    $ret = array(
                        'class' => 'error-space'
                    );
                }elseif(mb_strlen($column) !==  8){
                     $ret = array(
                        'class' => 'error-length'
                    );
                }elseif(!$this->validateDate($column)){
                    $ret = array(
                        'class' => 'error-date'
                    );
                }
                break;

            // field 共通管理番号
            case MANAGEMENT_NUMBER:
                if($column === ''){
                    break;
                }elseif(strlen($column) >10){
                    $ret = array(
                        'class' => 'error-length'
                    );
                }elseif(!$this->checkManagementNumber($column)){
                    $ret = array(
                        'class' => 'error-alphanumeric-symbol'
                    );
                }
                break;

            // 請求区分
            case CORPORATION_FLAG :
                // if($courseId === '' || $courseId === '0'){
                //     break;
                // }
                if($column === ''){
                    $ret = array(
                        'class' => 'error-empty'
                    );
                }
                elseif($column !== "1" && $column !== "0"){
                    $ret = array(
                        'class' => 'error-corporation'
                    );
                }
                break;
            // field 生徒番号
            case STUDENT_ID:
                if($column !== ''){
                    if (!$this->checkHankakuNumberOnly($column)) {
                        $ret = array(
                        'class' => 'error-special-text'
                    );
                    }
                }
                break;
            // メール送信フラッグ
            case SEND_MAIL_FLAG :
                if ($column === "") {
                    $ret = array(
                        'class' => 'error-empty'
                    );
                } elseif ($column != 0 && $column != 1) {
                    $ret = array(
                        'class' => 'error-common'
                    );
                }
                break;
            case LANG_TYPE :
                $column = trim($column);
                if($column === '') {
                    break;
                } elseif(!$this->checkLangtype($column)) {
                    $ret = array(
                        'class' => 'error-lang-type'
                    );
                }
                break;
            case TIME_ZONE :
                $column = trim($column);
                if($column === '')  {
                    break;
                } elseif (!$this->checkTimezone($column)) {
                    $ret = array(
                        'class' => 'error-time-zone'
                    );
                }
                break;
            default:
                break;
        }

        return $ret;
    }

    public function _check_db($value) 
    {
        $isLmsUser  = 1;
        $checkResult = DB::select('CALL lms_sp_csv_check_db_setcourse(?,?,?,?,?,?)', 
            array(
                $value[STUDENT_ID],
                $value[STUDENT_EMAIL],
                $value[PROJECT_CODE],
                (int) $value[COURSE_ID],
                $value[CORPORATION_FLAG],
                (int) $isLmsUser
            )
        );

        if (empty($checkResult)) {
            return array();
        }
        return $checkResult[0];
    }

    public function importSetCourseCsv(Request $request) 
    {
        set_time_limit(1000);

        if(!$request->isMethod('POST')){
            return response()->json([
                'status' => 'NG',
            ], StatusCode::BAD_REQUEST);  
        }
        
        $csv = Session::get('LMS_SETCOURSE_IMPORT');
        $lengthCsv = count($csv)-1;
        if (empty($csv)) {
            $return = array(
                "status" => 0,
                "result" => "エラー"
            );
            echo json_encode($return);
            return;
        }
        $data = $this->request->data;
        $index = $data['index'];
        if ($index == $lengthCsv) {
            Session::forget('LMS_SETCOURSE_IMPORT');
        }
        $courseList = $data['course_list'];

        if (empty($csv[$index])){
            $return = array(
                "status" => 0,
                "result" => "エラー"
            );
            echo json_encode($return);
            return;
        }
        if (empty($csv[$index + 1])) {
            Session::forget('LMS_SETCOURSE_IMPORT');
        }
        $rowData = $csv[$index];

        DB::beginTransaction();

        try {
           $saveInfoRet = $this->_saveInfo($rowData);
            // todo process error

            if ($saveInfoRet === false) {
                $return = array(
                    "status" => 0,
                    "result" => "エラー"
                );
                echo json_encode($return);
                return;
            }

            DB::commit();
        }catch (\PDOException $e) {
            DB::rollBack();
            $return = array(
                "status" => 0,
                "result" => "エラー"
            );
            echo json_encode($return);
            return;
        }
        if (!empty($saveInfoRet[0]['error'])) {
            if ($saveInfoRet[0]['error'] == 3) {
                $return = array(
                    "status" => 0,
                    "result" => "生徒追加―エラー"
                );
            }elseif ($saveInfoRet[0]['error'] == 4) {
                $return = array(
                    "status" => 0,
                    "result" => "コース追加―エラー"
                );
            }elseif ($saveInfoRet[0]['error'] == 5) {
                $return = array(
                    "status" => 0,
                    "result" => "購入不可エラー"
                );
            }else {
                $return = array(
                    "status" => 0,
                    "result" => "エラー"
                );
            }
            echo json_encode($return);
            return;
        }

        DB::beginTransaction();

        try {
           foreach ($courseList as  &$childCourse) {
                $saveCourseRet = $this->_saveCourse($rowData, $childCourse, $saveInfoRet[0]);
                if ($saveCourseRet === false || !empty($saveCourseRet[0]['error'])) {
                    $return = array(
                        "status" => 0,
                        "result" => "コース追加―エラー"
                    );
                    echo json_encode($return);
                    return;
                }

                $childCourse['save_ret'] = $saveCourseRet[0];
            }

            DB::commit();
        }catch (\PDOException $e) {
            DB::rollBack();
            $return = array(
                "status" => 0,
                "result" => "コース追加―エラー"
            );
            echo json_encode($return);
            return;
        }

        if ($saveInfoRet[0]['update_flg'] == 1) {
            $return = array(
                "status" => 200,
                "result" => "コース追加―完了"
            );
            $mailType = 43;
        }else {
            $return = array(
                "status" => 200,
                "result" => "生徒追加・コース追加―完了"
            );
            $mailType = 42;
        }

        $this->_sendMailWhenSuccess($rowData, $saveInfoRet, $courseList, $mailType);

        echo json_encode($return);
        return;
    }

    public function _sendMailWhenSuccess($rowData, $saveInfoRet, $courseList, $mailType)
    {
        if ($rowData[SEND_MAIL_FLAG] != 1) {
            return;
        }
        $mailTemp = $this->getMailTemp(1);
        // $mailRet = $mailTemp[$mailType];
        $dataInfoSendMail = $this->getDataForSendMail($mailType,$saveInfoRet[0]['lang_type'],$mailTemp);
        $mailBody = $dataInfoSendMail['mail_body'];
        $mailDomain = $dataInfoSendMail['mail_domain'];
        $mailSubject = $dataInfoSendMail['mail_subject'];
        $mailFromName = $dataInfoSendMail['mail_from_name'];

        preg_match('/'.COURSE_LIST_START.'(.*?)'.COURSE_LIST_END.'/s', $mailBody, $output);
        $childCoursePattern = !empty($output[1]) ? $output[1] : "";

        $childCourseContent = '';
        $setCoursePrice = 0;

        foreach ($courseList as $childCourse) {
            $childCourseRet = $childCourse['save_ret'];
            $setCoursePrice += $childCourseRet['amount'];
            $content =str_replace('#COURSE_NAME#', $childCourseRet['course_name'] , $childCoursePattern);
            $content =str_replace('#COURSE_ID#', $childCourseRet['course_id'] , $content);
            $content =str_replace('#POINT_SUBSCRIPTION_HISTORY_ID#', $childCourseRet['point_subscription_history_id'] , $content);
            $content =str_replace('#ORDER_DATE#', $childCourse['ORDER_DATE'] , $content);
            $content =str_replace('#START_DATE#', date('Y-m-d', strtotime($childCourse['START_DATE'])) , $content);
            $content =str_replace('#EXPIRE_DATE#', date('Y-m-d', strtotime($childCourseRet['point_expire_date'])) , $content);
            $childCourseContent .=  $content;
        }
        $mailBody = preg_replace('/'.COURSE_LIST_START.'(.*?)'.COURSE_LIST_END.'/s',$childCourseContent,$mailBody);

        $tax = round(COURSE_TAX *  $setCoursePrice);
        $total = $setCoursePrice + $tax;

        $mailBody = str_replace('#STUDENT_NAME#', $saveInfoRet[0]['student_name'], $mailBody);
        $mailBody = str_replace('#STUDENT_PASSWORD#', $rowData[PASSWORD], $mailBody);
        $mailBody = str_replace('#ORDER_ID#', $saveInfoRet[0]['order_id'], $mailBody);
        $mailBody = str_replace('#SET_COURSE_ID#', $rowData[COURSE_ID], $mailBody);
        $mailBody = str_replace('#SET_COURSE_NAME#', $saveInfoRet[0]['set_course_name'], $mailBody);
        $mailBody = str_replace('#SET_COURSE_PRICE#', number_format($setCoursePrice), $mailBody);
        $mailBody = str_replace('#SET_COURSE_TAX#', number_format($tax), $mailBody);
        $mailBody = str_replace('#SET_COURSE_TOTAL#', number_format($total), $mailBody);
        $mailBody = str_replace('#ORDER_DATE#', date('Y-m-d', strtotime($rowData[ORDER_DATE])), $mailBody);
        $mailBody = str_replace('#STUDENT_ADDRESS#',  $saveInfoRet[0]["postcode"]. ' ' .
                                                        $saveInfoRet[0]["prefecture_name"].
                                                        $saveInfoRet[0]["student_address"].
                                                        $saveInfoRet[0]["student_address1"].
                                                        $saveInfoRet[0]["student_address2"].
                                                        $saveInfoRet[0]["student_address3"], $mailBody);
        // $mailBody = str_replace("\r", "\r\n", $mailBody);

        return $this->Common->sendMail($rowData[STUDENT_EMAIL], $mailDomain, $mailFromName, $mailSubject, $mailBody);
    }

    public function _saveInfo($value) 
    {
        if ($value[LANG_TYPE] == 'en') {
            $st_langtype = 2;
        } else if ($value[LANG_TYPE] == 'vn') {
            $st_langtype = 3;
        } else {
            $st_langtype = 1;
        }

        if ($value[TIME_ZONE] === "") {
            $value[TIME_ZONE] = 1;
        }

        $saveInfoRet = DB::select('CALL lms_sp_import_student_csv_setcourse(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', 
            array(
                $value[STUDENT_FIRST_NAME],
                $value[STUDENT_LAST_NAME],
                $value[STUDENT_FIRSTNAME_KANA],
                $value[STUDENT_LASTNAME_KANA],
                $value[STUDENT_NICKNAME],
                $value[PASSWORD],
                $value[STUDENT_EMAIL],
                $value[SKYPE_NAME],
                $value[STUDENT_TEL],
                $value[POSTCODE],
                (int) $value[PREFECTURE],
                $value[ADDRESS1],
                $value[ADDRESS2],
                $value[ADDRESS3],
                $value[ADDRESS4],
                $value[DEPARTMENT_NAME],
                $value[EMPLOYEE_NUMBER],
                $value[DEPARTMENT_NUMBER],
                $value[PROJECT_CODE],
                (int) $value[COURSE_ID],
                $value[ORDER_DATE],
                (int) $value[STUDENT_ID],
                (int) $st_langtype,
                (int) $value[TIME_ZONE]
            )
        );

        return $saveInfoRet;
    }

    public function _saveCourse($rowData, $childCourse, $saveInfoRet) 
    {
        $saveCourseRet = DB::select('CALL sp_insert_point_subscription_lms_csv_setcourse(?,?,?,?,?,?,?,?,?,?,?,?)', 
            array(
                (int) $saveInfoRet['project_id'],
                (int) $saveInfoRet['student_id'],
                (int) $childCourse['COURSE_ID'],
                (int) $rowData[COURSE_ID],
                $saveInfoRet['order_id'],
                $childCourse['ORDER_DATE'],
                $childCourse['START_DATE'],
                $childCourse['BEGIN_DATE'],
                $childCourse['EXPIRE_DATE'],
                $childCourse['COURSE_BEGIN_MONTH'],
                $childCourse['MANAGEMENT_NUMBER'],
                (int) $rowData[CORPORATION_FLAG]
            )
        );

        return $saveCourseRet;
    }

    public function getMailTemp($isLmsUser) 
    {
        $this->loadModel('SendRemindMailPattern');
        $mailTemp = $this->SendRemindMailPattern->callProcedure('sp_remind_mail_csv_setcourse', array(
            '_is_lms_user' => $isLmsUser,
            '_brand_id' => BRAND_ID
        ));
        $mailTemp = Hash::combine($mailTemp, '{n}.mail_type', '{n}');
        return $mailTemp;
    }

    public $canEmptyWhenStudentExist = array(
        STUDENT_FIRST_NAME,
        STUDENT_LAST_NAME,
        STUDENT_FIRSTNAME_KANA,
        STUDENT_LASTNAME_KANA,
        STUDENT_NICKNAME,
        PASSWORD,
        SKYPE_NAME,
        LANG_TYPE,
        TIME_ZONE,
        STUDENT_TEL,
        POSTCODE,
        PREFECTURE,
        ADDRESS1,
        DEPARTMENT_NAME,
        EMPLOYEE_NUMBER,
        DEPARTMENT_NUMBER
    );
}
