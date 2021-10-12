<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentEditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|integer',
            'student_first_name' => 'required|max:100',
            'student_last_name' => 'required|max:100',
            'student_first_name_kata' => 'max:100',
            'student_last_name_kata' => 'max:100',
            'student_nickname' => 'max:16',
            'student_email' => 'required|max:255',
            'student_introduction' => 'max:20000',
            'student_home_tel' => 'max:20',
            'postcode' => 'max:10',
            'student_address1' => 'max:50',
            'student_address2' => 'max:50',
            'student_address3' => 'max:50',
            'student_comment_text' => 'max:20000',
        ];
    }
}
