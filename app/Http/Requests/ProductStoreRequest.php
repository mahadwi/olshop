<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'brand_id' => 'required|string|max:255',
            'description' => 'required|string',
            'entry_date' => 'required|string|max:255',
            'expired_date' => 'required|string|max:255',
            'product_category_id' => 'required',
            'user_id' => 'required',
            'stock' => 'required|integer',
            'price' => 'required|integer',
            'commission_type' => 'required',
            'commission' => 'required|integer',
            'sale_price' => 'required|integer',
            'display_on_homepage' => 'required',
            'image' => 'required|image|mimes:jpg,png,jpeg|max:500',            
        ];
    }

    public function messages(): array
    {
        return [
            'product_category_id.required' => 'Product Category is required',
            'user_id.required' => 'Vendor is required',
            'brand_id.required' => 'Brand is required',
        ];
    }
}
