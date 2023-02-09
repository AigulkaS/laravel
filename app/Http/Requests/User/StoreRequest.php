<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
        //            'role_id' => 'required|integer',
        //    'hospital_id' => 'required|integer',
        //    'last_name' => 'required|string',
        //    'first_name' => 'required|string',
        //    'patronymic' => 'required|string',
        //    'phone' => 'required|string',
        //    'email' => 'required|string',
        //    'password' => 'required|string',


            'role_id' => 'required|integer',
            'hospital_id' => 'required|integer',
            'last_name' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'patronymic' => 'required|string|max:255',
            'phone' => 'string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string',
            // 'password' => 'required|string||min:6|confirmed',

        ];



//        Phone Required????
    }
}
