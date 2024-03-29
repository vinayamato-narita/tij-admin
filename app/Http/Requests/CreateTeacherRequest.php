<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTeacherRequest extends FormRequest
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
            'displayOrder' => 'required|digits_between:1,1000000000',
            'teacherName' => 'required|max:255',
            'nickName' => 'required|max:255',
            'mail' => [
                'required',
                'email',
                'max:255',
                'unique:teacher,teacher_email'
            ],
            'timezone' => 'exists:timeZone,timezone_id',
            'teacherUniversity' => 'max:255',
            'teacherDepartment' => 'max:255',
            'teacherHobby' => 'max:255',
            'zoomPersonalMeetingId' => 'required|max:255',
            'zoomPassword' => 'max:50',
            'show_flag' => 'required'

        ];
    }

    public function messages()
    {
        return [
            'mail.unique' => 'このメールアドレスは既に存在します',
        ];
    }
}
