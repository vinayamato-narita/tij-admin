<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Components\BreadcrumbComponent;
use App\Enums\StatusCode;
use App\Enums\LangType;
use App\Models\Faq;
use App\Models\FaqCategory;
use App\Models\FaqInfo;
use App\Http\Requests\FaqRequest;
use App\Http\Requests\FaqLangRequest;
use Log;

class FaqController extends BaseController
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
            ['name' => 'faq_list']
        ]);
        $pageLimit = $this->newListLimit($request);
        $queryBuilder = new Faq();

        if (isset($request['search_input'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('question', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('answer', $request['search_input']));
            });
        }

        $faqList = $queryBuilder->sortable(['no_faq' => 'asc'])->paginate($pageLimit);

        return view('faq.index', [
            'breadcrumbs' => $breadcrumbs,
            'request' => $request,
            'pageLimit' => $pageLimit,
            'faqList' => $faqList,
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
            ['name' => 'faq_list'],
            ['name' => 'create_faq'],
        ]);

        $faqCategories = FaqCategory::orderBy('oder_number', 'asc')->get();

        return view('faq.create', [
            'breadcrumbs' => $breadcrumbs,
            'faqCategories' => $faqCategories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FaqRequest $request)
    {
        if(!$request->isMethod('POST')){
            return response()->json([
                'status' => 'OK',
            ], StatusCode::BAD_REQUEST);  
        }
        $faqCategoryInfo = FaqCategory::where('id', $request->faq_category_id)->first();
        if ($faqCategoryInfo == null) {
            return response()->json([
                'status' => 'OK',
            ], StatusCode::NOT_FOUND);
        }

        $faq = new Faq;
        $faq->no_faq = $request->no_faq;
        $faq->faq_category_id = $request->faq_category_id;
        $faq->question = $request->question;
        $faq->answer = $request->answer;
        $faq->save();    

        return response()->json([
            'status' => 'OK',
            'id' => $faq->id,
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
            ['name' => 'faq_list'],
            ['name' => 'show_faq', $id],
        ]);
        $faqInfo = Faq::where('id', $id)->with('faqCategory')->firstOrFail();
        $faqVnInfo = FaqInfo::where(['faq_id' => $id, 'lang_type' => LangType::VN])->first();
        $faqEnInfo = FaqInfo::where(['faq_id' => $id, 'lang_type' => LangType::EN])->first();

        return view('faq.show', [
            'breadcrumbs' => $breadcrumbs,
            'faqInfo' => $faqInfo,
            'faqVnInfo' => $faqVnInfo,
            'faqEnInfo' => $faqEnInfo,
        ]);
    }

    public function editLang($id, $langType)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'faq_list'],
            ['name' => 'show_faq', $id],
            ['name' => 'edit_lang_faq', $id, $langType],
        ]);
        $faqInfo = Faq::where('id', $id)->firstOrFail();
        
        $faqLangInfo = FaqInfo::where(['faq_id' => $id, 'lang_type' => $langType])->first();
        $faqInfo->_token = csrf_token();
        $faqInfo->lang_question = $faqLangInfo->question ?? "";
        $faqInfo->lang_answer = $faqLangInfo->answer ?? "";
        $faqInfo->lang = $langType;

        return view('faq.edit_lang', [
            'breadcrumbs' => $breadcrumbs,
            'faqInfo' => $faqInfo,
        ]);
    }

    public function updateLang(FaqLangRequest $request)
    {
        if(!$request->isMethod('POST')){
            return response()->json([
                'status' => 'OK',
            ], StatusCode::BAD_REQUEST);  
        }
        $faqInfo = Faq::where('id', $request->id)->first();
        if ($faqInfo == null) {
            return response()->json([
                'status' => 'OK',
            ], StatusCode::NOT_FOUND);
        }
        $faqLangInfo = FaqInfo::updateOrCreate(
            ['faq_id' => $request->id, 'lang_type' => $request->lang],
            ['question' => $request->lang_question, 'answer' => $request->lang_answer]
        );
        return response()->json([
            'status' => 'OK',
        ], StatusCode::OK);
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
            ['name' => 'faq_list'],
            ['name' => 'show_faq', $id],
            ['name' => 'edit_faq', $id],
        ]);
        $faqInfo = Faq::where('id', $id)->firstOrFail();
        $faqInfo->_token = csrf_token();
        $faqCategories = FaqCategory::orderBy('oder_number', 'asc')->get();
        
        return view('faq.edit', [
            'breadcrumbs' => $breadcrumbs,
            'faqInfo' => $faqInfo,
            'faqCategories' => $faqCategories,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FaqRequest $request, $id)
    {
        if(!$request->isMethod('PUT')) {
            return response()->json([
                'status' => 'OK',
            ], StatusCode::BAD_REQUEST);  
        }
        $faqInfo = Faq::where('id', $id)->firstOrFail();
        $faqInfo->no_faq = $request->no_faq;
        $faqInfo->faq_category_id = $request->faq_category_id;
        $faqInfo->question = $request->question;
        $faqInfo->answer = $request->answer;

        $faqInfo->save();  
            
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
            $user = Faq::where('id', $id)->delete();

        } catch (ModelNotFoundException $ex) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::NOT_FOUND);
        }
        return response()->json([
            'status' => 'OK',
            'message' => 'FAQの削除が完了しました。',
        ], StatusCode::OK);
    }
}
