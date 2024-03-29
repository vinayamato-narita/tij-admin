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
            'admin_user_name' => 'required|max:255',
            'admin_user_email' => [
                'required',
                'email',
                'max:255',
                'unique:admin_user,admin_user_email'
            ],
            'password' => 'required|min:8|max:32',
            'admin_user_description' => 'max:2000'
        ];
    }

    public function messages()
    {
        return [
            'admin_user_email.unique' => 'このメールアドレスは既に存在します',
        ];
    }
}
