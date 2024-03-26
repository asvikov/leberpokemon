<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PokemonRequest extends FormRequest
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
                'regex:/^[a-z0-9]+[\sa-z0-9]*[a-z0-9]+$/i',
                Rule::unique('pokemones')->ignore($this->pokemone) //почему не id, смотреть dd($this->route());
            ],
            'image' => 'required_without:image_file',
            'region_id' => 'required',
            'image_file' => 'image|mimes:jpeg,png,jpg,gif',
            'shapes_id' => [
                'required',
                'regex:/^\[(\d+(, )*)+\]$/'
            ],
            'abilities_id' => [
                'required',
                'regex:/^\[(\d+(, )*)+\]$/'
            ]
        ];
    }
}
