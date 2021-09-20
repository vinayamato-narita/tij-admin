<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\LangType;

class NewsLangRequest extends FormRequest
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
            'news_lang_title' => 'required|max:255',
            'news_lang_body' => 'max:20000',
            'lang' => 'required|enum_value:' . LangType::class . ',false'
        ];
    }
}
