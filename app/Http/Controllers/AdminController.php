<?php

namespace App\Http\Controllers;

use App\Models\AdminUser;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Components\BreadcrumbComponent;
use App\Http\Controllers\BaseController;
use App\Enums\StatusCode;
use App\Http\Requests\CreateAdminRequest;
use App\Http\Requests\EditAdminRequest;
use Hash;
use Log;

class AdminController extends BaseController
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
            ['name' => 'admin_list']
        ]);
        $pageLimit = $this->newListLimit($request);
        $queryBuilder = new AdminUser();

        if (isset($request['search_input'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('admin_name', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('admin_email', $request['search_input']));
            });
        }

        $adminList = $queryBuilder->sortable(['updated_at' => 'desc'])->paginate($pageLimit);

        return view('admin.index', [
            'breadcrumbs' => $breadcrumbs,
            'request' => $request,
            'pageLimit' => $pageLimit,
            'adminList' => $adminList,
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
            ['name' => 'admin_list'],
            ['name' => 'create_admin'],
        ]);
        return view('admin.create', [
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateAdminRequest $request)
    {
        if($request->isMethod('POST')) {
            $admin = new AdminUser;
            $admin->admin_name = $request->admin_name;
            $admin->admin_email = $request->admin_email;
            $admin->password = Hash::make($request->password);
            $admin->description = $request->description;
            $admin->save();            
        }
        return response()->json([
            'status' => 'OK',
        ], StatusCode::OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'admin_list'],
            ['name' => 'show_admin', $id],
        ]);
        $adminInfo = AdminUser::where('id', $id)->firstOrFail();
        return view('admin.show', [
            'breadcrumbs' => $breadcrumbs,
            'adminInfo' => $adminInfo,
        ]);
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
            ['name' => 'admin_list'],
            ['name' => 'show_admin', $id],
            ['name' => 'edit_admin', $id],
        ]);
        $adminInfo = AdminUser::where('id', $id)->firstOrFail();
        $adminInfo->_token = csrf_token();
        
        return view('admin.edit', [
            'breadcrumbs' => $breadcrumbs,
            'adminInfo' => $adminInfo,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EditAdminRequest $request, $id)
    {
        if($request->isMethod('PUT')){
            $adminInfo = AdminUser::where('id', $id)->firstOrFail();
            $adminInfo->admin_name = $request->admin_name;
            $adminInfo->admin_email = $request->admin_email;
            if ($request->password != "") {
                $adminInfo->password = Hash::make($request->password);
            }
            
            $adminInfo->description = $request->description;
            $adminInfo->save();            
        }
        return response()->json([
            'status' => 'OK',
        ], StatusCode::OK);
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
            $user = AdminUser::where('id', $id)->delete();

        } catch (ModelNotFoundException $ex) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::NOT_FOUND);
        }
        return response()->json([
            'status' => 'OK',
            'message' => '管理ユーザの削除が完了しました。',
        ], StatusCode::OK);
    }

    public function changeStatus(Request $request, $id)
    {
        try {
            $user = AdminUser::where('id', $id)->firstOrFail();
            $user->is_online = $user->is_online == 0 ? 1 : 0;
            $user->save();

        } catch (ModelNotFoundException $ex) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::NOT_FOUND);
        }
        return response()->json([
            'status' => 'OK',
        ], StatusCode::OK);
    }
}
