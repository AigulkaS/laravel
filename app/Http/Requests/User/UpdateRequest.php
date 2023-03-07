<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'role_id' => 'integer',
            'hospital_id' => 'required|integer',
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'patronymic' => 'required|string|max:255',
            'phone' => 'string|max:255',
            'push' => 'integer',
            'sms' => 'integer',
            // 'email' => 'required|string|email|max:255',
            // 'password' => 'required|string',
        ];
    }
}
