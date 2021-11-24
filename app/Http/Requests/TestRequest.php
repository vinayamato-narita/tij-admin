<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\TestType;

class TestRequest extends FormRequest
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
            'test_type' => 'required|enum_value:' . TestType::class . ',false',
            'test_name' => 'required|max:255',
            'test_description' => 'max:20000',
            'execution_time' => 'nullable|integer',
            'expire_count' => 'nullable|integer',
            'passing_score' => 'required|integer',
            'total_score' => 'nullable|integer',
        ];
    }
}
