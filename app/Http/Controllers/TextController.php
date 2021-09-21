<?php

namespace App\Http\Controllers;

use App\Components\BreadcrumbComponent;
use App\Enums\StatusCode;
use App\Http\Requests\StoreUpdateTextRequest;
use App\Models\LessonText;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TextController extends BaseController
{
    public function index(Request $request)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'text_list']
        ]);
        $pageLimit = $this->newListLimit($request);
        $queryBuilder = new LessonText();

        if (isset($request['search_input'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('lesson_text_name', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('lesson_text_description', $request['search_input']));
            });
        }

        $lessonTextList = $queryBuilder->sortable(['lesson_text_no' => 'asc', 'lesson_text_name' => 'asc'])->paginate($pageLimit);

        return view('text.index', [
            'breadcrumbs' => $breadcrumbs,
            'request' => $request,
            'pageLimit' => $pageLimit,
            'lessonTextList' => $lessonTextList,
        ]);
    }

    public function destroy($id)
    {
        try {
            $text = LessonText::where('id', $id)->delete();

        } catch (ModelNotFoundException $ex) {
            return response()->json([
                'status' => 'NG',
                'data' => [],
            ], StatusCode::NOT_FOUND);
        }
        return response()->json([
            'status' => 'OK',
            'message' => ' テキストが削除されました',
            'data' => [],
        ], StatusCode::OK);
    }

    public function create()
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'text_list'],
            ['name' => 'text_add']
        ]);

        return view('text.add', [
            'breadcrumbs' => $breadcrumbs,
        ]);

    }

    public function store(StoreUpdateTextRequest $request)
    {
        if($request->isMethod('POST')){
            DB::beginTransaction();
            try {
                $lessonText = new LessonText();
                $lessonText->lesson_text_no = $request->lessonTextNo;
                $lessonText->lesson_text_url = $request->lessonTextUrl;
                $lessonText->lesson_text_url_for_teacher = $request->lessonTextUrlForTeacher;
                $lessonText->lesson_text_sound_url = $request->lessonTextSoundUrl;
                $lessonText->lesson_text_name = $request->lessonTextName;
                $lessonText->lesson_text_description = $request->lessonTextDescription;

                $lessonText->save();
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

    public function show($id)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'text_list'],
            ['name' => 'text_show', $id]
        ]);

        $lessonText = LessonText::where('id', $id)->with(['lesson'])->first();
        if (!$lessonText) return redirect()->route('text.index');
        return view('text.show', [
            'breadcrumbs' => $breadcrumbs,
            'lessonText' => $lessonText
        ]);
    }

    public function edit($id)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'text_list'],
            ['name' => 'text_show', $id],
            ['name' => 'text_edit', $id]
        ]);

        $lessonText = LessonText::where('id', $id)->first();
        if (!$lessonText) return redirect()->route('text.index');
        return view('text.edit', [
            'breadcrumbs' => $breadcrumbs,
            'lessonText' => $lessonText
        ]);
    }

    public function update(StoreUpdateTextRequest $request, $id)
    {
        if($request->isMethod('PUT')){
            $lessonText = LessonText::where('id', $id)->first();
            if (!$lessonText) {
                return response()->json([
                    'status' => 'NOT_FOUND',
                ], StatusCode::NOT_FOUND);
            }

            DB::beginTransaction();
            try {
                $lessonText->lesson_text_no = $request->lessonTextNo;
                $lessonText->lesson_text_url = $request->lessonTextUrl;
                $lessonText->lesson_text_url_for_teacher = $request->lessonTextUrlForTeacher;
                $lessonText->lesson_text_sound_url = $request->lessonTextSoundUrl;
                $lessonText->lesson_text_name = $request->lessonTextName;
                $lessonText->lesson_text_description = $request->lessonTextDescription;

                $lessonText->save();
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








}
