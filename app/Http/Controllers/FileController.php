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
use App\Enums\OptionUploadFile;
use Log;
use DB;

class FileController extends BaseController
{
    public function getFiles(Request $request)
    {
        if (isset($request->page) && !is_numeric($request->page))
            return response()->json([
                'status' => 'INTERNAL_ERR',
            ], StatusCode::INTERNAL_ERR);
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

        $optionUploadFile = OptionUploadFile::asSelectArray();
        $fileBaseMedia = config('env.AZURE_STORAGE_URL') . "/" . AzureFolderEnum::MEDIA . "/";
        
        return view('file.create', [
            'breadcrumbs' => $breadcrumbs,
            'optionUploadFile' => $optionUploadFile,
            'fileBaseMedia' => $fileBaseMedia
        ]);
    }

    public function store(CreateFileRequest $request)
    {
        if(!$request->isMethod('POST')){
            return response()->json([
                'status' => 'NG',
            ], StatusCode::BAD_REQUEST);        
        }
        $file = new File();
        if($request->option_upload_file == OptionUploadFile::PC) {
            $name = TIJAdminAzureComponent::upload(AzureFolderEnum::MEDIA, $request->file_attach);
            if (!$name) {
                return response()->json([
                    'status' => 'NG'
                ], StatusCode::BAD_REQUEST);
            }
            
            $file->file_name = $name;
            $file->file_path = AzureFolderEnum::MEDIA . '/' . $name;
            $file->file_name_original = $request->file_attach->getClientOriginalName();
        }else {
            $fileBaseMedia = config('env.AZURE_STORAGE_URL') . "/" . AzureFolderEnum::MEDIA . "/";
            $arrUrl = explode($fileBaseMedia, $request->url_file_path);
            
            $orgirinalName = $arrUrl[1]; 

            $file->file_path = AzureFolderEnum::MEDIA . "/" . $orgirinalName;
            $file->file_name = $orgirinalName;
            $file->file_name_original = $orgirinalName;
        }
        
        $file->file_code = $request->file_code;
        $file->file_display_name = $request->file_display_name;
        $file->file_description = $request->file_description ?? '';
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

        $optionUploadFile = OptionUploadFile::asSelectArray();
        $fileBaseMedia = config('env.AZURE_STORAGE_URL') . "/" . AzureFolderEnum::MEDIA . "/";

        $fileInfo = File::where('file_id', $id)->firstOrFail();
        $fileInfo->_token = csrf_token();
        $fileInfo->file_path = $this->getUrlFileBase() . $fileInfo->file_path;
        $fileInfo->pre_code = substr($fileInfo->file_code,0, 10);
        $fileInfo->file_code = substr($fileInfo->file_code,10);
        $fileInfo->optionUploadFile = $optionUploadFile;
        $fileInfo->fileBaseMedia = $fileBaseMedia;

        $mediaList = DB::select("select preparation_name as media_name, '予習' as media_type, CONCAT('/preparation', '/', preparation_id) as href from preparation where file_id = " . $id . "
        UNION select review_name as media_name, '復習' as media_type, CONCAT('/review', '/', review_id) as href from review where file_id = " . $id . "
        UNION select lesson_text_name  as media_name, '学習者用テキスト' as media_type, CONCAT('/text', '/', lesson_text_id) as href from lesson_text where lesson_text_student_file_id = " . $id . "
        UNION select lesson_text_name  as media_name, '講師者用テキスト' as media_type, CONCAT('/text', '/', lesson_text_id) as href from lesson_text where lesson_text_teacher_file_id = " . $id . "
        UNION select test_name as media_name, 'テスト問題' as media_type, CONCAT('/test', '/', test.test_id) as href from test inner join test_question on test.test_id = test_question.test_id where file_id = " . $id. "
        UNION select test_name as media_name, 'テスト問題' as media_type, CONCAT('/test', '/', test.test_id) as href from test inner join test_question on test.test_id = test_question.test_id inner join test_sub_question on test_sub_question.test_question_id =  test_question.test_question_id where test_sub_question.explanation_file_id = " . $id);

        $fileInfo->mediaList = $mediaList;

        return view('file.edit', [
            'breadcrumbs' => $breadcrumbs,
            'fileInfo' => $fileInfo,
            'mediaList' => $mediaList
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
        $fileInfo->file_description = $request->file_description ?? '';
       
        if($request->option_upload_file == OptionUploadFile::PC && $request->file_attach) {
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

        if($request->option_upload_file == OptionUploadFile::CLOUD && $request->url_file_path) {
            $fileBaseMedia = config('env.AZURE_STORAGE_URL') . "/" . AzureFolderEnum::MEDIA . "/";
            $arrUrl = explode($fileBaseMedia, $request->url_file_path);
            
            $orgirinalName = $arrUrl[1]; 

            $fileInfo->file_path = AzureFolderEnum::MEDIA . "/" . $orgirinalName;
            $fileInfo->file_name = $orgirinalName;
            $fileInfo->file_name_original = $orgirinalName;
        }
        $fileInfo->save();  

        return response()->json([
            'status' => 'OK',
        ], StatusCode::OK);
    }

    public function destroy($id)
    {

        try {
            $mediaList = DB::select("select preparation_name as media_name, '予習' as media_type from preparation where file_id = " . $id . "
            UNION select review_name as media_name, '復習' as media_type from review  where file_id = " . $id . "
            UNION select lesson_text_name  as media_name, '学習者用テキスト' as media_type from lesson_text where lesson_text_student_file_id = " . $id . "
            UNION select lesson_text_name  as media_name, '講師者用テキスト' as media_type from lesson_text where lesson_text_teacher_file_id = " . $id . "
            UNION select test_name as media_name, 'テスト問題' as media_type from test inner join test_question on test.test_id = test_question.test_id where file_id = " . $id . "
             UNION select test_name as media_name, 'テスト問題' as media_type from test inner join test_question on test.test_id = test_question.test_id inner join test_sub_question on test_sub_question.test_question_id =  test_question.test_question_id where test_sub_question.explanation_file_id = " . $id);

            if ($mediaList) {
                return response()->json([
                    'status' => 'NG',
                    'message' => '予習・復習・テスト設問・テキストに紐づいているため、削除できません。',
                ], StatusCode::OK);
            }
        
            $fileInfo = File::where('file_id', $id)->delete();

        } catch (ModelNotFoundException $ex) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::NOT_FOUND);
        }
        return response()->json([
            'status' => 'OK',
            'message' => 'メディア削除が完了しました。',
        ], StatusCode::OK);
    }
}
