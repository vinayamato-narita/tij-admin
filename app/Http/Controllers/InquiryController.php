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
use App\Components\CommonComponent;
use Response;
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

        $queryBuilder = AdminInquiry::leftJoin('student', 'student.student_id', '=', 'admin_inquiry.user_id')
        ->select('admin_inquiry.inquiry_id as inquiry_id', 'inquiry_date', 'inquiry_subject', 'admin_inquiry.user_id as student_id', 'student.student_name as student_name', DB::raw('ifnull(admin_inquiry.user_mail,student.student_email) as j_student_email'), 'inquiry_flag');

        if (isset($request['search_input'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('admin_inquiry.inquiry_subject', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('admin_inquiry.user_mail', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('student.student_email', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('student.student_name', $request['search_input']));
            });
        }

        if (isset($request['sort']) && $request['sort'] == "student_name") {
            $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('student.student_name','ASC') : $queryBuilder->orderBy('student.student_name','DESC');
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
        $inquiryInfo = AdminInquiry::where('inquiry_id', $id)->firstOrFail();
        $studentInfo = Student::where('student_id', $inquiryInfo->user_id)->first();
        $inquiryInfo->student_name = $studentInfo->student_name ?? "";
        $inquiryInfo->inquiry_flag_name = $inquiryInfo->inquiry_flag_name;
        $inquiryInfo->student_email = $inquiryInfo->user_mail ?? $studentInfo->student_email ?? "";
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
        $inquiryInfo = AdminInquiry::where('inquiry_id', $request->inquiry_id)->first();
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
        $inquiryInfo = AdminInquiry::where('inquiry_id', $request->inquiry_id)->first();
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

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $header = [
            $this->convertShijis("問合せ番号"), 
            $this->convertShijis("日時"), 
            $this->convertShijis("問い合わせ件名"), 
            $this->convertShijis("学習者番号"), 
            $this->convertShijis("名前"), 
            $this->convertShijis("メールアドレス"), 
            $this->convertShijis("対応状況"), 
            $this->convertShijis("問い合わせ内容")
        ];

        if (!file_exists(public_path().'/csv_file/users')) {
            mkdir(public_path().'/csv_file/users', 0777, true);
        }
        $localPath = public_path().'/csv_file/users/'.$fileName;
        $file = fopen($localPath, 'w');
        fputcsv($file, $header);

        $queryBuilder = AdminInquiry::leftJoin('student', 'student.student_id', '=', 'admin_inquiry.user_id')
        	->select('admin_inquiry.inquiry_id as inquiry_id', 'inquiry_date', 'inquiry_subject', 'admin_inquiry.user_id as student_id', 'student.student_name as student_name', DB::raw('ifnull(admin_inquiry.user_mail,student.student_email) as j_student_email'), 'inquiry_flag', 'inquiry_body');
        
        if (isset($this->searchInput)) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($searchInput) {
                $query->where(CommonComponent::escapeLikeSentence('admin_inquiry.inquiry_subject', $searchInput))
                    ->orWhere(CommonComponent::escapeLikeSentence('admin_inquiry.user_mail', $searchInput))
                    ->orWhere(CommonComponent::escapeLikeSentence('student.student_email', $searchInput))
                    ->orWhere(CommonComponent::escapeLikeSentence('student.student_name', $searchInput));
            });
        }
        $inquiryList = $queryBuilder->get()->map(function($item, $key) {
        	$item['inquiry_flag'] = InquiryFlag::getDescription($item['inquiry_flag']);
        	return $item;
        });

        foreach ($inquiryList as $inquiry) {
            $input = [];
            $input["問合せ番号"] = $inquiry['inquiry_id'];
            $input["日時"] = $inquiry['inquiry_date'];
            $input["問い合わせ件名"] = $inquiry['inquiry_subject'];
            $input["学習者番号"] = $inquiry['student_id'];
            $input["名前"] = $inquiry['student_name'];
            $input["メールアドレス"] = $inquiry['j_student_email'];
            $input["対応状況"] = $inquiry['inquiry_flag'];
            $input["問い合わせ内容"] = $inquiry['inquiry_body'];

            fputcsv($file, $input);
        }

        return Response::download(public_path().'/csv_file/users/'.$fileName, $fileName, $header);
    }

}
