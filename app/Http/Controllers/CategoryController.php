<?php

namespace App\Http\Controllers;

use App\Components\BreadcrumbComponent;
use App\Enums\StatusCode;
use App\Http\Requests\StoreUpdateCategoryRequest;
use App\Models\Category;
use App\Models\CategoryCourse;
use App\Models\Course;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends BaseController
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
            ['name' => 'category_list']
        ]);
        $pageLimit = $this->newListLimit($request);
        $queryBuilder = new Category;

        if (isset($request['search_input'])) {
            $queryBuilder = $queryBuilder->orWhere(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('category_name', $request['search_input']));

            });
        }

        $categoryList = $queryBuilder->sortable(['order_num' => 'asc'])->paginate($pageLimit);

        return view('category.index', [
            'breadcrumbs' => $breadcrumbs,
            'request' => $request,
            'pageLimit' => $pageLimit,
            'categoryList' => $categoryList,
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
            ['name' => 'category_list'],
            ['name' => 'category_create']
        ]);


        return view('category.create', [
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUpdateCategoryRequest $request)
    {
        if($request->isMethod('POST')){
            DB::beginTransaction();
            try {
                $category = new Category();
                $category->order_num = $request->orderNum;
                $category->category_icon = $request->categoryIcon;
                $category->category_name = $request->categoryName;

                $category->save();
                DB::commit();
                return response()->json([
                    'status' => 'OK',
                ], StatusCode::OK);
            } catch (\Exception $exception) {
                DB::rollBack();
                return response()->json([
                    'status' => 'INTERNAL_ERR',
                ], StatusCode::INTERNAL_ERR);
            }

        }
        return response()->json([
            'status' => 'METHOD_NOT_ALLOWED',
        ], StatusCode::METHOD_NOT_ALLOWED);
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
            ['name' => 'category_list'],
            ['name' => 'category_show', $id]
        ]);

        $cat = Category::where('id', $id)->with(['courses'])->first();
        if (!$cat) return redirect()->route('text.index');
        return view('category.show', [
            'breadcrumbs' => $breadcrumbs,
            'category' => $cat
        ]);
    }

    public function registerCourse(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            foreach ($request->all() as $rq) {
                $tc = new CategoryCourse();
                $tc->course_id = $rq;
                $tc->category_id = $id;
                $tc->save();
            }
        }
        catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 'INTERNAL_ERR',
            ], StatusCode::INTERNAL_ERR);
        }

        DB::commit();
        return response()->json([
            'status' => 'OK',
        ], StatusCode::OK);
    }

    public function course(Request $request, $id)
    {
        $pageLimit = $this->newListLimit($request);
        $queryBuilder = new Course();

        if (isset($request['inputSearch'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->orwhere($this->escapeLikeSentence('course_name', $request['inputSearch']));
                $query->orwhere($this->escapeLikeSentence('course_name_short', $request['inputSearch']));
            });
        }
        $courseHasAdded = CategoryCourse::where('category_id', $id)->pluck('course_id');

        $courseList = $queryBuilder->whereNotIn('course_id', $courseHasAdded)->sortable(['course_id' => 'asc', 'lesson_text_name' => 'asc'])->paginate($pageLimit);
        return response()->json([
            'status' => 'OK',
            'dataList' => $courseList
        ], StatusCode::OK);
    }

    public function courseDelete($id, $courseId)
    {

        try {
            $catCourse = CategoryCourse::where([
                'category_id' => $id,
                'course_id' => $courseId
            ])->delete();

        } catch (ModelNotFoundException $ex) {
            return response()->json([
                'status' => 'NG',
                'data' => [],
            ], StatusCode::NOT_FOUND);
        }
        return response()->json([
            'status' => 'OK',
            'message' => ' コースの解除が完了しました。',
            'data' => [],
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
            ['name' => 'category_list'],
            ['name' => 'category_show', $id],
            ['name' => 'category_edit', $id]
        ]);

        $cat = Category::where('id', $id)->first();
        if (!$cat) return redirect()->route('category.index');
        return view('category.edit', [
            'breadcrumbs' => $breadcrumbs,
            'category' => $cat
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreUpdateCategoryRequest $request, $id)
    {
        if($request->isMethod('PUT')){
            $category = Category::where('id', $id)->first();
            if (!$category) {
                return response()->json([
                    'status' => 'NOT_FOUND',
                ], StatusCode::NOT_FOUND);
            }

            DB::beginTransaction();
            try {
                $category->order_num = $request->orderNum;
                $category->category_icon = $request->categoryIcon;
                $category->category_name = $request->categoryName;


                $category->save();
                DB::commit();
                return response()->json([
                    'status' => 'OK',
                ], StatusCode::OK);
            } catch (\Exception $exception) {
                DB::rollBack();
                return response()->json([
                    'status' => 'INTERNAL_ERR',
                ], StatusCode::INTERNAL_ERR);
            }

        }
        return response()->json([
            'status' => 'METHOD_NOT_ALLOWED',
        ], StatusCode::METHOD_NOT_ALLOWED);
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
