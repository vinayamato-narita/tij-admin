<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;

class StudentsImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Student([
            'student_name'         => $row[0],
            'student_nickname'     => $row[1],
            'student_email'        => $row[2],
            'student_birthday'     => $row[3],
            'student_sex'          => $row[4],
            'company_name'         => $row[5],
            'password' => \Hash::make($row[6]),
        ]);
    }
}
