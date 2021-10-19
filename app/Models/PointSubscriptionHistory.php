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
    public $timestamps = false;
    public function studentPointHistory() 
    {
        return $this->hasMany(StudentPointHistory::class);
    }

    public static function getPaymentHistoryInfo($id) {
    	return DB::table('point_subscription_histories')->select('point_subscription_histories.id as id',
            'point_subscription_histories.student_id as student_id',
            DB::raw('(CASE WHEN point_subscription_histories.payment_way = 2 THEN point_subscription_histories.payment_way + point_subscription_histories.paid_status ELSE point_subscription_histories.payment_way END) AS payment_type'),
            'point_subscription_histories.point_count as point_count',
            'point_subscription_histories.management_number as management_number',
            'point_subscription_histories.payment_date as payment_date',
            'point_subscription_histories.begin_date as begin_date',
            'point_subscription_histories.point_expire_date as point_expire_date',
            'point_subscription_histories.amount as amount',
            'courses.course_id as course_id',
            'courses.course_name as course_name',
            'students.student_name as student_name',
            'students.is_lms_user as is_lms_user',
            'student_point_histories.start_date as start_date',
            'lms_project_course_students.course_begin_month as course_begin_month',
            'point_subscription_histories.tax as tax'
        )
            ->leftJoin('courses', function($join) {
                $join->on('point_subscription_histories.course_id', '=', 'courses.course_id');
            })
            ->leftJoin('students', function($join) {
                $join->on('point_subscription_histories.student_id', '=', 'students.id');
            })
            ->leftJoin('orders', function($join) {
                $join->on('point_subscription_histories.order_id', '=', 'orders.order_id');
            })
            ->leftJoin('student_point_histories', function($join) {
                $join->on('point_subscription_histories.id', '=', 'student_point_histories.point_subscription_id');
            })
            ->leftJoin('lms_project_course_students', function($join) {
                $join->on('point_subscription_histories.id', '=', 'lms_project_course_students.point_subscription_id');
            })
            ->leftJoin('lms_projects', function($join) {
                $join->on('lms_project_course_students.project_id', '=', 'lms_projects.id');
            })
            ->leftJoin('lms_companies', function($join) {
                $join->on('lms_projects.company_id', '=', 'lms_companies.id');
            })
            ->where('point_subscription_histories.id', $id)
            ->where('point_subscription_histories.del_flag', 0)
            ->groupBy('point_subscription_histories.id')
            ->first();
    }
}
