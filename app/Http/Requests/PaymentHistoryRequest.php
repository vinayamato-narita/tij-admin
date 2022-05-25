<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\PaymentWay;

class PaymentHistoryRequest extends FormRequest
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
            'student_id' => 'required|integer',
            'course_id' => 'required|integer',
            'payment_date' => 'required|date',
            'begin_date' => 'required|date',
            'amount' => 'required|integer|between:0,1000000000',
            'payment_way' => 'required|enum_value:' . PaymentWay::class . ',false'
        ];
    }
}
