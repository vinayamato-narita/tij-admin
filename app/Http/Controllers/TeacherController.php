<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;

class TeacherController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbs = [
            '講師情報一覧'
        ];
        return view('teacher.index', [
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    public function getTeacherList(Request $request)
    {
        $orderBy = $request->input('column');
        $orderBy = ($orderBy == "display_order") ? "display_order" : $orderBy;
        $orderByDir = $request->input('dir', 'asc');
        $usersQuery = Teacher::query();
        switch ($orderByDir) {
            case 'asc':
                $usersQuery->orderBy($orderBy);
                break;
            case 'desc':
                $usersQuery->orderByDesc($orderBy);
                break;
        }
        return new DataTableCollectionResource($usersQuery->paginate(MaxPageSize::MAX_PAGE_SIZE, ['*'], 'page', $request->page));

    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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
