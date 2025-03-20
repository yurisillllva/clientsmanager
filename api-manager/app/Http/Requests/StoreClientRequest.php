<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'neighborhood' => 'nullable|string',
            'city' => 'nullable|string',
            'state' => 'nullable|string|size:2',
            'age' => 'nullable|integer|min:1',
            'photo' => 'nullable|url'
        ];
    }
}
