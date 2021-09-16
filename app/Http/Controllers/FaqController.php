<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Components\BreadcrumbComponent;
use App\Enums\StatusCode;
use App\Models\Faq;
use App\Models\FaqCategory;
use App\Http\Requests\FaqRequest;
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
        if($request->isMethod('POST')){
            $faq = new Faq;
            $faq->no_faq = $request->no_faq;
            $faq->faq_category_id = $request->faq_category_id;
            $faq->question = $request->question;
            $faq->answer = $request->answer;
            $faq->brand_id = 1;
            $faq->save();            
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
            ['name' => 'faq_list'],
            ['name' => 'show_faq', $id],
        ]);
        $faqInfo = Faq::where('faq_id', $id)->with('faqCategory')->firstOrFail();
        
        return view('faq.show', [
            'breadcrumbs' => $breadcrumbs,
            'faqInfo' => $faqInfo,
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
            ['name' => 'faq_list'],
            ['name' => 'show_faq', $id],
            ['name' => 'edit_faq', $id],
        ]);
        $faqInfo = Faq::where('faq_id', $id)->firstOrFail();
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
        if($request->isMethod('PUT')){
            $faqInfo = Faq::where('faq_id', $id)->firstOrFail();
            $faqInfo->no_faq = $request->no_faq;
            $faqInfo->faq_category_id = $request->faq_category_id;
            $faqInfo->question = $request->question;
            $faqInfo->answer = $request->answer;

            $faqInfo->save();            
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
            $user = Faq::where('faq_id', $id)->delete();

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
