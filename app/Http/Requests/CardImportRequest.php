<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CardImportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if(($this->user()->id == $this->post->id) or ($this->user()->hasPermissionTo('manage all posts'))){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
            return [
                'content' => 'required'
        ];
        
    }
}