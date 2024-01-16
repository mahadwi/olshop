<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class StoreVendorProductRequest extends FormRequest
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
            'commission_type' => 'required',
            'commission' => 'required|integer',
            'sale_price' => 'required',
            'sale_usd' => 'required',
            'color_id' => 'required',
            'condition' => 'required',
            'weight' => 'required|numeric',
            'length' => 'required|numeric',
            'width' => 'required|numeric',
            'height' => 'required|numeric',
            'image' => 'required|array',
            'image.*' => 'required|image|mimes:jpg,png,jpeg|max:500',
        ];
    }

    public function prepareForValidation()
    {
        dd($this->user());
        if ($this->user()) {
            dd($this->user()->vendor);
            $this->merge(['user_id' => $this->user()->id]);
        }
    }
}
