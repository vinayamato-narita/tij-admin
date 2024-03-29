<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Components\BreadcrumbComponent;
use App\Enums\StatusCode;
use App\Enums\LangType;
use App\Models\AdminNews;
use App\Models\NewsSubject;
use App\Models\AdminNewsInfo;
use App\Http\Requests\NewsRequest;
use App\Http\Requests\NewsLangRequest;
use Carbon\Carbon;
use Log;

class NewsController extends BaseController
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
            ['name' => 'news_list']
        ]);
        $pageLimit = $this->newListLimit($request);
        $queryBuilder = AdminNews::select('admin_news.news_id as news_id', 'is_show_on_student_top', 'admin_news.news_update_date as news_update_date',
            'news_subject.news_subject_ja as news_subject_ja', 'news_title', 'news_body')
        ->leftJoin('news_subject', 'news_subject.news_subject_id', '=', 'admin_news.news_subject_id');

        if (isset($request['search_input'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('admin_news.news_title', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('admin_news.news_body', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('news_subject.news_subject_ja', $request['search_input']));
            });
        }
        if (isset($request['sort']) && $request['sort'] == "news_subject_ja") {
            $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderBy('news_subject.news_subject_ja','ASC') : $queryBuilder->orderBy('news_subject.news_subject_ja','DESC');
        }
        
        $newsList = $queryBuilder->sortable(['news_update_date' => 'desc'])->paginate($pageLimit);

        return view('news.index', [
            'breadcrumbs' => $breadcrumbs,
            'request' => $request,
            'pageLimit' => $pageLimit,
            'newsList' => $newsList,
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
            ['name' => 'news_list'],
            ['name' => 'create_news'],
        ]);

        $newsSubjects = NewsSubject::all();

        return view('news.create', [
            'breadcrumbs' => $breadcrumbs,
            'newsSubjects' => $newsSubjects,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewsRequest $request)
    {
        if(!$request->isMethod('POST')){
            return response()->json([
                'status' => 'NG',
            ], StatusCode::BAD_REQUEST);        
        }
        $subjectInfo = NewsSubject::where('news_subject_id', $request->news_subject_id)->first();
        if ($subjectInfo == null) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::NOT_FOUND);
        }
        $news = new AdminNews;

        $news->news_subject_id = $request->news_subject_id;
        $news->news_title = $request->news_title;
        $news->news_body = $request->news_body;
        $news->is_show_on_student_top = $request->is_show_on_student_top;
        $news->news_update_date = Carbon::now();
        $news->save();  

        return response()->json([
            'status' => 'OK',
            'id' => $news->news_id,
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
            ['name' => 'news_list'],
            ['name' => 'show_news', $id],
        ]);
        $newsInfo = AdminNews::where('news_id', $id)->with('newsSubject')->firstOrFail();
        
        $newsEnInfo = AdminNewsInfo::where(['news_id' => $id, 'lang_type' => LangType::EN])->first();
        $newsZhInfo = AdminNewsInfo::where(['news_id' => $id, 'lang_type' => LangType::ZH])->first();

        return view('news.show', [
            'breadcrumbs' => $breadcrumbs,
            'newsInfo' => $newsInfo,
            'newsEnInfo' => $newsEnInfo,
            'newsZhInfo' => $newsZhInfo,
        ]);
    }

    public function editLang($id, $langType)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'news_list'],
            ['name' => 'show_news', $id],
            ['name' => 'edit_lang_news', $id, $langType],
        ]);
        $newsInfo = AdminNews::where('news_id', $id)->firstOrFail();
        
        $newsLangInfo = AdminNewsInfo::where(['news_id' => $id, 'lang_type' => $langType])->first();
        $newsInfo->_token = csrf_token();
        $newsInfo->news_lang_title = $newsLangInfo->news_title ?? "";
        $newsInfo->news_lang_body = $newsLangInfo->news_body ?? "";
        $newsInfo->lang = $langType;
        $newsInfo->title = $langType == LangType::EN ? '英語版' : '中国語版';

        return view('news.edit_lang', [
            'breadcrumbs' => $breadcrumbs,
            'newsInfo' => $newsInfo,
        ]);
    }

    public function updateLang(NewsLangRequest $request)
    {
        if(!$request->isMethod('POST')){
            return response()->json([
                'status' => 'NG',
            ], StatusCode::BAD_REQUEST);  
        }
        $newsInfo = AdminNews::where('news_id', $request->news_id)->first();
        if ($newsInfo == null) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::NOT_FOUND);
        }
        $newsLangInfo = AdminNewsInfo::updateOrCreate(
            ['news_id' => $request->news_id, 'lang_type' => $request->lang],
            ['news_title' => $request->news_lang_title, 'news_body' => $request->news_lang_body]
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
            ['name' => 'news_list'],
            ['name' => 'show_news', $id],
            ['name' => 'edit_news', $id],
        ]);
        $newsInfo = AdminNews::where('news_id', $id)->firstOrFail();
        $newsInfo->_token = csrf_token();
        $newsSubjects = NewsSubject::all();

        return view('news.edit', [
            'breadcrumbs' => $breadcrumbs,
            'newsInfo' => $newsInfo,
            'newsSubjects' => $newsSubjects,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(NewsRequest $request, $id)
    {
        if(!$request->isMethod('PUT')){
            return response()->json([
                'status' => 'NG',
            ], StatusCode::BAD_REQUEST);         
        }
        $subjectInfo = NewsSubject::where('news_subject_id', $request->news_subject_id)->first();
        if ($subjectInfo == null) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::NOT_FOUND);
        }

        $newsInfo = AdminNews::where('news_id', $id)->firstOrFail();
        $newsInfo->news_subject_id = $request->news_subject_id;
        $newsInfo->news_title = $request->news_title;
        $newsInfo->news_body = $request->news_body;
        $newsInfo->is_show_on_student_top = $request->is_show_on_student_top;
        $newsInfo->news_update_date = Carbon::now();
        
        $newsInfo->save();  

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
            $newsInfo = AdminNews::where('news_id', $id)->delete();

        } catch (ModelNotFoundException $ex) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::NOT_FOUND);
        }
        return response()->json([
            'status' => 'OK',
            'message' => 'お知らせの削除が完了しました。',
        ], StatusCode::OK);
    }

    public function changeStatus(Request $request, $id)
    {
        try {
            $newsInfo = AdminNews::where('news_id', $id)->firstOrFail();
            $newsInfo->is_show_on_student_top = $newsInfo->is_show_on_student_top == 0 ? 1 : 0;
            $newsInfo->save();

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
