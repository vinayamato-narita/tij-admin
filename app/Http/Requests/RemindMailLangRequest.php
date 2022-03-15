<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\LangType;

class RemindMailLangRequest extends FormRequest
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
            'send_remind_mail_pattern_id' => 'required|integer',
            'mail_lang_subject' => 'required|max:255',
            'mail_lang_body' => 'max:20000',
            'lang_type' => 'required|enum_value:' . LangType::class . ',false'
        ];
    }
}
