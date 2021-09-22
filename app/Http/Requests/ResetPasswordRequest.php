<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
            'remember_token' => 'required',
            'password' => 'required|min:8|regex:/^[A-Za-z0-9]*$/i',
            'password_confirm' => 'required|same:password',
        ];
    }
}
