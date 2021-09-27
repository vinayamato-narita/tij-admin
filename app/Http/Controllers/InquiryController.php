<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Components\BreadcrumbComponent;
use App\Enums\StatusCode;
use App\Enums\InquiryFlag;
use App\Models\AdminInquiry;
use App\Models\Student;
use App\Http\Requests\NewsRequest;
use Carbon\Carbon;
use App\Exports\InquiryExport;
use Maatwebsite\Excel\Facades\Excel;
use DB;
use Log;

class InquiryController extends BaseController
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
            ['name' => 'inquiry_list']
        ]);
        $pageLimit = $this->newListLimit($request);

        $queryBuilder = AdminInquiry::leftJoin('students', 'students.id', '=', 'admin_inquiries.student_id')
        ->select('admin_inquiries.id as id', 'inquiry_date', 'inquiry_subject', 'student_id', 'students.student_name as student_name', DB::raw('ifnull(admin_inquiries.student_email,students.student_email) as j_student_email'), 'inquiry_flag');

        if (isset($request['search_input'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('admin_inquiries.inquiry_subject', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('admin_inquiries.student_email', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('students.student_email', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('students.student_name', $request['search_input']));
            });
        }

        if (isset($request['sort']) && $request['sort'] == "student_name") {
            $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('students.student_name','ASC') : $queryBuilder->orderBy('students.student_name','DESC');
        }
        if (isset($request['sort']) && $request['sort'] == "j_student_email") {
            $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('j_student_email','ASC') : $queryBuilder->orderBy('j_student_email','DESC');
        }
        $inquiryList = $queryBuilder->sortable(['inquiry_date' => 'desc'])->paginate($pageLimit);

        return view('inquiry.index', [
            'breadcrumbs' => $breadcrumbs,
            'request' => $request,
            'pageLimit' => $pageLimit,
            'inquiryList' => $inquiryList,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
            ['name' => 'inquiry_list'],
            ['name' => 'edit_inquiry', $id],
        ]);
        $inquiryInfo = AdminInquiry::where('id', $id)->firstOrFail();
        $studentInfo = Student::where('id', $inquiryInfo->student_id)->first();
        $inquiryInfo->student_name = $studentInfo->student_name ?? "";
        $inquiryInfo->inquiry_flag_name = $inquiryInfo->inquiry_flag_name;
        $inquiryInfo->student_email = $inquiryInfo->student_email ?? $studentInfo->student_email ?? "";
        $inquiryInfo->_token = csrf_token();

        return view('inquiry.edit', [
            'breadcrumbs' => $breadcrumbs,
            'inquiryInfo' => $inquiryInfo,
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
        if(!$request->isMethod('PUT')){
            return response()->json([
                'status' => 'OK',
            ], StatusCode::BAD_REQUEST);              
        }
        $inquiryInfo = AdminInquiry::where('id', $request->id)->first();
        if ($inquiryInfo == null) {
            return response()->json([
                'status' => 'OK',
            ], StatusCode::NOT_FOUND);
        }

        $inquiryInfo->inquiry_memo = $request->inquiry_memo;

        $inquiryInfo->save();  
        return response()->json([
            'status' => 'OK',
        ], StatusCode::OK);
    }

    public function changeInquiryFlag(Request $request)
    {
        if(!$request->isMethod('POST')){
            return response()->json([
                'status' => 'OK',
            ], StatusCode::BAD_REQUEST);            
        }
        $inquiryInfo = AdminInquiry::where('id', $request->id)->first();
        if ($inquiryInfo == null) {
            return response()->json([
                'status' => 'OK',
            ], StatusCode::NOT_FOUND);
        }

        $inquiryInfo->inquiry_flag = InquiryFlag::SUPPORTED;

        $inquiryInfo->save();

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
        //
    }

    public function exportInquiry($searchInput = null)
    {
        $fileName = "contact_".date("Y_m_d").".csv";
        return Excel::download(new InquiryExport($searchInput), $fileName);
    }

}
