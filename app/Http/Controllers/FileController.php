<?php

namespace App\Http\Controllers;

use App\Enums\StatusCode;
use App\Models\File;
use App\Models\Preparation;
use App\Models\Review;
use App\Models\TestQuestion;
use App\Models\TestSubQuestion;
use Illuminate\Http\Request;
use App\Components\BreadcrumbComponent;
use App\Http\Requests\CreateFileRequest;
use App\Http\Requests\EditFileRequest;
use App\Components\TIJAdminAzureComponent;
use App\Enums\AzureFolderEnum;
use Carbon\Carbon;
use App\Enums\FileTypeEnum;
use Log;

class FileController extends BaseController
{
    public function getFiles(Request $request)
    {
        $pageLimit = $this->newListLimit($request);
        $queryBuilder = new File();

        if (isset($request['inputSearch'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('file_name_original', $request['inputSearch']));
            });
        }

        if (isset($request->preparationId) || isset($request->reviewId) || isset($request->testQuestionId) || isset($request->testSubQuestionId)) {
            if (isset($request->preparationId))
                $targetObject = Preparation::find($request->preparationId);
            if (isset($request->reviewId))
                $targetObject = Review::find($request->reviewId);
            if (isset($request->testQuestionId))
                $targetObject = TestQuestion::find($request->testQuestionId);
            if (isset($request->testSubQuestionId))
                $targetObject = TestSubQuestion::find($request->testSubQuestionId);

            $selected = File::where('file_id', '=', $targetObject->file_id);
            if (isset($request->testSubQuestionId))
                $selected = File::where('file_id', '=', $targetObject->explanation_file_id);

            if (isset($request['inputSearch'])) {
                $selected = $selected->where(function ($query) use ($request) {
                    $query->where($this->escapeLikeSentence('file_name_original', $request['inputSearch']));
                });
            }

            $later = File::where('file_id', '!=', $targetObject->file_id)->sortable(['file_id' => 'asc']);
            if (isset($request['inputSearch'])) {
                $later = $later->where(function ($query) use ($request) {
                    $query->where($this->escapeLikeSentence('file_name_original', $request['inputSearch']));
                });
            }

            $fileList = $selected->union($later)->paginate($pageLimit);
        }
        else {
            $fileList = $queryBuilder->sortable(['file_id' => 'asc'])->paginate($pageLimit);

        }

        return response()->json([
            'status' => 'OK',
            'dataList' => $fileList
        ], StatusCode::OK);
    }

    public function index(Request $request)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'file_list']
        ]);
        $pageLimit = $this->newListLimit($request);
        $queryBuilder = new File;

        if (isset($request['search_input'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('file_code', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('file_display_name', $request['search_input']));
            });
        }
        
        $files = $queryBuilder->sortable(['news_update_date' => 'desc'])->paginate($pageLimit);

        return view('file.index', [
            'breadcrumbs' => $breadcrumbs,
            'request' => $request,
            'pageLimit' => $pageLimit,
            'files' => $files,
        ]);
    }

    public function create()
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'file_list'],
            ['name' => 'create_file'],
        ]);

        return view('file.create', [
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    public function store(CreateFileRequest $request)
    {
        if(!$request->isMethod('POST')){
            return response()->json([
                'status' => 'NG',
            ], StatusCode::BAD_REQUEST);        
        }

        $name = TIJAdminAzureComponent::upload(AzureFolderEnum::MEDIA, $request->file_attach);
        if (!$name) {
            return response()->json([
                'status' => 'NG'
            ], StatusCode::BAD_REQUEST);
        }
        $file = new File();
        $file->file_name = $name;
        $file->file_path = AzureFolderEnum::MEDIA . '/' . $name;
        $file->file_name_original = $request->file_attach->getClientOriginalName();
        $file->file_code = $request->file_code;
        $file->file_display_name = $request->file_display_name;
        $file->file_description = $request->file_description;
        $file->file_type = FileTypeEnum::MEDIA;

        $file->save();

        return response()->json([
            'status' => 'OK'
        ], StatusCode::OK);
    }

    public function edit($id)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'file_list'],
            ['name' => 'edit_file', $id],
        ]);
        $fileInfo = File::where('file_id', $id)->firstOrFail();
        $fileInfo->_token = csrf_token();
        $fileInfo->file_path = $this->getUrlFileBase() . $fileInfo->file_path;
        $fileInfo->pre_code = substr($fileInfo->file_code,0, 10);
        $fileInfo->file_code = substr($fileInfo->file_code,10);

        return view('file.edit', [
            'breadcrumbs' => $breadcrumbs,
            'fileInfo' => $fileInfo
        ]);
    }

    public function updateFile(EditFileRequest $request)
    {
        if(!$request->isMethod('POST')){
            return response()->json([
                'status' => 'NG',
            ], StatusCode::BAD_REQUEST);         
        }

        $fileInfo = File::where('file_id', $request->file_id)->firstOrFail();
        $fileInfo->file_code = $request->file_code;
        $fileInfo->file_display_name = $request->file_display_name;
        $fileInfo->file_description = $request->file_description;
       
        if($request->file_attach) {
            $name = TIJAdminAzureComponent::upload(AzureFolderEnum::MEDIA, $request->file_attach);
            if (!$name) {
                return response()->json([
                    'status' => 'NG'
                ], StatusCode::BAD_REQUEST);
            }
            $fileInfo->file_name = $name;
            $fileInfo->file_path = AzureFolderEnum::MEDIA . '/' . $name;
            $fileInfo->file_name_original = $request->file_attach->getClientOriginalName();
        }

        $fileInfo->save();  

        return response()->json([
            'status' => 'OK',
        ], StatusCode::OK);
    }
}
