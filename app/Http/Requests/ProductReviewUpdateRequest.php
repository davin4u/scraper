<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductReviewUpdateRequest extends FormRequest
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
            'title' => 'required|min:8',
            'url' => 'required|min:8|url',
            'pros' => 'required|min:8',
            'cons' => 'required|min:8',
            'body' => 'required|min:8',
            'summary' => 'required|min:8',
            'bought_at' => 'date'
        ];
    }

    public function messages()
    {
        return [
            'bought_at.date' => 'The year of bought must be in format YYYY-MM-DD.'
        ];
    }
}
