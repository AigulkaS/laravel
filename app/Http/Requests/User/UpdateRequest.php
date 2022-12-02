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
            'role_id' => '',
            'hospital_id' => '',
            'last_name' => '',
            'first_name' => '',
            'patronymic' => '',
            'phone' => '',
            'email' => '',
            'password' => '',
        ];
    }
}
