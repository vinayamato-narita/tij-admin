<?php

namespace App\Http\Controllers;

use App\Components\BreadcrumbComponent;
use App\Enums\StatusCode;
use App\Http\Requests\CourseRegisterVideoRequest;
use App\Http\Requests\StoreUpdateCourseRequest;
use App\Http\Requests\StoreUpdateCourseSetRequest;
use App\Models\Course;
use App\Models\CourseLesson;
use App\Models\CourseSetCourse;
use App\Models\CourseTag;
use App\Models\CourseVideo;
use App\Models\Lesson;
use App\Models\Tag;
use function Doctrine\Common\Cache\Psr6\get;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;

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

        if (!empty($request['campaign_code'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where($this->escapeLikeSentence('campaign_code', $request['campaign_code']));
            });
        }

        if (!empty($request['paypal_item_number'])) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where('paypal_item_number', $request['paypal_item_number']);
            });
        }

        if (!empty($request['is_show']) && $request['is_show'] != 2) {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where('is_show', $request['is_show']);
            });
        }

        $coureList = $queryBuilder->with(['childCourse'])->sortable(['course_name' => 'desc'])->paginate($pageLimit);

        return view('course.index', [
            'breadcrumbs' => $breadcrumbs,
            'request' => $request,
            'pageLimit' => $pageLimit,
            'courseList' => $coureList,
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
            ['name' => 'course_list'],
            ['name' => 'course_add']
        ]);


        $tag = Tag::all()->toArray();
        return view('course.create', [
            'breadcrumbs' => $breadcrumbs,
            'tag' => $tag
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUpdateCourseRequest $request)
    {
        if ($request->reverseStart && $request->reverseStart < $request->reverseEnd) {
            return response()->json([
                'status' => 'UNPROCESSABLE_ENTITY',
            ], StatusCode::UNPROCESSABLE_ENTITY);
        }
        if($request->isMethod('POST')){
            DB::beginTransaction();
            try {
                $course = new Course();
                $course->display_order = $request->displayOrder;
                $course->is_show = $request->isShow == true ? 1 : 0;
                $course->course_name_short = $request->courseNameShort;
                $course->course_name = $request->courseName;
                $course->point_expire_day = $request->pointExpireDay;
                $course->point_count = $request->pointCount;
                $course->max_reserve_count = $request->maxReverseCount;
                $course->amount = $request->amount;
                $course->paypal_item_number = $request->paypalItemNumber == true ? 1 : 0;
                $course->is_campaign = $request->isCampaign == true ? 1 : 0;
                $course->campaign_code = $request->campaignCode;
                $course->course_description = $request->courseDescription;
                $course->is_schedule_limit = $request->isScheduleLimit == true ? 1 : 0;
                $course->reserve_end = $request->reverseEnd;
                $course->reserve_start = $request->reverseStart;
                $course->cancel_end = $request->cancelEnd;
                $course->is_for_lms = $request->isForLMS == true ? 1 : 0;
                $course->is_set_course = false;

                $course->save();

                if (isset($request->tagIds)) {
                    $arrTagId = explode(',', $request->tagIds);
                    foreach ($arrTagId as $id) {
                        $ct = new  CourseTag();
                        $ct->course_id = $course->id;
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

    public function courseSetCreate()
    {
        $breadcrumbComponent = new BreadcrumbComponent();
        $breadcrumbs = $breadcrumbComponent->generateBreadcrumb([
            ['name' => 'course_list'],
            ['name' => 'course_set_add']
        ]);


        $tag = Tag::all()->toArray();
        return view('course.setCreate', [
            'breadcrumbs' => $breadcrumbs,
            'tag' => $tag
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
        if($request->isMethod('POST')){
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
                        $ct->course_id = $course->id;
                        $ct->tag_id = $id;
                        $ct->save();
                    }
                }

                if (isset($request->childIds)) {
                    $arrchildIds = explode(',', $request->childIds);
                    foreach ($arrchildIds as $id) {
                        $ct = new  CourseSetCourse();
                        $ct->set_course_id = $course->id;
                        $ct->course_id = $id;
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
                'id' => $videoId
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
     * @param  int  $id
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
            'is_set_course' => false
        ])->with(['childCourse', 'tags', 'lesson'])->first();

        $courseVideo = CourseVideo::where('course_id', $id)->get();
        if (!$course) return redirect()->route('course.index');
        return view('course.show', [
            'breadcrumbs' => $breadcrumbs,
            'course' => $course,
            'courseVideo' => $courseVideo
        ]);
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
        }
        catch (\Exception $exception) {
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

    public function registerLesson(Request $request, $id) {
        DB::beginTransaction();
        try {
            foreach ($request->all() as $rq) {
                $tc = new CourseLesson();
                $tc->lesson_id = $rq;
                $tc->course_id = $id;
                $tc->save();
            }
        }
        catch (\Exception $exception) {
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

        $lessonList = $queryBuilder->whereNotIn('id', $lessonHasAdded)->sortable(['display_order' => 'asc', 'lesson_name' => 'asc'])->paginate($pageLimit);
        return response()->json([
            'status' => 'OK',
            'dataList' => $lessonList
        ], StatusCode::OK);

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
