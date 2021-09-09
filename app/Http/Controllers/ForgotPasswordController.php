<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\AdminUser;
use Carbon\Carbon;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPassword;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends BaseController
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
    public function store(ForgotPasswordRequest $request)
    {
        $account = AdminUser::where('admin_user_email', $request->email)->first();
        if (!$account) {
            $this->setFlash(__('メールでユーザーを見つけることができません'), 'error');
            return redirect(route('forgot_password.create'));
        }
        $account->reset_password_token = md5($request->email . random_bytes(25) . Carbon::now());
        $account->reset_password_token_exprire = Carbon::now()->addMinutes(30);
        if (!$account->save()) {
            $this->setFlash(__('メールが一致しません。'), 'error');
        }
        $mailContents = [
            'data' => [
                'name' => $account->user_account_name,
                'link' => route('reset-password', $account->reset_password_token)
            ]
        ];
        Mail::to($account->admin_user_email)->send(new ForgotPassword($mailContents));
        return redirect(route('forgot_password.index'));
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

    public function reset($token)
    {
        $account = AdminUser::where([
            ['reset_password_token', $token],
            ['reset_password_token_exprire', '>=', Carbon::now()]
        ])->first();

        if ($account) {
            return view('forgotPassword.reset', [
                'token' => $token
            ]);
        }

        $this->setFlash(__('期限切れのリンク。'), 'error');
        return view('forgotPassword.reset', [
            'title' => 'パスワードをお忘れですか',
        ]);
    }

    public function changePassword(ResetPasswordRequest $request)
    {
        $account = AdminUser::where([
            ['reset_password_token', $request->reset_password_token],
            ['reset_password_token_exprire', '>=', Carbon::now()]
        ])->first();
        if ($account) {
            $account->user_account_password = Hash::make($request->password);
            $account->reset_password_token = null;
            if ($account->save()) {
                $this->setFlash(__('パスワード変更が完了しました。'));
                return redirect('login');
            }
        }

        $this->setFlash(__('期限切れのリンク。'), 'error');
        return view('forgotPassword.index', [
            'title' => 'パスワードをお忘れですか',
        ]);
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
