<?php

namespace App\Http\Controllers;

use App\Components\BreadcrumbComponent;
use App\Enums\StatusCode;
use App\Http\Requests\StoreUpdatePreparationRequest;
use App\Models\Preparation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PreparationController extends BaseController
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
            ['name' => 'preparation_list']
        ]);
        $pageLimit = $this->newListLimit($request);
        $queryBuilder = new Preparation();

        if (isset($request['search_input'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('preparation_name', $request['search_input']))
                    ->orWhere('preparation_id', $request['search_input'])
                    ->orWhere($this->escapeLikeSentence('preparation_description', $request['search_input']));
            });
        }

        $preparationList = $queryBuilder->sortable(['display_order' => 'asc', 'preparation_name' => 'asc'])->paginate($pageLimit);

        return view('preparation.index', [
            'breadcrumbs' => $breadcrumbs,
            'request' => $request,
            'pageLimit' => $pageLimit,
            'preparationList' => $preparationList,
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
            ['name' => 'preparation_list'],
            ['name' => 'preparation_add']
        ]);

        return view('preparation.add', [
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUpdatePreparationRequest $request)
    {
        if($request->isMethod('POST')){
            DB::beginTransaction();
            try {
                $preparation = new Preparation();
                $preparation->display_order = $request->displayOrder;
                $preparation->preparation_name = $request->preparationName;
                $preparation->preparation_description = $request->preparationDescription;
                $preparation->save();
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'preparation_list'],
            ['name' => 'preparation_show', $id]
        ]);

        $preparation = Preparation::where('preparation_id', $id)->with('file')->first();
        if (!$preparation) return redirect()->route('preparation.index');
        return view('preparation.show', [
            'breadcrumbs' => $breadcrumbs,
            'preparation' => $preparation
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
            ['name' => 'preparation_list'],
            ['name' => 'preparation_show', $id],
            ['name' => 'preparation_edit', $id]
        ]);

        $preparation = Preparation::where('preparation_id', $id)->with('file')->first();
        if (!$preparation) return redirect()->route('preparation.index');
        return view('preparation.edit', [
            'breadcrumbs' => $breadcrumbs,
            'preparation' => $preparation
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreUpdatePreparationRequest $request, $id)
    {
        if($request->isMethod('PUT')){
            $preparation = Preparation::where('preparation_id', $id)->first();
            if (!$preparation) {
                return response()->json([
                    'status' => 'NOT_FOUND',
                ], StatusCode::NOT_FOUND);
            }

            DB::beginTransaction();
            try {
                $preparation->display_order = $request->displayOrder;
                $preparation->preparation_name = $request->preparationName;
                $preparation->preparation_description = $request->preparationDescription;

                $preparation->save();
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
        try {
            $preparation = Preparation::where('preparation_id', $id)->delete();

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
        ], StatusCode::OK);    }
}
