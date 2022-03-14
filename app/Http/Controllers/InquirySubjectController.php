<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Components\BreadcrumbComponent;
use App\Enums\StatusCode;
use App\Enums\LangType;
use App\Models\InquirySubject;
use App\Http\Requests\InquirySubjectRequest;
use App\Http\Requests\InquirySubjectLangRequest;
use App\Models\InquirySubjectInfo;
use Carbon\Carbon;

class InquirySubjectController extends BaseController
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
            ['name' => 'inquiry_subject_index']
        ]);
        $pageLimit = $this->newListLimit($request);
        $queryBuilder = new InquirySubject();

        if (isset($request['search_input'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('inquiry_subject', $this->mb_trim($request['search_input'], ' ')));
            });
        }

        $inquirySubjectList = $queryBuilder->sortable(['inquiry_subject' => 'asc'])->paginate($pageLimit);

        return view('inquirySubject.index', [
            'breadcrumbs' => $breadcrumbs,
            'request' => $request,
            'pageLimit' => $pageLimit,
            'inquirySubjectList' => $inquirySubjectList,
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
            ['name' => 'inquiry_subject_index'],
            ['name' => 'create_inquiry_subject'],
        ]);

        return view('inquirySubject.create', [
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->isMethod('POST')){
            try{
                $inquirySubject = new InquirySubject();
                $inquirySubject->inquiry_subject = $request->inquiry_subject;
                $inquirySubject->last_update_date = Carbon::now();
                $inquirySubject->save();

                return response()->json([
                    'status' => 'OK',
                ], StatusCode::OK);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'NG',
                    'message' => '問い合わせ件名の編集に失敗しました。再度お願いいたします。'
                ], StatusCode::BAD_REQUEST);
            }
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
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'inquiry_subject_index'],
            ['name' => 'show_inquiry_subject', $id],
        ]);
        $inquirySubjectInfo = InquirySubject::where('inquiry_subject_id', $id)->firstOrFail();
        $inquirySubjectEnInfo = InquirySubjectInfo::where(['inquiry_subject_id' => $id, 'lang_type' => LangType::EN])->first();
        $inquirySubjectZhInfo = InquirySubjectInfo::where(['inquiry_subject_id' => $id, 'lang_type' => LangType::ZH])->first();

        return view('inquirySubject.show', [
            'breadcrumbs' => $breadcrumbs,
            'inquirySubjectInfo' => $inquirySubjectInfo,
            'inquirySubjectEnInfo' => $inquirySubjectEnInfo,
            'inquirySubjectZhInfo' => $inquirySubjectZhInfo,
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
            ['name' => 'inquiry_subject_index'],
            ['name' => 'show_inquiry_subject', $id],
            ['name' => 'edit_inquiry_subject', $id],
        ]);
        $inquirySubjectInfo = InquirySubject::where('inquiry_subject_id', $id)->firstOrFail();
        $inquirySubjectInfo->_token = csrf_token();
        
        return view('inquirySubject.edit', [
            'breadcrumbs' => $breadcrumbs,
            'inquirySubjectInfo' => $inquirySubjectInfo
        ]);
    }

    public function editLang($id, $langType)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'inquiry_subject_index'],
            ['name' => 'show_inquiry_subject', $id],
            ['name' => 'edit_lang_inquiry_subject', $id, $langType],
        ]);
        $inquirySubjectInfo = InquirySubject::where('inquiry_subject_id', $id)->firstOrFail();
        
        $inquirySubjectLangInfo = InquirySubjectInfo::where(['inquiry_subject_id' => $id, 'lang_type' => $langType])->first();
        $inquirySubjectInfo->_token = csrf_token();
        $inquirySubjectInfo->lang_inquiry_subject = $inquirySubjectLangInfo->inquiry_subject ?? "";
        $inquirySubjectInfo->lang = $langType;

        return view('inquirySubject.edit_lang', [
            'breadcrumbs' => $breadcrumbs,
            'inquirySubjectInfo' => $inquirySubjectInfo,
            'langType' => $langType,
        ]);
    }

    public function updateLang(InquirySubjectLangRequest $request)
    {
        if($request->isMethod('POST')){
            $inquirySubjectInfo = InquirySubject::where('inquiry_subject_id', $request->inquiry_subject_id)->first();
            if ($inquirySubjectInfo == null) {
                return response()->json([
                    'status' => 'OK',
                ], StatusCode::NOT_FOUND);
            }

            $inquirySubjectLangInfo = InquirySubjectInfo::updateOrCreate(
                ['inquiry_subject_id' => $request->inquiry_subject_id, 'lang_type' => $request->lang],
                ['inquiry_subject' => $request->lang_inquiry_subject]
            );
        }
        return response()->json([
            'status' => 'OK',
        ], StatusCode::OK);
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
        if($request->isMethod('PUT')){
            try {
                $inquirySubjectInfo = InquirySubject::where('inquiry_subject_id', $id)->firstOrFail();
                $inquirySubjectInfo->inquiry_subject = $request->inquiry_subject;
                $inquirySubjectInfo->last_update_date = Carbon::now();
                $inquirySubjectInfo->save();

                return response()->json([
                    'status' => 'OK',
                ], StatusCode::OK);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'NG',
                    'message' => '問い合わせ件名の編集に失敗しました。再度お願いいたします。'
                ], StatusCode::BAD_REQUEST);
            }
                        
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
            $inquirySubject = InquirySubject::where('inquiry_subject_id', $id)->delete();

        } catch (ModelNotFoundException $ex) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::NOT_FOUND);
        }
        return response()->json([
            'status' => 'OK',
            'message' => '問い合わせ件名の削除が完了しました。',
        ], StatusCode::OK);
    }
}
