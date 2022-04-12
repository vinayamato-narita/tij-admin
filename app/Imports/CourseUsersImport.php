<?php

namespace App\Imports;

use App\Models\PointSubscriptionHistory;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;

class CourseUsersImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return User|null
     */
    public function model(array $row)
    {
        return new PointSubscriptionHistory([
           'course_id'     => $row[0],
           'email'    => $row[1],
        ]);
    }
}
