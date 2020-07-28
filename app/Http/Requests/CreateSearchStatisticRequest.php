<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSearchStatisticRequest extends FormRequest
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
            'source' => 'required|min:10|max:75',
            'phrase' => 'required|min:10|max:75',
            'last-upd-date' => 'date_format:"Y-m-d H:i:s"'
        ];
    }

    public function messages(){
        return[
            'last-upd-date.date_format' => 'The Last update date does not match the format Y-m-d H:i:s.',
        ];
    }
}
