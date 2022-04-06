<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\AdminUserRight;
use Log;

class BaseController extends Controller
{
    public function setFlash($message, $mode = 'success', $urlRedirect = '')
    {
        session()->forget('Message.flash');
        session()->push('Message.flash', [
            'message' => $message,
            'mode' => $mode,
            'urlRedirect' => $urlRedirect,
        ]);
    }
    public static function newListLimit($query)
    {
        $newSizeLimit = PAGE_SIZE_DEFAULT;
        $arrPageSize = PAGE_SIZE_LIMIT;
        if (isset($query['limit'])) {
            $newSizeLimit = (($query['limit'] === '') || !in_array($query['limit'], $arrPageSize)) ? $newSizeLimit : $query['limit'];
        }
        if (((isset($query['limit']))) && (!empty($query->query('limit')))){
            $newSizeLimit = (!in_array($query->query('limit'), $arrPageSize)) ? $newSizeLimit : $query->query('limit');
        }
        return $newSizeLimit;
    }

    /**
     * [escapeLikeSentence description]
     * @param  [type]  $str    :search conditions
     * @param  boolean $before : add % before
     * @param  boolean $after  : add % after
     * @return [type]          [description]
     */
    public function escapeLikeSentence($column, $str, $before = true, $after = true)
    {
        $result = str_replace('\\', '[\]', $this->mb_trim($str)); // \ -> \\
        $result = str_replace('%', '\%', $result); // % -> \%
        $result = str_replace('_', '\_', $result); // _ -> \_
        return [[$column, 'LIKE', (($before) ? '%' : '') . $result . (($after) ? '%' : '')]];
    }

    public function checkPaginatorList($query)
    {
        if ($query->currentPage() > $query->lastPage()) {
            return true;
        }
        return false;
    }

    public function mb_trim($string)
    {
        $whitespace = '[\s\0\x0b\p{Zs}\p{Zl}\p{Zp}]';
        $ret = preg_replace(sprintf('/(^%s+|%s+$)/u', $whitespace, $whitespace), '', $string);
        return $ret;
    }

    /**
	* マルチバイト対応のtrim
	*
	* 文字列先頭および最後の空白文字を取り除く
	*
	* @param  string  空白文字を取り除く文字列
	* @return  string
	*
	*/

    public function checkRfidCode($rfidCode){
        return preg_match('/^[a-zA-Z0-9][a-zA-Z0-9]*$/i', $rfidCode);
    }

    public function checkReturnCsvContent($column) {
    	$ret = 0;
        if ($column == '') {
            $ret = 1; // Blank OR NULL
        } elseif (!$this->checkRfidCode($column)) {
            $ret = 2; // Error formart
        }
        return $ret;
    }

    public function logInfo($request, $message = '') {
        Log::channel('access_log')->info([
            'url' => url()->full(),
            'method' => $request->getMethod(),
            'data' => $request->all(),
            'message' => $message,
        ]);
    }
    public function logError($request, $message = '') {
        Log::channel('access_log')->error([
            'url' => url()->full(),
            'method' => $request->getMethod(),
            'data' => $request->all(),
            'message' => $message,
        ]);
    }
    public function logWarning($request, $message = '') {
        Log::channel('access_log')->warning([
            'url' => url()->full(),
            'method' => $request->getMethod(),
            'data' => $request->all(),
            'message' => $message,
        ]);
    }

    public function adminCanEdit($view) 
    {
        $adminUser = Auth::user();
        $adminCanEdit = AdminUserRight::select(CANEDIT)->where('admin_user_id', $adminUser->admin_user_id)
            ->where('admin_rights_id', $view)
            ->first();

        $adminCanEdit = $adminCanEdit->can_edit ?? 0;

        return $adminCanEdit;
    }

    public function convertShijis($text) {
        return mb_convert_encoding($text, "SJIS", "UTF-8");
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

    /**
     * get client of IP
     * @return [type] [description]
     */
    static function getClientIp() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }

}
