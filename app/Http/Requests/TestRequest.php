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
        $validate = [
            'test_type' => 'required|enum_value:' . TestType::class . ',false',
            'test_name' => 'required|max:255',
            'test_description' => 'max:20000',
            'passing_score' => 'required|integer',
            'total_score' => 'nullable|integer',
        ];

        if ($this->request->get('test_type') === TestType::ENDCOURSE) {
            $validate['execution_time'] = 'required|integer';
            $validate['expire_count'] = 'required|integer';
        }

        return $validate;
    }
}
