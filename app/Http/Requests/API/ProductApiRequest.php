<?php

namespace App\Http\Requests\API;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductApiRequest extends FormRequest
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
            'brand_id' => ['nullable', 'array'],
            'category_id' => ['nullable', 'array'],
            'price_min' => ['nullable', 'integer', 'min:0'],
            'price_max' => ['nullable', 'integer', 'min:0'],
            'sort_by' => ['nullable', 'in:name_asc,name_desc,price_asc,price_desc,date_asc,date_desc'],
            'is_new_arrival' => ['nullable', 'boolean'],
            'user_id' => ['nullable', Rule::exists('users', 'id')],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
            'status' => true
        ], 422));
    }

    protected function prepareForValidation()
    {

        $data = [
            'sort' => null,
        ];

        $data['sort'] = $this->normalizeSortingParams();

        $this->merge($data);
    }

    private function normalizeSortingParams(): array
    {
        $sortOrder = 'asc';
        $sortName = 'name';

        if (isset($this->sort_by)) {

            $sort = explode('_', $this->sort_by);
            if ($sort[1] === 'desc') {
                $sortOrder = 'desc';
            }
            if ($sort[0] === 'price') {
                $sortName = 'sale_price';
            }
            if ($sort[0] === 'date') {
                $sortName = 'entry_date';
            }
        }

        $sortBy['name'] = $sortName;
        $sortBy['ordering'] = $sortOrder;

        return $sortBy;
    }
}
