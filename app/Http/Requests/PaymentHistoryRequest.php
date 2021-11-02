<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\PaidStatus;

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
            'management_number' => 'nullable|regex:/^[!-~]+$/i|max:10',
            'course_begin_month' => 'required_if:is_lms_user,1|nullable|date',
            'payment_date' => 'required|date',
            'start_date' => 'required|date',
            'begin_date' => 'required|date',
            'amount' => 'required|integer|between:0,1000000000',
            'payment_type' => 'required|enum_value:' . PaidStatus::class . ',false'
        ];
    }
}
