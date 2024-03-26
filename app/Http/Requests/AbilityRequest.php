<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AbilityRequest extends FormRequest
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
            'name' => [
                'required',
                'regex:/^[a-z0-9]+[\sa-z0-9]*[a-z0-9]+$/i'
            ],
            'name_lang_ru' => [
                'required',
                'regex:/^[а-яА-ЯЁё0-9]+[\sа-яА-ЯЁё0-9]*[а-яА-ЯЁё0-9]+$/u'
            ],
            'image' => 'required_without:image_file',
            'image_file' => 'image|mimes:jpeg,png,jpg,gif'
        ];
    }
}
