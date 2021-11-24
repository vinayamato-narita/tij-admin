<?php

namespace App\Http\Controllers;

use App\Enums\StatusCode;
use App\Models\File;
use App\Models\Preparation;
use App\Models\Review;
use Illuminate\Http\Request;

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

        if (isset($request->preparationId) || isset($request->reviewId)) {
            if (isset($request->preparationId))
                $targetObject = Preparation::find($request->preparationId);
            if (isset($request->reviewId))
                $targetObject = Review::find($request->reviewId);

            $selected = File::where('file_id', '=', $targetObject->file_id);
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
}
