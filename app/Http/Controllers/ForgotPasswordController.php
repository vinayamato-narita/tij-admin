<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\AdminUser;
use App\Models\SendRemindMailPattern;
use Carbon\Carbon;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPassword;
use Illuminate\Support\Facades\Hash;
use App\Enums\StatusCode;
use App\Enums\MailType;
use Log;

class ForgotPasswordController extends BaseController
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function forgotPassword()
    {
        return view('forgotPassword.create', [
            'title' => TITLE_FORGOT_PASSWORD,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeForgotPassword(ForgotPasswordRequest $request)
    {
        if($request->isMethod('POST')) {
            $account = AdminUser::where('admin_email', $request->admin_email)->first();
            if (!$account) {
                return response()->json([
                    'status' => 'NG',
                    'message' => 'メールアドレスが登録されていません'
                ], StatusCode::NOT_FOUND);
            }
            $account->remember_token = md5($request->admin_email . random_bytes(25) . Carbon::now());
            $account->remember_token_expires_at = Carbon::now()->addMinutes(30);
            if (!$account->save()) {
                return response()->json([
                    'status' => 'NG',
                    'message' => 'メールが一致しません。'
                ], StatusCode::NOT_FOUND);
            }
            $mailPattern = SendRemindMailPattern::where('send_remind_mail_timing_type', MailType::FORGOTPASSWORD)->first();
            if (!$mailPattern) {
                return response()->json([
                    'status' => 'NG',
                    'message' => 'メールの情報を確認できません。'
                ], StatusCode::NOT_FOUND);
            }
            $AccessURL = route('resetPassword', $account->remember_token);

            $mailSubject = $mailPattern->mail_subject;
            $mailBody = $mailPattern->mail_body;

            $mailBody = str_replace("#ADMIN_NAME#", $account->admin_name, $mailBody);
            $mailBody = str_replace("#CHANGEPASS_URL#", $AccessURL, $mailBody);
            $mailBody = str_replace("#EntryURL#", $AccessURL, $mailBody);
            
            Mail::raw($mailBody, function ($message) use ($account, $mailSubject) {
                $message->to($account->admin_email)
                    ->subject($mailSubject);
            });

            return response()->json([
                    'status' => 'OK',
                ], StatusCode::OK);
        }
        
        return response()->json([
            'status' => 'NG',
        ], StatusCode::BAD_REQUEST);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function forgotPasswordSuccess()
    {
        return view('forgotPassword.success', [
            'title' => TITLE_FORGOT_PASSWORD,
        ]);
    }

    public function reset($token)
    {
        $account = AdminUser::where([
            ['remember_token', $token],
            ['remember_token_expires_at', '>=', Carbon::now()]
        ])->first();

        $dataToken = [];
        if ($account) {
            $dataToken['_token'] = csrf_token();
            $dataToken['remember_token'] = $token;

            return view('forgotPassword.reset', [
                'dataToken' => $dataToken,
                'title' => 'パスワード更新',
            ]);
        }

        $this->setFlash(__('期限切れのリンク。'), 'error');
        return view('forgotPassword.reset', [
            'dataToken' => $dataToken,
            'title' => 'パスワード更新',
        ]);
    }

    public function changePassword(ResetPasswordRequest $request)
    {
        if($request->isMethod('POST')) {
            $account = AdminUser::where([
                ['remember_token', $request->remember_token],
                ['remember_token_expires_at', '>=', Carbon::now()]
            ])->first();
            
            if ($account) {
                $account->password = Hash::make($request->password);
                $account->remember_token = null;

                $account->save();
                return response()->json([
                        'status' => 'OK',
                    ], StatusCode::OK);
            }
        }
        $this->setFlash(__('期限切れのリンク。'), 'error');
        return response()->json([
            'status' => 'NG',
        ], StatusCode::BAD_REQUEST);
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
}
