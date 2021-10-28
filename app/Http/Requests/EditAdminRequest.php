<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Log;
class EditAdminRequest extends FormRequest
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
        Log::info($this);
        return [
            'admin_user_name' => 'required|max:255',
            'admin_user_email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('admin_user', 'admin_user_email')->where(function ($query) {
                    return $query->where('admin_user_id', '!=', $this->admin_user_id);
                }),
            ],
            'password' => 'nullable|min:8|max:32|regex:/^[A-Za-z0-9]*$/i',
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
