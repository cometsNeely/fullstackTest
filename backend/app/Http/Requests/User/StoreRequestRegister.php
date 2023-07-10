<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequestRegister extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules():array
    {
        return [
            'username' => 'required|unique:users|string|min:6|max:50',
            'email' => 'required|unique:users|string|min:6|max:50',
            'password' => 'required|string|min:6|max:50|confirmed',
            'password_confirmation' => 'required|string|min:6|max:50'
        ];
    }

    public function messages():array
	{
		return [
         'username.unique' => 'Username уже существует',
		 'username.required' => 'Username необходимо заполнить',
         'email.unique' => 'Email уже существует',
         'email.required' => 'Email необходимо заполнить',
         'username.min' => 'Username не может быть менее 6 символов',
         'password.required' => 'Password необходимо заполнить',
         'password.min' => 'Password не может быть менее 6 символов',
         'password.confirmed' => 'Пароли не совпадают',
		];
	}
}
