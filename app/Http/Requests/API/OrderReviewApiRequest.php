<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class OrderReviewApiRequest extends FormRequest
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
            'order_id'                  => 'required|exists:orders,id',
            'reviews'                   => 'required|array',
            'reviews.*.order_detail_id' => 'required|exists:order_details,id',
            'reviews.*.rating'          => 'required|min:1|max:5',
            'reviews.*.review'          => 'required',
            'reviews.*.image'           => 'nullable|array',
            'reviews.*.image.*'         => 'nullable|image|mimes:jpg,png,jpeg|max:500',
        ];
    }

    public function prepareForValidation()
    {
        if ($this->user()) {
            $this->merge(['user_id' => $this->user()->id]);
        }
    }
}
