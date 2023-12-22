<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
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
            'price' => 'required',
            'price_usd' => 'required',
            'commission_type' => 'required',
            'commission' => 'required|integer',
            'sale_price' => 'required',
            'sale_usd' => 'required',
            'display_on_homepage' => 'required',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:500',
            'color_id' => 'required',
            'condition' => 'required',
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

    protected function prepareForValidation()
    {
        // Logika sebelum validasi dilakukan
        $this->merge([
            'price' => $this->cleanCurrencyFormat($this->input('price')),
        ]);
        $this->merge([
            'sale_price' => $this->cleanCurrencyFormat($this->input('sale_price')),
        ]);
    }

    // Metode untuk membersihkan format mata uang
    protected function cleanCurrencyFormat($value)
    {
        // Lakukan pembersihan format mata uang di sini
        return str_replace('.', '', $value);
    }
}
