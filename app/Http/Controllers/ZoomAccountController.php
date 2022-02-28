<?php

namespace App\Http\Controllers;

use App\Components\BreadcrumbComponent;
use App\Enums\StatusCode;
use App\Models\ZoomAccount;
use App\Services\ZoomService;
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
    public function store(Request $request, ZoomService $zoomService)
    {
        try {
            $url = 'https://api.zoom.us/v2/users/me';
            $method = 'GET';
            $data = $zoomService->zoomClient($url, $method, $request->token);
            if ($data['http_code'] != StatusCode::OK) {
                return response()->json([
                    'status' => 'UNPROCESSABLE_ENTITY',
                    'message' => 'APIキーとAPI SECRETが正しくありません',
                ], StatusCode::UNPROCESSABLE_ENTITY);
            }
            $zoom_user_id = $data['data']->id;
            $zoomAccount = ZoomAccount::where('zoom_user_id', $zoom_user_id)->first();
            if (!empty($zoomAccount)) {
                return response()->json([
                    'status' => 'UNPROCESSABLE_ENTITY',
                    'message' => 'Zoomアカウントが重複しているため、登録できません。',
                ], StatusCode::UNPROCESSABLE_ENTITY);
            }
            $zoomAccount = new ZoomAccount();
            $zoomAccount->zoom_account_name = $request->zoom_account_name;
            $zoomAccount->api_key = $request->api_key;
            $zoomAccount->api_secret = $request->api_secret;
            $zoomAccount->zoom_user_id = $zoom_user_id;
            $zoomAccount->save();
            return response()->json([
                'status' => 'OK',
            ], StatusCode::OK);
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
    public function update(Request $request, $id, ZoomService $zoomService)
    {
        try {
            $url = 'https://api.zoom.us/v2/users/me';
            $method = 'GET';
            $data = $zoomService->zoomClient($url, $method, $request->token);
            if ($data['http_code'] != StatusCode::OK) {
                return response()->json([
                    'status' => 'UNPROCESSABLE_ENTITY',
                    'message' => 'APIキーとAPI SECRETが正しくありません',
                ], StatusCode::UNPROCESSABLE_ENTITY);
            }
            $zoom_user_id = $data['data']->id;
            $zoomAccount = ZoomAccount::where('zoom_user_id', $zoom_user_id)
                ->whereNotIn('zoom_account_id', [$id])
                ->first();
            if (!empty($zoomAccount)) {
                return response()->json([
                    'status' => 'UNPROCESSABLE_ENTITY',
                    'message' => 'Zoomアカウントが重複しているため、登録できません。',
                ], StatusCode::UNPROCESSABLE_ENTITY);
            }
            $zoomAccount = ZoomAccount::find($id);
            $zoomAccount->zoom_account_name = $request->zoom_account_name;
            $zoomAccount->api_key = $request->api_key;
            $zoomAccount->api_secret = $request->api_secret;
            $zoomAccount->zoom_user_id = $zoom_user_id;
            $zoomAccount->save();
            return response()->json([
                'status' => 'OK',
            ], StatusCode::OK);
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
        //
    }
}
