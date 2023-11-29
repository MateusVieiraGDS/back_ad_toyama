<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                'max:255',
                'exists:users,email',
            ],
            'password' => [
                'required',
                'max:255',
            ]
        ];
    }

    
    /**
     * Get the rate limit throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        return md5($this->input('email').'|'.$this->ip());
    }
}
