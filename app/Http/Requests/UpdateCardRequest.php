<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Override;

class UpdateCardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    // Função que define valores por padrão antes da validação 
    #[Override]
    protected function prepareForValidation()
    {
        $this->merge([
            'available' => $this->boolean('available'),
            'show' => $this->boolean('show')
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                    'sometimes',
                    //'unique:App\Models\Card,name',
                    'string',
                    'max:100',
                    'regex:/^[\pL\pN]+(?:\s[\pL\pN]+)*$/u'
                ],

            'images' => [
                    'nullable',
                    'array',
                    'max:5'
                ],

            'images.*' => [
                    'image',
                    'extensions:jpg,png,jpeg',
                    'max:2048'
                ],

            'description' => [
                    'nullable',
                    'string'
                ],

            'categories' => [
                    'required',
                    'array'
                ],

            'categories.*' => [
                    'exists:categories,id'
                ],

            'available' => [
                    'boolean'
                ],

            'show' => [
                    'boolean'
                ],

            'price' => [
                    'required',
                    'numeric',
                    'min:0'
                ],
        ];
    }
}
