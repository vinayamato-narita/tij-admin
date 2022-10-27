<?php

namespace App\Http\Controllers;

use App\Components\BreadcrumbComponent;
use App\Enums\StatusCode;
use App\Models\ZoomAccount;
use App\Services\ZoomClientService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ZoomAccountController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'zoom_account_list']
        ]);
        $pageLimit = $this->newListLimit($request);
        $queryBuilder = new ZoomAccount();

        if (isset($request['search_input'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('zoom_account_name', $request['search_input']));
            });
        }

        $zoomAccountList = $queryBuilder->sortable(['id' => 'asc'])->paginate($pageLimit);

        return view('zoomAccount.index', [
            'breadcrumbs' => $breadcrumbs,
            'request' => $request,
            'pageLimit' => $pageLimit,
            'zoomAccountList' => $zoomAccountList,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'zoom_account_list'],
            ['name' => 'zoom_account_add']
        ]);

        return view('zoomAccount.add', [
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, ZoomClientService $zoomClientService)
    {
        try {
            $response = $zoomClientService->checkZoomAccountViaAccessKey($request->token);
            if ($response->status() != StatusCode::OK) {
                return response()->json([
                    'status' => 'UNPROCESSABLE_ENTITY',
                    'message' => 'APIキーとAPI SECRETが正しくありません',
                ], StatusCode::UNPROCESSABLE_ENTITY);
            }
/*            $zoomUserId = $response->json('id');*/
            $zoomAccount = ZoomAccount::where([
                'api_key' =>  $request->api_key,
                'api_secret' => $request->api_secret,
                'zoom_email' => $request->zoom_email
            ])->first();
            if (!empty($zoomAccount)) {
                return response()->json([
                    'status' => 'UNPROCESSABLE_ENTITY',
                    'message' => 'Zoomアカウントが重複しているため、登録できません。',
                ], StatusCode::UNPROCESSABLE_ENTITY);
            }
            $users = $zoomClientService->getUserList($request->token);
            $user = array_filter($users, function ($e) use ($request) {
                return $e['email'] == $request->zoom_email;
            });
            if (!empty($user)) {
                $zoomAccount = new ZoomAccount();
                $zoomAccount->zoom_account_name = $request->zoom_account_name;
                $zoomAccount->api_key = $request->api_key;
                $zoomAccount->api_secret = $request->api_secret;
                $zoomAccount->zoom_user_id = reset($user)['id'];
                $zoomAccount->zoom_email = $request->zoom_email;
                $zoomAccount->save();
                return response()->json([
                    'status' => 'OK',
                ], StatusCode::OK);
            }
            return response()->json([
                'status' => 'UNPROCESSABLE_ENTITY',
                'message' => ' メールアドレスが見つかりません。',
            ], StatusCode::UNPROCESSABLE_ENTITY);

        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'INTERNAL_ERR',
            ], StatusCode::INTERNAL_ERR);
        }
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
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'zoom_account_list'],
            ['name' => 'zoom_account_edit', $id]
        ]);

        $zoomAccount = ZoomAccount::where('zoom_account_id', $id)->first();
        if (!$zoomAccount) return redirect()->route('zoomAccount.index');
        return view('zoomAccount.edit', [
            'breadcrumbs' => $breadcrumbs,
            'zoomAccount' => $zoomAccount
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, ZoomClientService $zoomClientService)
    {
        try {
            $response = $zoomClientService->checkZoomAccountViaAccessKey($request->token);
            if ($response->status() != StatusCode::OK) {
                return response()->json([
                    'status' => 'UNPROCESSABLE_ENTITY',
                    'message' => 'APIキーとAPI SECRETが正しくありません',
                ], StatusCode::UNPROCESSABLE_ENTITY);
            }
/*            $zoomUserId = $response->json('id');*/
            $zoomAccount = ZoomAccount::where([
                'api_key' =>  $request->api_key,
                'api_secret' => $request->api_secret,
                'zoom_email' => $request->zoom_email
            ])->whereNotIn('zoom_account_id', [$id])
                ->first();
            if (!empty($zoomAccount)) {
                return response()->json([
                    'status' => 'UNPROCESSABLE_ENTITY',
                    'message' => 'Zoomアカウントが重複しているため、登録できません。',
                ], StatusCode::UNPROCESSABLE_ENTITY);
            }

            $users = $zoomClientService->getUserList($request->token);
            $user = array_filter($users, function ($e) use ($request) {
                return $e['email'] == $request->zoom_email;
            });
            if (!empty($user)) {
                $zoomAccount = ZoomAccount::find($id);
                $zoomAccount->zoom_account_name = $request->zoom_account_name;
                $zoomAccount->api_key = $request->api_key;
                $zoomAccount->api_secret = $request->api_secret;
                $zoomAccount->zoom_user_id = reset($user)['id'];
                $zoomAccount->zoom_email = $request->zoom_email;
                $zoomAccount->save();
                return response()->json([
                    'status' => 'OK',
                ], StatusCode::OK);
            }
            return response()->json([
                'status' => 'UNPROCESSABLE_ENTITY',
                'message' => ' メールアドレスが見つかりません。',
            ], StatusCode::UNPROCESSABLE_ENTITY);

        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'INTERNAL_ERR',
            ], StatusCode::INTERNAL_ERR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            ZoomAccount::where('zoom_account_id', $id)->delete();

        } catch (ModelNotFoundException $ex) {
            return response()->json([
                'status' => 'NG',
                'data' => [],
            ], StatusCode::NOT_FOUND);
        }
        return response()->json([
            'status' => 'OK',
            'message' => 'Zoomアカウントの削除が完了しました',
            'data' => [],
        ], StatusCode::OK);
    }
}
