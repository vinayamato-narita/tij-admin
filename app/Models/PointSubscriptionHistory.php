<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Support\Facades\DB;
use Log;

class PointSubscriptionHistory extends Authenticatable
{
    use HasFactory, Sortable;

    protected $table = 'point_subscription_history';

    public $timestamps = false;

    protected $primaryKey = 'point_subscription_history_id';

    public function studentPointHistory()
    {
        return $this->hasMany(StudentPointHistory::class);
    }

    public static function getPaymentHistoryInfo($id)
    {
        return DB::table('point_subscription_history')->select(
            'point_subscription_history.point_subscription_history_id as point_subscription_history_id',
            'point_subscription_history.student_id as student_id',
            'point_subscription_history.point_count as point_count',
            'point_subscription_history.management_number as management_number',
            DB::raw('DATE_FORMAT(point_subscription_history.payment_date, "%Y-%m-%d") as payment_date'),
            DB::raw('DATE_FORMAT(point_subscription_history.begin_date, "%Y-%m-%d") as begin_date'),
            DB::raw('DATE_FORMAT(point_subscription_history.point_expire_date, "%Y-%m-%d") as point_expire_date'),
            'point_subscription_history.amount as amount',
            'course.course_id as course_id',
            'course.course_name as course_name',
            'student.student_name as student_name',
            'student.is_lms_user as is_lms_user',
            DB::raw('DATE_FORMAT(student_point_history.start_date, "%Y-%m-%d") as start_date'),
            'point_subscription_history.tax as tax',
            'course.course_type',
            'point_subscription_history.payment_way as payment_way'
        )
            ->leftJoin('course', function ($join) {
                $join->on('point_subscription_history.course_id', '=', 'course.course_id');
            })
            ->leftJoin('student', function ($join) {
                $join->on('point_subscription_history.student_id', '=', 'student.student_id');
            })
            ->leftJoin('order', function ($join) {
                $join->on('point_subscription_history.order_id', '=', 'order.order_id');
            })
            ->leftJoin('student_point_history', function ($join) {
                $join->on('point_subscription_history.point_subscription_history_id', '=', 'student_point_history.point_subscription_id');
            })
            ->where('point_subscription_history.point_subscription_history_id', $id)
            ->where('point_subscription_history.del_flag', 0)
            ->groupBy('point_subscription_history.point_subscription_history_id')
            ->first();
    }

    public function course()
    {
        return $this->belongsTo('App\Models\Course', 'course_id', 'course_id');
    }

    public function student()
    {
        return $this->belongsTo('App\Models\Student', 'student_id', 'student_id');
    }

    public function studentPointHistories()
    {
        return $this->hasMany(StudentPointHistory::class, 'point_subscription_id', 'point_subscription_history_id');
    }
}
