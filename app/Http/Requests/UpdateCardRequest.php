<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCardRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                    //'required',
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
                    'sometimes',
                    'boolean'
                ],

            'show' => [
                    'sometimes',
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
