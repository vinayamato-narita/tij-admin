<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUpdateReviewRequest extends FormRequest
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
            'reviewName' => 'required|max:255',
            'file_code' => [
                'max:255',
                Rule::unique('file', 'file_code')->where(function ($query) {
                    return $query->where('file_id', '!=', $this->fileId);
                }),
            ],
        ];
    }

    public function messages()
    {
        return [
            'file_code.unique' => 'メディアコードが存在されています。',
        ];
    }
}
