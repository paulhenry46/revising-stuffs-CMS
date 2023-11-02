<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileRequest extends FormRequest
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
        if($this->input('type') == 'source'){
            return [
            'type' => 'bail|required|min:4',
            'name' => 'bail|required|min:4',
            'file' => 'required|max:10000|mimes:doc,docx,pdf,tex,odt,ods,csv'

        ];
        
        }else{
            return [
            'type' => 'bail|required|min:4',
            'name' => 'bail|min:4',
            'file' => 'required|max:10000|mimes:pdf'

        ];
        }
    }
}