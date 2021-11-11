<?php
namespace App\Components;

use Illuminate\Support\Facades\Auth;
use App\Models\AdminUserRight;
use Illuminate\Support\Facades\Route;
use Log;

class AdminUserRightComponent
{
    public static function getList()
    {
        $user = Auth::user();
        $adminUserRight = AdminUserRight::where('admin_user_id', $user->admin_user_id)->get()->keyBy('admin_rights_id')->toArray();
        return $adminUserRight;
    }

    public static function checkAdminUserRight($arrKey, $adminUserRights)
    {
    	$cls = "hidden";
        if ($adminUserRights == null) {
        	return "hidden";
        }
        foreach($arrKey as $key) {
        	if (isset($adminUserRights[$key]) && $adminUserRights[$key][ISPERMITTED] == 1) {
        		$cls = "";
        		break;
        	}
        }
        return $cls;
    }

    public static function getActiveMenu($key) 
    {
    	$routeName = Route::currentRouteName();

    	if (isset(MENU[$key]) && in_array($routeName, MENU[$key])) {
    		return 'c-active';
    	}

    	return "";
    }
}
