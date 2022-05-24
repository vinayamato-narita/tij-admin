<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\OptionUploadFile;
use Illuminate\Support\Facades\Storage;
use Log;

class CreateFileRequest extends FormRequest
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
            'file_code' => [
                'required', 
                'max:255',
                'unique:file,file_code'
            ],
            'option_upload_file' => 'required|enum_value:' . OptionUploadFile::class . ',false',
            'file_attach' => 'required_if:option_upload_file,' . OptionUploadFile::PC,
            'url_file_path' => ['required_if:option_upload_file,' . OptionUploadFile::CLOUD,
                function ($attribute, $value, $fail) {
                    if($value) {
                        $fileBaseMedia = env('AZURE_STORAGE_URL');
                        $arrUrl = explode($fileBaseMedia, $value);
                        $orgirinalName = $arrUrl[1]; 
                        
                        if (!Storage::disk('azure')->exists($orgirinalName)) {
                            return $fail('指定のURLにファイルが存在しません。');
                        }
                    }
                }],
            'file_display_name' => 'required|max:255',
            'file_description' => 'max:20000',
        ];
    }

    public function messages()
    {
        return [
            'file_code.unique' => 'メディアコードが存在されています。',
        ];
    }
}
