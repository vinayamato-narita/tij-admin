<?php

namespace App\Models;

use App\Enums\GroupLessonStatus;
use App\Enums\TestType;
use Carbon\Carbon;
use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Course extends Model
{
    use HasFactory, Sortable;

    protected $table = 'course';

    public $timestamps = false;
    protected $appends = ['publication_status'];

    public $sortable = ['publication_status', 'course_id', 'display_order', 'course_name', 'course_description', 'point_count', 'amount', 'group_lesson_status', 'min_reserve_count', 'max_reserve_count', 'decide_date', 'reserve_end_date', 'is_for_lms'];
    public function publicationStatusSortable(Builder $query, $direction)
    {
        return $query->selectRaw("course_id, display_order, course_name, course_name_short, course_description, point_count, amount, publish_date_to, publish_date_from,
        (CASE WHEN course.publish_date_from <= CURDATE() AND course.publish_date_to >= CURDATE() THEN 1 ELSE 0 END) AS publication_status_num")->orderBy('publication_status_num', $direction);
    }

    protected $primaryKey = 'course_id';
    public function childCourse()
    {
        return $this->belongsToMany(
            'App\Models\Course',
            'course_set_course',
            'set_course_id',
            'course_id',
            'course_id',
            'course_id'
        );
    }

    public function tags()
    {
        return $this->belongsToMany(
            'App\Models\Tag',
            'course_tags',
            'course_id',
            'tag_id',
            'course_id',
            'tag_id'
        );
    }

    public function campaigns()
    {
        return $this->hasMany('App\Models\CourseCampaign', 'course_id', 'course_id');
    }

    public function lesson()
    {
        return $this->belongsToMany(
            'App\Models\Lesson',
            'course_lesson',
            'course_id',
            'lesson_id',
            'course_id',
            'lesson_id'
        );
    }

    public function course_infos()
    {
        return $this->hasMany('App\Models\CourseInfo', 'course_id', 'course_id');
    }


    public function getSumAmountAttribute()
    {
        if (!$this->is_set_course) return $this->amount;
        return $this->childCourse()->sum('amount');
    }

    public function getPublicationStatusAttribute(): string
    {
        $status = '非公開';
        if (Carbon::parse($this->publish_date_from) <= Carbon::now() && Carbon::parse($this->publish_date_to) >= Carbon::now())
            $status = '公開中';
        return $status;
    }

    public function testAbilities()
    {
        return $this->belongsToMany(
            'App\Models\Test',
            'course_test',
            'course_id',
            'test_id',
            'course_id',
            'test_id'
        )->where('test_type', TestType::ABILITY);
    }

    public function testCourseEnds()
    {
        return $this->belongsToMany(
            'App\Models\Test',
            'course_test',
            'course_id',
            'test_id',
            'course_id',
            'test_id'
        )->where('test_type', TestType::ENDCOURSE);
    }

    public function studentPointHistories()
    {
        return $this->hasMany('App\Models\StudentPointHistory', 'course_id', 'course_id');
    }

    public function lessonSchedules()
    {
        return $this->hasMany('App\Models\LessonSchedule', 'course_id', 'course_id');
    }

//    public function getGroupLessonAttribute()
//    {
//        $today = Carbon::now();
//        $lessonDateArr = $this->lessonSchedules->pluck('lesson_date')->toArray();
//
//        $lessonMaxDate = '';
//        $lessonMinDate = '';
//        if (!empty($lessonDateArr)) {
//            $lessonMaxDate = max($lessonDateArr);
//            $lessonMinDate = min($lessonDateArr);
//        }
//
//        if ($this->group_lesson_status == GroupLessonStatus::BEFORE_DECIDE && Carbon::parse($this->publish_date_from) > $today) {
//            return '公開前';
//        } elseif ($this->group_lesson_status == GroupLessonStatus::BEFORE_DECIDE && Carbon::parse($this->publish_date_from) <= $today) {
//            return '公開中';
//        } elseif ($this->group_lesson_status == GroupLessonStatus::COURSE_DECIDE && $today < Carbon::parse($lessonMinDate) && count($lessonDateArr) != 0) {
//            return '開講決定';
//        } elseif ($this->group_lesson_status == GroupLessonStatus::COURSE_DECIDE && $today >= Carbon::parse($lessonMinDate) && count($lessonDateArr) != 0) {
//            return '開講中';
//        } elseif ($this->group_lesson_status == GroupLessonStatus::COURSE_DECIDE && $today >= Carbon::parse($lessonMaxDate) && count($lessonDateArr) != 0) {
//            return '終了';
//        } elseif ($this->group_lesson_status == GroupLessonStatus::COURSE_DECIDE && count($lessonDateArr) == 0) {
//            return '---';
//        } elseif ($this->group_lesson_status == GroupLessonStatus::CANCEL) {
//            return '不成立';
//        }
//    }

    public function getGroupLessonAttribute()
    {
        $now = Carbon::now();

        if ($this->group_lesson_status  == GroupLessonStatus::BEFORE_DECIDE) {
            if ($this->publish_date_from > $now) return '公開前';
            else return '公開中';

        }

        if ($this->group_lesson_status  == GroupLessonStatus::COURSE_DECIDE) {
            if ($this->course_start_date > $now) return '開講決定';
            if ($this->publish_date_from < $now) return '終了';
            else return '開講中';

        }

        if ($this->group_lesson_status  == GroupLessonStatus::CANCEL) return '不成立';
        return '---';
    }

    protected $sortableAs = ['student_point_histories_count', 'point_subscription_histories_count'];

    public function pointSubscriptionHistories()
    {
        return $this->hasMany('App\Models\PointSubscriptionHistory', 'course_id', 'course_id')
            ->where(function ($q) {
                $q->where('del_flag', NULL)->orWhere('del_flag', 0);
            });
    }
}
