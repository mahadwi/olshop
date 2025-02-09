<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVendorProductRequest extends FormRequest
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
            'product_category_id' => 'required',
            'description' => 'required|string',
            'description_en' => 'required|string',
            'price' => 'required',
            'price_usd' => 'required',
            'commission_type' => 'required|in:Percent,Selling',
            'commission' => 'required|integer',
            'sale_price' => 'required',
            'sale_usd' => 'required',
            'color_id' => 'required',
            'condition' => 'required|string|in:New,Like New',            
            'weight' => 'required|numeric',
            'length' => 'required|numeric',
            'width' => 'required|numeric',
            'height' => 'required|numeric',
            'product_deadline' => 'required',  
        ];
    }
}
