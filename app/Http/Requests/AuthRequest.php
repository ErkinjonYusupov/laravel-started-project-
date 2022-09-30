<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'full_name' => 'required',
            'username' => 'required',
            'password' => 'required',
            'organization_id' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'full_name.required' => 'Ism familya kiritilmadi',
            'username.required' => 'Login kiritilmadi',
            'username.unique:users' => 'Login yaroqsiz',
            'password.required' => 'Parol kiritilmadi',
            'organization_id.required' => 'Ism familya kiritilmadi'
        ];
    }
}
