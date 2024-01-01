<?php

namespace App\Http\Requests;

use App\Rules\NonEmptyStringIgnoringTags;
use Illuminate\Foundation\Http\FormRequest;

class WorkWithUsStoreSection4Request extends FormRequest
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
           'titleSection4' => 'required|string|max:255',
           'titleEnSection4' => 'required|string|max:255',
           'descriptionSection4' => 'required',
           'descriptionEnSection4' => 'required',
           'imageSection4' => 'nullable|image|mimes:jpg,png,jpeg|max:500',
           'linkSection4' => 'required|url',
       ];
   }

   public function prepareForValidation()
   {
       $this->merge([
           'descriptionSection4' => $this->descriptionSection4 == "<p><br></p>" ? "" : $this->descriptionSection4,
           'descriptionEnSection4' => $this->descriptionEnSection4 == "<p><br></p>" ? "" : $this->descriptionSection4
       ]);
   }
}
