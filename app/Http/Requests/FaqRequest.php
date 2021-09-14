<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FaqRequest extends FormRequest
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
            'no_faq' => 'required|integer|between:1,1000000000',
            'faq_category_id' => 'required|integer',
            'question' => 'required|max:2000',
            'answer' => 'required|max:2000'
        ];
    }
}
