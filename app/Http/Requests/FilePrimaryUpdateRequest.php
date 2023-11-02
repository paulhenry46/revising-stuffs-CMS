<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FilePrimaryUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
            return [
            'file_light' => 'required|max:10000|mimes:pdf',
            'file_dark' => 'max:10000|mimes:pdf',
            'update_type' => ['required', 'max:200', Rule::in(['bugfix', 'news'])],
            'update_content' => 'required|max:200'

        ];
        
    }
}