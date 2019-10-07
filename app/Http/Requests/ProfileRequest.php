<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->user()->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'country_code' => ['required', 'string', 'size:3'],
            'twitter' => ['nullable', 'string', Rule::unique('users')->ignore($this->user()->id)],
            'username' => ['required', 'string', Rule::unique('users')->ignore($this->user()->id)],
        ];
    }
}
