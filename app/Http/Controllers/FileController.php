<?php

namespace App\Http\Controllers;

use App\Enums\StatusCode;
use App\Models\File;
use App\Models\Preparation;
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

        if (isset($request->preparationId)) {
            $preparation = Preparation::find($request->preparationId);
            $selected = File::where('file_id', '=', $preparation->file_id);

            $later = File::where('file_id', '!=', $preparation->file_id)->sortable(['file_id' => 'asc']);

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
