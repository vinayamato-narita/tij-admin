<?php

namespace App\Http\Controllers;

use App\Models\AdminUser;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Log;

class LoginController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {

        if (Auth::check()) {
            return redirect()->route('dashboard.index');
        }
        
        return view('login.index', [
            'title' => TITLE_LOGIN,
            'request' => $request->all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function store(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt([
            'admin_user_email' => $credentials['email'],
            'password' => $credentials['password']
            ], $request->remember_me ?? false)) {
            try {
                $account = AdminUser::where('admin_user_id', Auth::user()->admin_user_id)->firstOrFail();
                $account->last_login_date = Carbon::now();
            }
            catch (Exception $exception){
                throw new Exception($exception);
            }
            if (!$account->save()) {
                Auth::logout();
                return redirect('/');
            }

            return redirect($request->url_redirect ? $request->url_redirect : route('dashboard.index'));
        }
        return view('login.index', [
            'title' => TITLE_LOGIN,
            'message' => MESSAGE_LOGIN_ERROR,
            'request' => $request->all()
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
