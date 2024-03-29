<?php

namespace App\Http\Controllers;

use App\Components\BreadcrumbComponent;
use App\Enums\CourseTypeEnum;
use App\Enums\StatusCode;
use App\Enums\TestType;
use App\Http\Requests\CourseLangRequest;
use App\Http\Requests\CourseRegisterVideoRequest;
use App\Http\Requests\StoreUpdateCourseRequest;
use App\Http\Requests\StoreUpdateCourseSetRequest;
use App\Models\Course;
use App\Models\CourseCampaign;
use App\Models\CourseInfo;
use App\Models\CourseLesson;
use App\Models\CourseSetCourse;
use App\Models\CourseTag;
use App\Models\CourseTest;
use App\Models\CourseVideo;
use App\Models\Lesson;
use App\Models\LessonSchedule;
use App\Models\PointSubscriptionHistory;
use App\Models\Tag;
use App\Models\Test;
use Carbon\Carbon;
use function Doctrine\Common\Cache\Psr6\get;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Validator;
use App\Models\CourseGroupMemo;

class CourseController extends BaseController
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
            ['name' => 'course_list']
        ]);
        $pageLimit = $this->newListLimit($request);
        $queryBuilder = new Course();

        if (!empty($request['search_input'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('course_name', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('course_name_short', $request['search_input']))
                    ->orWhere($this->escapeLikeSentence('course_description', $request['search_input']));
            });
        }

        if (!empty($request['course_id'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where('course_id', $request['course_id']);
            });
        }

        if (!empty($request['course_name'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('course_name', $request['course_name']));
            });
        }


        if (!empty($request['paypal_item_number'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where('paypal_item_number', $request['paypal_item_number']);
            });
        }
        if (isset($request['sort'])) {
            if ($request['sort'] == "point_count") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderByRaw('CAST(point_count AS INT) ASC') : $queryBuilder->orderByRaw('CAST(point_count AS INT) DESC');
            }
        }

        if (isset($request['sort'])) {
            if ($request['sort'] == "amount") {
                $queryBuilder = $request['direction'] == "asc" ? $queryBuilder->orderByRaw('CAST(amount AS INT) ASC') : $queryBuilder->orderByRaw('CAST(amount AS INT) DESC');
            }
        }


        $coureList = $queryBuilder->with(['childCourse'])->sortable(['course_name' => 'desc'])->paginate($pageLimit);

        return view('course.courseIndex', [
            'breadcrumbs' => $breadcrumbs,
            'request' => $request,
            'pageLimit' => $pageLimit,
            'courseList' => $coureList,
        ]);
    }

    public function destroy($id) {
        $course = Course::where('course_id', $id)->first();
        if ($course == null) {
            return response()->json([
                'status' => 'NG',
            ], StatusCode::OK);
        }

        if (CourseLesson::where('course_id', $id)->exists() ||
            PointSubscriptionHistory::where('course_id', $id)->exists() ||
            LessonSchedule::where('course_id', $id)->exists())
            return response()->json([
                'status' => 'NG',
                'message' => 'コースが既に使用されているため、削除できません。',
            ], StatusCode::OK);



        try {
            $course = Course::where('course_id', $id)->delete();

        } catch (ModelNotFoundException $ex) {
            return response()->json([
                'status' => 'NG',
                'data' => [],
            ], StatusCode::NOT_FOUND);
        }
        return response()->json([
            'status' => 'OK',
            'message' => 'コース削除が完了しました。',
            'data' => [],
        ], StatusCode::OK);

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
            ['name' => 'course_list'],
            ['name' => 'course_add']
        ]);


        return view('course.create', [
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUpdateCourseRequest $request)
    {
        if ($request->reverseStart && $request->reverseStart < $request->reverseEnd) {
            return response()->json([
                'status' => 'UNPROCESSABLE_ENTITY',
            ], StatusCode::UNPROCESSABLE_ENTITY);
        }
        if ($request->isMethod('POST')) {
            DB::beginTransaction();
            try {
                $course = new Course();
                $course->publish_date_from = $request->fromDate;
                $course->publish_date_to = $request->toDate;
                $course->display_order = $request->displayOrder;
                $course->course_name_short = $request->courseNameShort ?? ' ';
                $course->course_name = $request->courseName;
                $course->point_count = $request->pointCount;
                $course->amount = $request->amount;
                $course->paypal_item_number = $request->paypalItemNumber;
                $course->course_description = $request->courseDescription ?? ' ';
                $course->course_target = $request->courseTarget ?? ' ';
                $course->course_attainment_target = $request->courseAttainmentTarget ?? ' ';
                $course->is_for_lms = $request->isForLMS;
                $course->course_type = $request->courseType;
                if (in_array($request->courseType, [CourseTypeEnum::REGULAR_COURSE, CourseTypeEnum::ABILITY_TEST_COURSE])) {
                    $course->expire_day = $request->expireDay ?? 1;
                }
                if ($request->courseType == CourseTypeEnum::GROUP_COURSE) {
                    $course->min_reserve_count = $request->minReserveCount;
                    $course->max_reserve_count = $request->maxReserveCount;
                    $course->decide_date = $request->decideDate;
                    $course->reserve_end_date = $request->reverseEndDate;
                    $course->course_start_date = $request->courseStartDate;
                }

                if ($course->is_for_lms == 1) {
                    $course->course_type = 1;
                    $course->group_lesson_status =1;
                }


                $course->save();

                DB::commit();
                return response()->json([
                    'course_id' => $course->course_id,
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

    public function courseSetCreate()
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'course_list'],
            ['name' => 'course_set_add']
        ]);


        return view('course.setCreate', [
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    public function getCourse($id)
    {
        if (is_numeric($id)) {
            $course = Course::where([
                'is_set_course' => false,
                'course_id' => $id
            ])->first();
            if (!$course)
                return response()->json([
                    'status' => 'NOT_FOUND',
                ], StatusCode::NOT_FOUND);
            return response()->json([
                'status' => 'OK',
                'data' => $course
            ], StatusCode::OK);
        }
        return response()->json([
            'status' => 'NOT_FOUND',
        ], StatusCode::NOT_FOUND);
    }

    public function courseSetStore(StoreUpdateCourseSetRequest $request)
    {
        if ($request->isMethod('POST')) {
            DB::beginTransaction();
            try {
                $course = new Course();
                $course->display_order = $request->displayOrder;
                $course->is_show = $request->isShow == true ? 1 : 0;
                $course->course_name_short = $request->courseNameShort;
                $course->course_name = $request->courseName;
                $course->amount = $request->amount;
                $course->is_campaign = $request->isCampaign == true ? 1 : 0;
                $course->campaign_code = $request->campaignCode;
                $course->course_description = $request->courseDescription;
                $course->is_for_lms = $request->isForLMS == true ? 1 : 0;
                $course->is_set_course = true;
                $course->point_count = '';
                $course->point_expire_day = 0;
                $course->paypal_item_number = '';
                $course->max_reserve_count = 0;


                $course->save();

                if (isset($request->tagIds)) {
                    $arrTagId = explode(',', $request->tagIds);
                    foreach ($arrTagId as $id) {
                        $ct = new  CourseTag();
                        $ct->course_id = $course->course_id;
                        $ct->tag_id = $id;
                        $ct->save();
                    }
                }

                if (isset($request->childIds)) {
                    $arrchildIds = explode(',', $request->childIds);
                    foreach ($arrchildIds as $id) {
                        $ct = new  CourseSetCourse();
                        $ct->set_course_id = $course->course_id;
                        $ct->course_id = $id;
                        $ct->save();
                    }
                }


                DB::commit();
                return response()->json([
                    'course_id' => $course->course_id,
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

    public function lessonDelete($id, $lessonId)
    {

        try {
            $cl = CourseLesson::where([
                'course_id' => $id,
                'lesson_id' => $lessonId
            ])->delete();

        } catch (ModelNotFoundException $ex) {
            return response()->json([
                'status' => 'NG',
                'data' => [],
            ], StatusCode::NOT_FOUND);
        }
        return response()->json([
            'status' => 'OK',
            'message' => ' レッスンの解除が完了しました。',
            'data' => [],
        ], StatusCode::OK);

    }

    public function videoDelete($id, $videoId)
    {

        try {
            $cl = CourseVideo::where([
                'course_video_id' => $videoId
            ])->delete();

        } catch (ModelNotFoundException $ex) {
            return response()->json([
                'status' => 'NG',
                'data' => [],
            ], StatusCode::NOT_FOUND);
        }
        return response()->json([
            'status' => 'OK',
            'message' => ' 動画の解除が完了しました。',
            'data' => [],
        ], StatusCode::OK);

    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'course_list'],
            ['name' => 'course_show', $id]
        ]);

        $course = Course::where([
            'course_id' => $id,
        ])->with(['lesson', 'course_infos', 'lesson.courseLesson', 'testAbilities', 'testCourseEnds', 'campaigns'])->first();

        if($course['course_type']==2 && !empty($course['testAbilities'])){

          foreach ($course['testAbilities'] as $key => $value){
              if($key != 0){
                  unset($course['testAbilities'][$key]);
              }
          }
        }

        $courseVideo = CourseVideo::where('course_id', $id)->get();
        if (!$course) return redirect()->route('course.index');
        $course->lesson = $course->lesson()->with('courseLesson')->orderBy('course_lesson.display_order', 'asc')->get();
        $course->setRelation('lesson', $course->lesson()->with('courseLesson')->orderBy('course_lesson.display_order', 'asc')->get());

        $courseGroupMemo = CourseGroupMemo::where('course_id', $id)->first();
        if ($courseGroupMemo == null) {
            $courseGroupMemo['course_id'] = $id;
            $courseGroupMemo['memo'] = '';
        }

        return view('course.show', [
            'breadcrumbs' => $breadcrumbs,
            'course' => $course,
            'courseVideo' => $courseVideo,
            'courseGroupMemo' => $courseGroupMemo,
        ]);
    }

    public function editLang($id, $langType)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'course_list'],
            ['name' => 'course_show', $id],
            ['name' => 'edit_lang_course', $id, $langType],
        ]);
        $course = Course::where('course_id', $id)->first();
        if (!$course) return redirect()->route('course.index');
        $courseInfo = CourseInfo::where(['course_id' => $id, 'lang_type' => $langType])->first();

        return view('course.edit_lang', [
            'breadcrumbs' => $breadcrumbs,
            'courseInfo' => $courseInfo,
            'course' => $course,
            'lang' => $langType
        ]);
    }

    public function updateLang(CourseLangRequest $request)
    {
        if (!$request->isMethod('POST')) {
            return response()->json([
                'status' => 'OK',
            ], StatusCode::BAD_REQUEST);
        }

        $course = Course::where('course_id', $request->course_id)->first();
        if ($course == null) {
            return response()->json([
                'status' => 'OK',
            ], StatusCode::NOT_FOUND);
        }
/*        $courseLangInfo = CourseInfo::updateOrCreate(
            [
                'course_id' => $request->course_id,
                'lang_type' => $request->lang,
                'course_name' => $request->course_name,
                'course_description' => $request->course_description,
                'course_target' => $request->course_target,
                'course_attainment_target' => $request->course_attainment_target
            ]
        );*/

        $courseLangInfo = CourseInfo::updateOrCreate(
            ['course_id' => $request->course_id, 'lang_type' => $request->lang],
            ['course_name' => $request->course_name, 'course_description' => $request->course_description,                 'course_target' => $request->course_target,
                'course_attainment_target' => $request->course_attainment_target]
        );


        return response()->json([
            'status' => 'OK',
        ], StatusCode::OK);
    }

    public function campaignCreate($id)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'course_list'],
            ['name' => 'course_show', $id],
            ['name' => 'course_campaign_create', $id]
        ]);
        $course = Course::where([
            'course_id' => $id,
        ])->first();
        if (!$course) return redirect()->route('course.index');
        return view('course.add_campaign', [
            'breadcrumbs' => $breadcrumbs,
            'course' => $course,
        ]);
    }

    public function existCampaignDatetime(Request $request, $courseId)
    {
        $startDate = Carbon::parse($request->startDate);
        $endDate = Carbon::parse($request->endDate);
        $valid = !CourseCampaign::where([
            'course_id' => $courseId,
        ])->where(function ($query) use ($startDate, $endDate) {
            return $query->whereBetween('campaign_start_time', [$startDate, $endDate])
                ->orWhereBetween('campaign_end_time', [$startDate, $endDate])
                ->orWhere(function ($query) use ($startDate, $endDate) {
                    return $query->where('campaign_start_time', '<=', $startDate)->where('campaign_end_time', '>=', $endDate);
                });
        })->exists();

        return response()->json([
            'valid' => $valid,
        ], StatusCode::OK);
    }

    public function campaignDestroy($courseId, $campaignId)
    {
        try {
            $campaign = CourseCampaign::where([
                'course_id' => $courseId,
                'course_campaign_id' => $campaignId
            ])->delete();

        } catch (\Exception $ex) {
            return response()->json([
                'status' => 'NG',
                'data' => [],
            ], StatusCode::NOT_FOUND);
        }
        return response()->json([
            'status' => 'OK',
            'message' => ' キャンペーンの解除が完了しました。',
            'data' => [],
        ], StatusCode::OK);
    }

    public function addCampaignStore(Request $request, $courseId)
    {
        $campaign = new CourseCampaign();
        $campaign->course_id = $courseId;
        $campaign->price = $request->price;
        $campaign->campaign_start_time = Carbon::parse($request->startDate)->format('Y-m-d H:i:00');
        $campaign->campaign_end_time = Carbon::parse($request->endDate)->format('Y-m-d H:i:59');
        $ret = $campaign->save();
        if ($ret) {
            return response()->json([
                'status' => 'OK',
            ], StatusCode::OK);
        }
        return response()->json([
            'status' => 'INTERNAL_ERR',
        ], StatusCode::INTERNAL_ERR);

    }


    public function registerVideo(CourseRegisterVideoRequest $request, $id)
    {

        DB::beginTransaction();
        try {

            $cv = new CourseVideo();
            $cv->course_id = $id;
            $cv->video_name = $request->videoName;
            $cv->image_url = $request->imageUrl;
            $cv->video_url = $request->videoUrl;

            $cv->save();
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 'INTERNAL_ERR',
            ], StatusCode::INTERNAL_ERR);
        }

        DB::commit();
        return response()->json([
            'status' => 'OK',
        ], StatusCode::OK);
    }

    public function setShow($id)
    {

        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'course_list'],
            ['name' => 'course_set_show', $id]
        ]);

        $course = Course::where([
            'course_id' => $id,
            'is_set_course' => true
        ])->with(['childCourse', 'tags', 'lesson'])->first();
        if (!$course) return redirect()->route('course.index');
        return view('course.setShow', [
            'breadcrumbs' => $breadcrumbs,
            'course' => $course
        ]);
    }

    public function registerLesson(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            foreach ($request->all() as $rq) {
                $tc = new CourseLesson();
                $tc->lesson_id = $rq;
                $tc->course_id = $id;
                $tc->display_order = CourseLesson::where('course_id', $id)->max('display_order') + 1;
                $tc->save();
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 'INTERNAL_ERR',
            ], StatusCode::INTERNAL_ERR);
        }

        DB::commit();
        return response()->json([
            'status' => 'OK',
        ], StatusCode::OK);
    }

    public function lesson(Request $request, $id)
    {
        $pageLimit = $this->newListLimit($request);
        $queryBuilder = new Lesson();

        if (isset($request['inputSearch'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('lesson_name', $request['inputSearch']));
            });
        }
        $lessonHasAdded = CourseLesson::where('course_id', $id)->pluck('lesson_id');

        $lessonList = $queryBuilder->whereNotIn('lesson_id', $lessonHasAdded)->sortable(['display_order' => 'asc', 'lesson_name' => 'asc'])->paginate($pageLimit);
        return response()->json([
            'status' => 'OK',
            'dataList' => $lessonList
        ], StatusCode::OK);

    }

    public function lessonAttach(Request $request, $id)
    {
        $queryBuilder = new CourseLesson();

        $lessonList = $queryBuilder::with('lesson')
            ->where('course_id', $id)
            ->whereHas('lesson')
            ->sortable(['display_order' => 'asc'])->get()->toArray();
        return response()->json([
            'status' => 'OK',
            'dataList' => $lessonList
        ], StatusCode::OK);

    }

    public function lessonAttachUpdate(Request $request, $id)
    {
        if (empty($request->all())) {
            return response()->json([
                'status' => 'OK',
            ], StatusCode::OK);
        }


        $display_order = 0;
        $ret = true;
        DB::beginTransaction();
        foreach ($request->all() as $cLId) {
            $courseLesson = CourseLesson::where([
                'course_id' => $id,
                'course_lesson_id' => $cLId
            ])->first();
            if (!$courseLesson) {
                DB::rollBack();
                return response()->json([
                    'status' => 'INTERNAL_ERR',
                ], StatusCode::INTERNAL_ERR);
            }
            $display_order++;
            $courseLesson->display_order = $display_order;
            $ret = $courseLesson->save();
        }
        if (!$ret) {
            DB::rollBack();
            return response()->json([
                'status' => 'INTERNAL_ERR',
            ], StatusCode::INTERNAL_ERR);
        }
        DB::commit();


        return response()->json([
            'status' => 'OK',
        ], StatusCode::OK);

    }

    public function testAbility(Request $request, $id)
    {
        $pageLimit = $this->newListLimit($request);
        $queryBuilder = new Test();

        if (isset($request['inputSearch'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('test_name', $request['inputSearch']));
            });
        }
        $testHasAdded = CourseTest::where('course_id', $id)->pluck('test_id');

        $testList = $queryBuilder->where('test_type', TestType::ABILITY)->whereNotIn('test_id', $testHasAdded)->sortable(['display_order' => 'asc', 'test_name' => 'asc'])->paginate($pageLimit);
        return response()->json([
            'status' => 'OK',
            'dataList' => $testList
        ], StatusCode::OK);
    }

    public function testAbilityUpdate(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            foreach ($request->all() as $rq) {
                $tc = new CourseTest();
                $tc->test_id = $rq;
                $tc->course_id = $id;
                $tc->save();
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 'INTERNAL_ERR',
            ], StatusCode::INTERNAL_ERR);
        }

        DB::commit();
        return response()->json([
            'status' => 'OK',
        ], StatusCode::OK);
    }

    public function testDelete($id, $testId)
    {

        try {
            $cl = CourseTest::where([
                'course_id' => $id,
                'test_id' => $testId
            ])->delete();

        } catch (ModelNotFoundException $ex) {
            return response()->json([
                'status' => 'NG',
                'data' => [],
            ], StatusCode::NOT_FOUND);
        }
        return response()->json([
            'status' => 'OK',
            'message' => ' テストの解除が完了しました。',
            'data' => [],
        ], StatusCode::OK);

    }

    public function testCourseEnd(Request $request, $id)
    {
        $pageLimit = $this->newListLimit($request);
        $queryBuilder = new Test();

        if (isset($request['inputSearch'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('test_name', $request['inputSearch']));
            });
        }
        $testHasAdded = CourseTest::where('course_id', $id)->pluck('test_id');

        $testList = $queryBuilder->where('test_type', TestType::ENDCOURSE)->whereNotIn('test_id', $testHasAdded)->sortable(['display_order' => 'asc', 'test_name' => 'asc'])->paginate($pageLimit);
        return response()->json([
            'status' => 'OK',
            'dataList' => $testList
        ], StatusCode::OK);
    }

    public function testCourseEndUpdate(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            foreach ($request->all() as $rq) {
                $tc = new CourseTest();
                $tc->test_id = $rq;
                $tc->course_id = $id;
                $tc->save();
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'status' => 'INTERNAL_ERR',
            ], StatusCode::INTERNAL_ERR);
        }

        DB::commit();
        return response()->json([
            'status' => 'OK',
        ], StatusCode::OK);
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'course_list'],
            ['name' => 'course_show', $id],
            ['name' => 'course_edit', $id]
        ]);

        $course = Course::where([
            'course_id' => $id,
        ])->first();
        if (!$course) return redirect()->route('course.index');
        $courseBought = PointSubscriptionHistory::where(['course_id' => $id,
            'payment_status' => 1])->exists();
        return view('course.edit', [
            'breadcrumbs' => $breadcrumbs,
            'course' => $course,
            'courseBought' => $courseBought
        ]);
    }

    public function setEdit($id)
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'course_list'],
            ['name' => 'course_set_show', $id],
            ['name' => 'course_set_edit', $id]
        ]);
        $tag = Tag::all()->toArray();

        $course = Course::where([
            'course_id' => $id,
            'is_set_course' => true
        ])->with(['tags'])->first();
        if (!$course) return redirect()->route('course.index');
        return view('course.setEdit', [
            'breadcrumbs' => $breadcrumbs,
            'course' => $course,
            'tags' => $tag
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreUpdateCourseRequest $request, $id)
    {
        if ($request->reverseStart && $request->reverseStart < $request->reverseEnd) {
            return response()->json([
                'status' => 'UNPROCESSABLE_ENTITY',
            ], StatusCode::UNPROCESSABLE_ENTITY);
        }
        $course = Course::where([
            'course_id' => $id,
        ])->first();
        if (!$course)
            return response()->json([
                'status' => 'NOT_FOUND',
            ], StatusCode::NOT_FOUND);

        if ($request->isMethod('PUT')) {
            DB::beginTransaction();
            try {
                $course->publish_date_from = $request->fromDate;
                $course->publish_date_to = $request->toDate;
                $course->display_order = $request->displayOrder;
                $course->course_name_short = $request->courseNameShort ?? ' ';
                $course->course_name = $request->courseName;
                $course->point_count = $request->pointCount;
                $course->amount = $request->amount;
                $course->paypal_item_number = $request->paypalItemNumber;
                $course->course_description = $request->courseDescription ?? ' ';
                $course->course_target = $request->courseTarget ?? ' ';
                $course->course_attainment_target = $request->courseAttainmentTarget ?? ' ';
                if (!PointSubscriptionHistory::where([
                    'course_id' => $id,
                    'payment_status' => 1])->exists())
                    $course->is_for_lms = $request->isForLMS;
                $course->course_type = $request->courseType;
                if (in_array($request->courseType, [CourseTypeEnum::REGULAR_COURSE, CourseTypeEnum::ABILITY_TEST_COURSE])) {
                    $course->expire_day = $request->expireDay ?? 1;
                }
                if ($request->courseType == CourseTypeEnum::GROUP_COURSE) {
                    $course->min_reserve_count = $request->minReserveCount;
                    $course->max_reserve_count = $request->maxReserveCount;
                    $course->decide_date = $request->decideDate;
                    $course->reserve_end_date = $request->reverseEndDate;
                    $course->course_start_date = $request->courseStartDate;
                }

                $course->save();

                DB::commit();
                return response()->json([
                    'status' => 'OK',
                ], StatusCode::OK);
            } catch (\Exception $exception) {
                Log::info($exception->getMessage());
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

    public function setUpdate(StoreUpdateCourseSetRequest $request, $id)
    {
        $course = Course::where([
            'course_id' => $id,
            'is_set_course' => true
        ])->first();
        if (!$course)
            return response()->json([
                'status' => 'NOT_FOUND',
            ], StatusCode::NOT_FOUND);
        if ($request->isMethod('POST')) {
            DB::beginTransaction();
            try {
                $course->display_order = $request->displayOrder;
                $course->is_show = $request->isShow == true ? 1 : 0;
                $course->course_name_short = $request->courseNameShort == 'null' ? '' : $request->courseNameShort;
                $course->course_name = $request->courseName;
                $course->amount = $request->amount;
                $course->is_campaign = $request->isCampaign == true ? 1 : 0;
                $course->campaign_code = $request->campaignCode == 'null' ? '' : $request->campaignCode;
                $course->course_description = $request->courseDescription == 'null' ? '' : $request->courseDescription;
                $course->is_for_lms = $request->isForLMS == true ? 1 : 0;
                $course->is_set_course = true;
                $course->point_count = '';
                $course->point_expire_day = 0;
                $course->paypal_item_number = '';
                $course->max_reserve_count = 0;


                $course->save();
                CourseTag::where([
                    'course_id' => $id,
                ])->delete();

                if (isset($request->tagIds)) {
                    $arrTagId = explode(',', $request->tagIds);
                    foreach ($arrTagId as $id) {
                        $ct = new  CourseTag();
                        $ct->course_id = $course->course_id;
                        $ct->tag_id = $id;
                        $ct->save();
                    }
                }


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

    public function updateGroupMemo(Request $request)
    {
        if (!$request->isMethod('POST')) {
            return response()->json([
                'status' => 'OK',
            ], StatusCode::BAD_REQUEST);
        }

        $courseGroupMemo = CourseGroupMemo::updateOrCreate(
            ['course_id' => $request->course_id],
            ['memo' => $request->memo, 'last_update_date' => Carbon::now()]
        );
        return response()->json([
            'status' => 'OK',
        ], StatusCode::OK);
    }
}
