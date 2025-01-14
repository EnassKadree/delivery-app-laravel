<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|array',
            'name.en' => 'required|string|max:255',
            'name.ar' => 'required|string|max:255',
            'address' =>  'required|array',
            'address.en' => 'required|string|max:255',
            'address.ar' => 'required|string|max:255',
            'logo_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }
}
