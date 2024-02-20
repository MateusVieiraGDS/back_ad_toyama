<?php

namespace App\Http\Requests;

use Exception;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;

class UpdatePasswordRequest extends FormRequest
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
        $user = auth()->user();
        return [
            'password_token' => 'required',
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|same:new_password',
            'user_id' => 'required',
            'code' => [
                'required',
                Rule::exists('users', 'forgout_password_code')
                    ->where(function ($query){
                        $query->where('id', $this->user_id);
                        $query->where('forgout_password_expires', '>', now());
                    }),
            ],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        try{
            list($code, $userId) = explode("|", Crypt::decrypt($this->password_token));
            $this->merge([
                'code' => $code,
                'user_id' => $userId
            ]);
        }catch(Exception){}
    }


    /**
     * Handle a passed validation attempt.
     */
    protected function passedValidation(): void
    {
        unset($this->password_token);
    }
}
