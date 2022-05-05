<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Log;

class EditFileRequest extends FormRequest
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
            'file_code' => [
                'required', 
                'max:255',
                Rule::unique('file', 'file_code')->where(function ($query) {
                    return $query->where('file_id', '!=', $this->file_id);
                }),
            ],
            'file_display_name' => 'required|max:255',
            'file_description' => 'max:20000',
        ];
    }

    public function messages()
    {
        return [
            'file_code.unique' => 'メディアコードが存在されています。',
        ];
    }
}
