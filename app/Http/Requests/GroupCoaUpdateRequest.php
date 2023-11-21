<?php

namespace App\Http\Requests;

use App\Models\GroupCoa;
use Illuminate\Foundation\Http\FormRequest;

class GroupCoaUpdateRequest extends FormRequest
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
            // 'code' => 'required|string|max:255|unique:group_coas,code,'.$this->id,
            'code' => 'required|string|max:5',
            'name' => 'required|string|max:255',
            'normal_balance' => 'required',
            'is_active' => 'required'
        ];
    }
}
