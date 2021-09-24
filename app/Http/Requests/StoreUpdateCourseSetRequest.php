<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateCourseSetRequest extends FormRequest
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
            'reverseEnd' => 'digits_between:1,1000000000',
            'amount' => 'required|digits_between:0,1000000000',
            'courseName' => 'required|max:255',
            'courseNameShort' => 'max:255',
            'campaignCode' => 'nullable|max:8',
        ];
    }
}
