<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\LangType;

class TeacherLangRequest extends FormRequest
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
            'teacher_id' => 'required|integer',
            'teacher_name_lang' => 'required|max:255',
            'teacher_nickname_lang' => 'required|max:255',
            'teacher_university_lang' => 'max:255',
            'teacher_department_lang' => 'max:255',
            'teacher_introduction_lang' => 'max:20000',
            'introduce_from_admin_lang' => 'max:20000',
            'lang_type' => 'required|enum_value:' . LangType::class . ',false'
        ];
    }
}
