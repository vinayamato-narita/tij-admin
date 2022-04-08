<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUpdateTextRequest extends FormRequest
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
            'lesson_text_url' => 'nullable|max:255|url',
            'lessonTextUrlForTeacher' => 'nullable|max:255|url',
            'lessonTextName' => 'required|max:255',
            'lessonTextSoundUrl' => 'nullable|url'
        ];
    }
}
