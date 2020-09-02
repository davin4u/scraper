<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class YmlDataImportUploadRequest extends FormRequest
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
            'yml-file' => 'required|file|mimes:xml,txt'
        ];
    }

    public function messages()
    {
        return [
            'yml-file.required' => 'Please, select a file before upload',
            'Uploaded file extension should be xml'
        ];
    }
}
