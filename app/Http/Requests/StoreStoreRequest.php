<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStoreRequest extends FormRequest
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
            'name.en' => 'required|string|max:255',
            'address.en' => 'required|string|max:255',
            'logo_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'user_id' => 'nullable|exists:users,id',
        ];
    }
}
