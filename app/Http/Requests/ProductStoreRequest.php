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
            'brand_id' => 'required',
            'description' => 'required|string',
            'description_en' => 'required|string',
            'entry_date' => 'required|string|max:255',
            'expired_date' => 'required|string|max:255',
            'product_category_id' => 'required',
            'vendor_id' => 'required',
            'stock' => 'required|integer',
            'price' => 'required|integer',
            'price_usd' => 'required|integer',
            'commission_type' => 'required',
            'commission' => 'required|integer',
            'sale_price' => 'required|integer',
            'sale_usd' => 'required|integer',
            'display_on_homepage' => 'required',
            'color_id' => 'required',
            'condition' => 'required',
            // 'image' => 'required|image|mimes:jpg,png,jpeg|max:500',
            'image.*' => 'required|image|mimes:jpg,png,jpeg|max:500',
            'weight' => 'required|numeric',
            'length' => 'required|numeric',
            'width' => 'required|numeric',
            'height' => 'required|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'product_category_id.required' => 'Product Category is required',
            'user_id.required' => 'Vendor is required',
            'brand_id.required' => 'Brand is required',
            'color_id.required' => 'Color is required',
        ];
    }
}
