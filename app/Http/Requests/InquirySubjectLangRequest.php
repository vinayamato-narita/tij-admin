<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\LangType;

class InquirySubjectLangRequest extends FormRequest
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
            'inquiry_subject_id' => 'required|integer',
            'lang_inquiry_subject' => 'required|max:255',
            'lang' => 'required|enum_value:' . LangType::class . ',false'
        ];
    }
}
