<?php

namespace App\Http\Requests\Today;

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
            'hospital_id' => 'required|integer',
            'surgeon_id' => 'required|integer',
            'cardiologist_id' => 'required|integer',
        ];

    }
}
