<?php

namespace App\Http\Requests\API;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class StoreWishlistApiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return !is_null($this->user());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => 'required|exists:products,id'
        ];
    }

    public function prepareForValidation()
    {
        if ($this->user()) {
            $this->merge(['user_id' => $this->user()->id]);
        }
    }

}
