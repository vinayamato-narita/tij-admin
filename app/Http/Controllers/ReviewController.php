<?php

namespace App\Http\Controllers;

use App\Components\BreadcrumbComponent;
use App\Components\TIJAdminAzureComponent;
use App\Enums\AzureFolderEnum;
use App\Enums\FileTypeEnum;
use App\Enums\StatusCode;
use App\Http\Requests\StoreUpdateReviewRequest;
use App\Models\File;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReviewController extends BaseController
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
            ['name' => 'review_list']
        ]);
        $pageLimit = $this->newListLimit($request);
        $queryBuilder = new Review();

        if (isset($request['search_input'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('review_name', $request['search_input']))
                    ->orWhere('review_id', $request['search_input'])
                    ->orWhere($this->escapeLikeSentence('review_description', $request['search_input']));
            });
        }

        $reviewList = $queryBuilder->sortable(['display_order' => 'asc', 'review_name' => 'asc'])->paginate($pageLimit);

        return view('review.index', [
            'breadcrumbs' => $breadcrumbs,
            'request' => $request,
            'pageLimit' => $pageLimit,
            'reviewList' => $reviewList,
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
            ['name' => 'review_list'],
            ['name' => 'review_add']
        ]);

        return view('review.add', [
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUpdateReviewRequest $request)
    {
        if($request->isMethod('POST')){
            DB::beginTransaction();
            try {
                $review = new Review();
                $review->display_order = $request->displayOrder;
                $review->review_name = $request->reviewName;
                $review->review_description = $request->reviewDescription ?? '';

                if (isset($request->fileSelected)) {
                    $name = TIJAdminAzureComponent::upload(AzureFolderEnum::REVIEW, $request->fileSelected);
                    if ($name) {
                        $file = new File();
                        $file->file_name = $name;
                        $file->file_name_original = $request->fileSelected->getClientOriginalName();
                        $file->file_path = AzureFolderEnum::REVIEW . '/' . $name;
                        $file->file_type = FileTypeEnum::REVIEW_VIDEO;
                        $file->save();
                        $review->file_id = $file->file_id;
                    }
                }
                if (isset($request->fileId)) {
                    $storedFile = File::query()->find($request->fileId);
                    if ($storedFile)
                        $review->file_id = $storedFile->file_id;

                }
                $review->save();
                DB::commit();
                return response()->json([
                    'status' => 'OK',
                ], StatusCode::OK);
            } catch (\Exception $exception) {
                Log::error($exception);
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
            ['name' => 'review_list'],
            ['name' => 'review_show', $id]
        ]);

        $review = Review::where('review_id', $id)->with('file')->first();
        if (!$review) return redirect()->route('review.index');
        return view('review.show', [
            'breadcrumbs' => $breadcrumbs,
            'review' => $review
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
            ['name' => 'review_list'],
            ['name' => 'review_show', $id],
            ['name' => 'review_edit', $id]
        ]);

        $review = Review::where('review_id', $id)->with('file')->first();
        if (!$review) return redirect()->route('review.index');
        return view('review.edit', [
            'breadcrumbs' => $breadcrumbs,
            'review' => $review
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
        if($request->isMethod('PUT')){
            $review = Review::where('review_id', $id)->first();
            if (!$review) {
                return response()->json([
                    'status' => 'NOT_FOUND',
                ], StatusCode::NOT_FOUND);
            }

            DB::beginTransaction();
            try {
                $review->display_order = $request->displayOrder;
                $review->review_name = $request->reviewName;
                $review->review_description = $request->reviewDescription;
                if (isset($request->fileSelected)) {
                    $name = TIJAdminAzureComponent::upload(AzureFolderEnum::REVIEW, $request->fileSelected);
                    if ($name) {
                        $file = new File();
                        $file->file_name = $name;
                        $file->file_name_original = $request->fileSelected->getClientOriginalName();
                        $file->file_path = AzureFolderEnum::REVIEW . '/' . $name;
                        $file->file_type = FileTypeEnum::REVIEW_VIDEO;
                        $file->save();
                        $review->file_id = $file->file_id;
                    }
                }
                if (isset($request->fileId)) {
                    $storedFile = File::query()->find($request->fileId);
                    if ($storedFile)
                        $review->file_id = $storedFile->file_id;

                }

                $review->save();
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
        ], StatusCode::METHOD_NOT_ALLOWED);    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $review = Review::where('review_id', $id)->delete();

        } catch (\Exception $ex) {
            return response()->json([
                'status' => 'NG',
                'data' => [],
            ], StatusCode::NOT_FOUND);
        }
        return response()->json([
            'status' => 'OK',
            'message' => ' 予習が削除されました',
            'data' => [],
        ], StatusCode::OK);
    }
}
