<?php
namespace App\Components;

use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class CommonComponent
{
    public static function uploadFile($folder, $file, $fileName)
    {
        try {
            $azure = Storage::disk('public');
            $path = $azure->putFileAs($folder, $file, $fileName);
            return Storage::disk('public')->url($path);
        } catch (Exception $exception) {
            return false;
        }
        return true;
    }
    public static function uploadFileName($extension = '')
    {
        return sha1(time() . rand(0, 9999999)) . "." . $extension;
    }
    public static function deleteFile($folder, $nameFile)
    {
        try {
            return Storage::disk('public')->delete($folder.'/'.$nameFile);
        } catch (Exception $exception) {
            return false;
        }
        return true;
    }

    public static function checkData($column, $type){

        $ret = array();

        switch ($type) {
            // field 氏名性
            case STUDENT_FIRST_NAME:
                //echo $column;
                if ($column === ''){
                    $ret = array(
                        'class' => 'error-empty'
                    );
                }elseif( mb_strlen($column) > 100){
                    $ret = array(
                        'class' => 'error-length'
                    );
                }elseif(!(self::NGCharCheck($column))){
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                }

                break;

            // field 氏名名
            case STUDENT_LAST_NAME:
                //echo $column;
                if ($column === ''){
                    $ret = array(
                        'class' => 'error-empty'
                    );
                }elseif( mb_strlen($column) > 100){
                    $ret = array(
                        'class' => 'error-length'
                    );
                }elseif(!(self::NGCharCheck($column))){
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                }

                break;

            // field ニックネーム
            case STUDENT_NICKNAME:
                if($column === ''){
                    break;
                }elseif(!(self::checkNickname($column))){
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                }elseif(mb_strlen($column) > 16 ){
                    $ret = array(
                        'class' => 'error-length'
                    );
                }
                break;
            // field パスワード
            case PASSWORD:
                if($column === ''){
                    $ret = array(
                        'class' => 'error-empty'
                    );
                }elseif(mb_strlen($column) > 16 || mb_strlen($column) <8){
                    $ret = array(
                        'class' => 'error-length'
                    );
                }elseif(!(self::passwordValidate($column))){
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
                }elseif(mb_strlen($column) > 255){
                    $ret = array(
                        'class' => 'error-length'
                    );
                }elseif(!(self::isValidEmail($column))){
                    $ret = array(
                        'class' => 'error-email-name'
                    );
                }
                break;
            // field Skype名
            case SKYPE_NAME:
                if($column === ''){
                    break;
                }elseif(mb_strlen($column) > 255){
                    $ret = array(
                        'class' => 'error-length'
                    );
                }elseif(!(self::checkSkype($column))){
                    $ret = array(
                        'class' => 'error-skype-name'
                    );
                }
                break;
            // field デフォルト言語
            case LANGDEFAULT:
                $column = trim($column);
                if($column === ''){
                    break;
                } elseif(!self::langTypeValidate($column)) {
                    $ret = array(
                        'class' => 'error-lang-type'
                    );
                }
                break;
            // field デフォルト言語
            case TIMEZONE:
                $column = trim($column);
                if($column === ''){
                    break;
                } elseif(!self::timezoneValidate($column)) {
                    $ret = array(
                        'class' => 'error-time-zone'
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
                }elseif(!(self::checkManagementNumber($column))){
                    $ret = array(
                        'class' => 'error-alphanumeric-symbol'
                    );
                }
                break;
            // field コースコード（EduCross）
            case COURSE_ID:
                if($column === ''){
                    $ret = array(
                        'class' => 'error-empty'
                    );
                }
                break;
            // field 得意先コード
            case CUSTOMER_CODE:
                if($column && !(self::NGCharCheck($column))){
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                }elseif(mb_strlen($column) > 255){
                    $ret = array(
                        'class' => 'error-length'
                    );
                }
                break;
            // field 法人コード
            case CORPORATION_CODE:
                if ($column === '') {
                    break;
                }
                if($column && !(self::NGCharCheck($column))){
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                }elseif(mb_strlen($column) > 255){
                    $ret = array(
                        'class' => 'error-length'
                    );
                }elseif (!(self::checkNumber($column))) {
                    $ret = array(
                        'class' => 'error-hankaku'
                    );
                }
                break;
            // field 法人名
            case COMPANY_NAME:
                if($column && !(self::NGCharCheck($column))){
                    $ret = array(
                        'class' => 'error-special-text'
                    );
                }elseif(mb_strlen($column) > 255){
                    $ret = array(
                        'class' => 'error-length'
                    );
                }
                break;
            // field 受注日
            case ORDER_DATE:
                if($column === ''){
                    $ret = array(
                        'class' => 'error-empty'
                    );
                }elseif(mb_strlen($column) !==  8){
                    $ret = array(
                        'class' => 'error-length'
                    );
                }elseif(!(self::validateDate($column))){
                    $ret = array(
                        'class' => 'error-date'
                    );
                }
                break;
            // field 基準日
            case START_DATE:
                if($column === ''){
                    $ret = array(
                        'class' => 'error-empty'
                    );
                }elseif(mb_strlen($column) !==  8){
                    $ret = array(
                        'class' => 'error-length'
                    );
                }elseif(!(self::validateDate($column))){
                    $ret = array(
                        'class' => 'error-date'
                    );
                }
                break;
            // field 有効期限
            case EXPIRE_DATE:
                if($column === ''){
                    break;
                }elseif (!(self::spaceCheck($column))) {
                    $ret = array(
                        'class' => 'error-space'
                    );
                }elseif(mb_strlen($column) !==  8){
                    $ret = array(
                        'class' => 'error-length'
                    );
                }elseif(!(self::validateDate($column))){
                    $ret = array(
                        'class' => 'error-date'
                    );
                }
                break;

            // field 生徒番号
            case STUDENT_ID:
                break;
            // メール送信フラッグ
            case SEND_MAIL_FLAG:
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
            default:
                break;
        }

        return $ret;
    }


    private static function NGCharCheck($data)
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

        if (strlen($data) !== strlen(mb_convert_encoding(mb_convert_encoding($data, 'SJIS', 'UTF-8'), 'UTF-8', 'SJIS'))) {
            return false;
        }
        return true;
    }

    private  static function checkNickname($check)
    {
        return preg_match('/^[a-z][a-z0-9]{1,16}$/i', $check);
    }

    private static function passwordValidate($check)
    {
        $length = mb_strlen($check);
        if ($length < 8 || $length > 16) {
            return false;
        }
        if (!preg_match('/^[A-Za-z0-9]+$/i', $check)) {
            return false;
        }

        if (!preg_match('/[A-Za-z]/', $check)) {
            return false;
        }

        if (!preg_match('/[0-9]/', $check)) {
            return false;
        }
        return true;
    }

    private static function isValidEmail($email)
    {
        //return filter_var($email, FILTER_VALIDATE_EMAIL);
        $a = filter_var($email, FILTER_VALIDATE_EMAIL);
        $b = preg_match('/@.+\./', $email);
        $vvvv = 0;
        return filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match('/@.+\./', $email);
    }

    private static function checkSkype($check){
        return preg_match('/^[a-zA-Z][a-zA-Z0-9_\-\.\,\:]{5,31}$/i', $check);
    }

    private static function langTypeValidate($data) {
        $langType = ["jp", "en", "vn"];
        if(in_array($data, $langType)) {
            return true;
        }
        return false;
    }

    private static function timezoneValidate($data) {
        if(!is_numeric($data) || ($data >75 || $data < 1)) {
            return false;
        }
        return true;
    }

    private static function checkManagementNumber($str) {
        return preg_match('/^[!-~]+$/', $str);
    }

    private static function checkNumber($check)
    {
        return preg_match('/^[0-9０-９-]+$/', $check);
    }

    private static function validateDate($date)
    {
        $d = DateTime::createFromFormat('Ymd', $date);
        return $d && $d->format('Ymd') == $date;
        // return true;
    }

    private static function spaceCheck($data)
    {
        if (preg_match("/[ 　]+/u", $data)) {
            return false;
        }

        return true;
    }
}
