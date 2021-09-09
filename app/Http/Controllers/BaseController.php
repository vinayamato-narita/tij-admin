<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
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
        if (isset($query['limit_page'])) {
            $newSizeLimit = (($query['limit_page'] === '') || !in_array($query['limit_page'], $arrPageSize)) ? $newSizeLimit : $query['limit_page'];
        }
        if (((isset($query['limit_page']))) && (!empty($query->query('limit_page')))){
            $newSizeLimit = (!in_array($query->query('limit_page'), $arrPageSize)) ? $newSizeLimit : $query->query('limit_page');
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
}
