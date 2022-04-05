<?php

namespace App\Http\Controllers;

use App\Components\BreadcrumbComponent;
use App\Enums\RemindMailTimmingMinutesEnum;
use App\Enums\StatusCode;
use App\Models\SendRemindMailPattern;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SendRemindMailPatternInfo;
use App\Enums\LangType;
use App\Http\Requests\RemindMailLangRequest;

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
            'send_remind_mail_pattern_id' => $id,
        ])->with(['sendRemindMailTiming'])->first();

        $mailEnInfo = SendRemindMailPatternInfo::where(['send_remind_mail_pattern_id' => $id, 'lang_type' => LangType::EN])->first();
        $mailZhInfo = SendRemindMailPatternInfo::where(['send_remind_mail_pattern_id' => $id, 'lang_type' => LangType::ZH])->first();

        if (!$remindMail) return redirect()->route('SendRemindMailPattern.index');
        return view('SendRemindMailPattern.show', [
            'breadcrumbs' => $breadcrumbs,
            'remindMail' => $remindMail,
            'mailEnInfo' => $mailEnInfo,
            'mailZhInfo' => $mailZhInfo,
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
            'send_remind_mail_pattern_id' => $id,
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
            $remindMail = SendRemindMailPattern::where('send_remind_mail_pattern_id', $id)->first();
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

    public function editLang($id, $langType)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'remind_mail_list'],
            ['name' => 'remind_mail_show', $id],
            ['name' => 'edit_lang_remind_mail', $id, $langType],
        ]);
        $remindMail = SendRemindMailPattern::with(['sendRemindMailTiming'])->where('send_remind_mail_pattern_id', $id)->firstOrFail();
        
        $remindMailLang = SendRemindMailPatternInfo::where(['send_remind_mail_pattern_id' => $id, 'lang_type' => $langType])->first();

        $remindMail->_token = csrf_token();
        $remindMail->mail_lang_subject = $remindMailLang->mail_subject ?? "";
        $remindMail->mail_lang_body = $remindMailLang->mail_body ?? "";
        $remindMail->lang_type = $langType;
        $remindMail->title = $langType == LangType::EN ? "英語版" : "中国語版";

        return view('SendRemindMailPattern.edit_lang', [
            'breadcrumbs' => $breadcrumbs,
            'remindMail' => $remindMail,
        ]);
    }

    public function updateLang(RemindMailLangRequest $request)
    {
        if(!$request->isMethod('POST')){
            return response()->json([
                'status' => 'NG',
            ], StatusCode::BAD_REQUEST);  
        }
        $remindMail = SendRemindMailPattern::where('send_remind_mail_pattern_id', $request->send_remind_mail_pattern_id)->first();
        if ($remindMail == null) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::NOT_FOUND);
        }
        $remindMailLang = SendRemindMailPatternInfo::updateOrCreate(
            ['send_remind_mail_pattern_id' => $request->send_remind_mail_pattern_id, 'lang_type' => $request->lang_type],
            ['mail_subject' => $request->mail_lang_subject, 'mail_body' => $request->mail_lang_body]
        );

        return response()->json([
            'status' => 'OK',
        ], StatusCode::OK);
    }
}
