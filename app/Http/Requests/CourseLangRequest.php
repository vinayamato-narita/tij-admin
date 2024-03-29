<?php

namespace App\Http\Requests;

use App\Enums\LangType;
use Illuminate\Foundation\Http\FormRequest;

class CourseLangRequest extends FormRequest
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
            'course_id' => 'required|integer',
            'course_name' => 'required|max:255',
            'lang' => 'required|enum_value:' . LangType::class . ',false'
        ];
    }
}
