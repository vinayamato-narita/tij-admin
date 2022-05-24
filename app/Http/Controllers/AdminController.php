<?php

namespace App\Http\Controllers;

use App\Models\AdminUser;
use App\Models\AdminRight;
use App\Models\AdminUserRight;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Components\BreadcrumbComponent;
use App\Http\Controllers\BaseController;
use App\Enums\StatusCode;
use App\Http\Requests\CreateAdminRequest;
use App\Http\Requests\EditAdminRequest;
use Hash;
use Log;
use Illuminate\Support\Facades\Auth;
use App\Enums\AdminRole;

class AdminController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $role = Auth::user()->role;
            if($role == AdminRole::SYSTEM){
                return $next($request);
            }
           return redirect('/dashboard');
        });
    }
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
                $query->where($this->escapeLikeSentence('admin_user_name', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('admin_user_email', $request['search_input']));
            });
        }

        $adminList = $queryBuilder->sortable(['last_login_date' => 'desc'])->paginate($pageLimit);

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
        if(!$request->isMethod('POST')) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::BAD_REQUEST);              
        }
        $admin = new AdminUser;
        $admin->admin_user_name = $request->admin_user_name;
        $admin->admin_user_email = $request->admin_user_email;
        $admin->password = Hash::make($request->password);
        $admin->admin_user_description = $request->admin_user_description;
        $admin->role = $request->role;
        $admin->save();  

        return response()->json([
            'status' => 'OK',
            'id' => $admin->admin_user_id,
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
        $adminInfo = AdminUser::where('admin_user_id', $id)->firstOrFail();
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
            /*['name' => 'show_admin', $id],*/
            ['name' => 'edit_admin', $id],
        ]);
        $adminInfo = AdminUser::where('admin_user_id', $id)->firstOrFail();
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
        if(!$request->isMethod('PUT')) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::BAD_REQUEST);   
        }
        $adminInfo = AdminUser::where('admin_user_id', $id)->firstOrFail();
        $adminInfo->admin_user_name = $request->admin_user_name;
        $adminInfo->admin_user_email = $request->admin_user_email;
        $adminInfo->role = $request->role;
        if ($request->password != "") {
            $adminInfo->password = Hash::make($request->password);
        }
        
        $adminInfo->admin_user_description = $request->admin_user_description;
        $adminInfo->save();  
            
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
            $user = AdminUser::where('admin_user_id', $id)->delete();

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

    public function editRole($id)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'admin_list'],
            ['name' => 'edit_role', $id],
        ]);
        $adminInfo = AdminUser::where('admin_user_id', $id)->firstOrFail();
        
        $adminRole = AdminRight::with(['adminUserRights' => function ($query) use ($id) {
            $query->where('admin_user_id', '=', $id);
        }])
        ->sortable(['admin_rights_menu_order' => 'asc'])
        ->get()->toArray();
        foreach($adminRole as &$role)
        {
            if ($role['admin_user_rights'] == null) {
                $role['admin_user_id'] = $id;
                $role['admin_rights_id'] = $role['admin_rights_id'];
                $role['is_permitted'] = 0;
                $role['can_edit'] = 0;
            }else {
                $role['admin_user_rights_id'] = $role['admin_user_rights']['admin_user_rights_id'];
                $role['admin_user_id'] = $role['admin_user_rights']['admin_user_id'];
                $role['is_permitted'] = $role['admin_user_rights']['is_permitted'];
                $role['can_edit'] = $role['admin_user_rights']['can_edit'];
            }
        }

        return view('admin.edit-role', [
            'breadcrumbs' => $breadcrumbs,
            'adminInfo' => $adminInfo,
            'adminRole' => $adminRole,
        ]);
    }

    public function updateRole(Request $request)
    {
        if(!$request->isMethod('POST')) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::BAD_REQUEST);   
        }
        $roles = $request->roles;
        
        $dataUpdate = [];
        $dataInsert = [];
        foreach($roles as $key => $data) {
            if (isset($data['admin_user_rights_id'])) {
                $dataUpdate[] = [
                    'admin_user_rights_id' => $data['admin_user_rights_id'],
                    'is_permitted' => $data['is_permitted'],
                    'can_edit' => $data['can_edit'],
                ];
            }else {
                $dataInsert[] = [
                    'admin_user_id' => $data['admin_user_id'],
                    'admin_rights_id' => $data['admin_rights_id'],
                    'is_permitted' => $data['is_permitted'],
                    'can_edit' => $data['can_edit'],
                ];
            }
        }

        AdminUserRight::insert($dataInsert);
        \Batch::update(new AdminUserRight, $dataUpdate, 'admin_user_rights_id');

        return response()->json([
            'status' => 'OK',
        ], StatusCode::OK);
    }
}
