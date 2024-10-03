<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class StoreTeamRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'clinic_name' => ['required'],
            'user_id'     => ['nullable', 'required_without_all:name,email,password', 'exists:users,id'],

            'name'     => ['nullable', 'required_without:user_id', 'required_with:email,password', 'string'],
            'email'    => ['nullable', 'required_without:user_id', 'required_with:name,password', 'email'],
            'password' => ['nullable', 'required_without:user_id', 'required_with:name,email', 'string', Password::defaults()],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    protected function passedValidation(): void
    {
        if (is_null($this->user_id)) {
            $this->request->remove('user_id');
        }
    }
}
