<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class VendorProductUploadImageRequest extends FormRequest
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
            'vendor_product_id' => 'required|integer|exists:vendor_products,id',
            'images'             => 'required|array',
            'images.*'           => 'required|image|mimes:jpg,png,jpeg|max:500',
        ];
    }
}