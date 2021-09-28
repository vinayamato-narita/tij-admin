<?php

namespace App\Http\Requests;

use App\Enums\RemindMailTimmingMinutesEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRemindMailRequest extends FormRequest
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
            'mailSubject' => 'required|max:255',
            'mailBody' => 'required',
            'timingMinutes' => ['required',
                Rule::in(RemindMailTimmingMinutesEnum::getValues()),
            ],
        ];
    }
}
