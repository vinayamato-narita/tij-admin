<?php

namespace App\Http\Controllers;

use App\Components\BreadcrumbComponent;
use App\Enums\RemindMailTimmingMinutesEnum;
use App\Enums\StatusCode;
use App\Models\SendRemindMailPattern;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RemindMailController extends BaseController
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
            ['name' => 'remind_mail_list']
        ]);
        $pageLimit = $this->newListLimit($request);
        $queryBuilder = SendRemindMailPattern::with(['sendRemindMailTiming']);

        if (isset($request['search_input'])) {
            $queryBuilder = $queryBuilder->orWhere(function ($query) use ($request) {
                $query->whereHas('sendRemindMailTiming', function ($query) use ($request) {
                    $query->where($this->escapeLikeSentence('send_remind_mail_timing_type_name', $request['search_input']));
                });
            });
        }

        $remindMailList = $queryBuilder->sortable(['sendRemindMailTiming.send_remind_mail_timing_type_name' => 'desc'])->paginate($pageLimit);

        return view('SendRemindMailPattern.index', [
            'breadcrumbs' => $breadcrumbs,
            'request' => $request,
            'pageLimit' => $pageLimit,
            'remindMailList' => $remindMailList,
        ]);
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
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'remind_mail_list'],
            ['name' => 'remind_mail_show', $id]
        ]);

        $remindMail = SendRemindMailPattern::where([
            'id' => $id,
        ])->with(['sendRemindMailTiming'])->first();

        if (!$remindMail) return redirect()->route('SendRemindMailPattern.index');
        return view('SendRemindMailPattern.show', [
            'breadcrumbs' => $breadcrumbs,
            'remindMail' => $remindMail
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
            ['name' => 'remind_mail_list'],
            ['name' => 'remind_mail_show', $id],
            ['name' => 'remind_mail_edit', $id]
        ]);

        $remindMail = SendRemindMailPattern::where([
            'id' => $id,
        ])->with(['sendRemindMailTiming'])->first();
        $enum = RemindMailTimmingMinutesEnum::getValues();

        if (!$remindMail) return redirect()->route('SendRemindMailPattern.index');
        return view('SendRemindMailPattern.edit', [
            'breadcrumbs' => $breadcrumbs,
            'remindMail' => $remindMail,
            'enum' => $enum
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
            $remindMail = SendRemindMailPattern::where('id', $id)->first();
            if (!$remindMail) {
                return response()->json([
                    'status' => 'NOT_FOUND',
                ], StatusCode::NOT_FOUND);
            }

            DB::beginTransaction();
            try {
                $remindMail->timing_minutes = $request->timingMinutes;
                $remindMail->mail_subject = $request->mailSubject;
                $remindMail->mail_body = $request->mailBody;

                $remindMail->save();
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
