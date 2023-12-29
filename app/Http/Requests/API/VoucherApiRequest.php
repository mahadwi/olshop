<?php

namespace App\Http\Requests\API;

use App\Constants\VoucherUseFor;
use Illuminate\Foundation\Http\FormRequest;

class VoucherApiRequest extends FormRequest
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
        $use_for = VoucherUseFor::getValues();
        $use_for = implode(',', $use_for);

        return [
            'use_for' => ['required', 'string', 'in:' . $use_for],
        ];
    }
}
