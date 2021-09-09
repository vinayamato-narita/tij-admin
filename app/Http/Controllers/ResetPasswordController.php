<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Carbon\Carbon;
use App\Enums\StatusCode;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPassword;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends BaseController
{
   /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('forgotPassword.success', [
            'title' => TITLE_FORGOT_PASSWORD,
        ]);
    }
    
    public function reset($token)
    {
        $account = User::where([
            ['remember_token', $token],
            ['remember_token_expires_at', '>=', Carbon::now()]
        ])->first();

        if ($account) {
            return view('forgotPassword.reset', [
                'token' => $token
            ]);
        }

        $this->setFlash(__('期限切れのリンク。'), 'error');
        return view('forgotPassword.reset', [
            'title' => 'パスワード再設定',
        ]);
    }
    public function changePassword(ResetPasswordRequest $request)
    {
        $account = User::where([
            ['remember_token', $request->remember_token],
            ['remember_token_expires_at', '>=', Carbon::now()]
        ])->first();
        if ($account) {
            $account->password = Hash::make($request->password);
            $account->remember_token = null;
            if ($account->save()) {
                $this->setFlash(__('パスワード変更が完了しました。'));
                return redirect('login');
            }
        }
        $this->setFlash(__('期限切れのリンク。'), 'error');
        return view('forgotPassword.index', [
            'title' => 'パスワード再設定',
        ]);
    }

}
