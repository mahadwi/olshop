<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class VoucherStoreRequest extends FormRequest
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
            'code' => 'required|string|max:15',
            'name' => 'required|string|max:255',
            'date' => 'required|array',
            'time' => 'required|array',
            'capacity' => 'required',
            'quota' => 'required|integer',
            'use_for' => 'required',
            'type' => 'required',
            'disc_percent' => 'required|integer|between:0,100',
            'disc_price' => 'required|integer',
            'min_price' => 'required|integer',
        ];
    }


    public function passedValidation(){
        // Jika validasi berhasil, tambahkan input setelah validasi
        $this->merge(
            [
                'start_date' => $this->date[0],
                'end_date' => $this->date[1],
                'time_start' => $this->time[0],
                'time_end' => $this->time[1],
            ]
        );
            
    }

}
