<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Kyslik\ColumnSortable\Sortable;

class Student extends Authenticatable
{
    use HasFactory, Sortable;

    protected $table = 'student';

    public $timestamps = false;

    protected $primaryKey = 'student_id';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function pointSubscriptionHistories()
    {
        return $this->hasMany('App\Models\PointSubscriptionHistory', 'student_id', 'student_id')->orderBy('payment_status', 'desc');
    }
}
