<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
            'title' => 'bail|required|min:4',
            'description' => 'bail|required|min:10',
            'type_id' => 'bail|required|int',
            'course_id' => 'bail|required|numeric',
            'level_id' => 'bail|required|numeric',
            'description' => 'bail|required|max:200',
            'description' => 'bail|min:10',
            'dark_version' => '',
            'early_access' => '',
            'public' => '',
            'published' => '',
            'pinned' => '',
            'date' => ''

        ];
    }
}
