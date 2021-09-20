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
            'admin_name' => 'required|max:255',
            'admin_email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('admin_users', 'admin_email')->where(function ($query) {
                    return $query->where('id', '!=', $this->id);
                }),
            ],
            'password' => 'nullable|min:8|max:32|regex:/^[A-Za-z0-9]*$/i',
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
