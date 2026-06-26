<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $section = $this->input('section', 'account');

        $rules = [];

        if ($section === 'account') {
            $rules = [
                'name'         => ['required', 'string', 'max:255'],
                'email'        => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($this->user()->id)],
                'country_code' => ['required', 'string', 'size:3'],
                'username'     => ['required', 'string', Rule::unique('users')->ignore($this->user()->id)],
            ];
        }

        if ($section === 'password') {
            $rules = [
                'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            ];
        }

        if ($section === 'public_profile') {
            $rules = [
                'twitter'  => ['nullable', 'string', Rule::unique('users')->ignore($this->user()->id)],
                'mastodon' => ['nullable', 'string', Rule::unique('users')->ignore($this->user()->id)],
            ];
        }

        return $rules;
    }
}
