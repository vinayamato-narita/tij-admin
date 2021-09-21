<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTeacherRequest extends FormRequest
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
                Rule::unique('teachers', 'teacher_email')->where(function ($query) {
                    return $query;
                })->ignore($this->id)
            ],
            'timezone' => 'exists:timeZone,timezone_id',
            'teacherUniversity' => 'max:255',
            'teacherDepartment' => 'max:255',
            'teacherHobby' => 'max:255',
            'photoSavepath' => 'nullable|url'
        ];
    }

    public function messages()
    {
        return [
            'mail.unique' => 'このメールアドレスは既に存在します',
        ];
    }
}
