<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Log;
class CreateAdminRequest extends FormRequest
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
            'admin_name' => 'required|max:255',
            'admin_email' => [
                'required',
                'email',
                'max:255',
                'unique:admin_user,admin_user_email'
            ],
            'password' => 'required|min:8|max:32|regex:/^[A-Za-z0-9]*$/i',
            'description' => 'max:2000'
        ];
    }

    public function messages()
    {
        return [
            'admin_email.unique' => 'このメールアドレスは既に存在します',
        ];
    }
}
