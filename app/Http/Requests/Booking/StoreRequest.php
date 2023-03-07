<?php

namespace App\Http\Requests\Booking;

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
            'disease_id' => 'required|integer',
            'condition_id' => 'required|integer',
            'user_id' => 'required|integer', //!
            'hospital_id' => 'required|integer',
            'date_time' => 'required|string',
        ];

    }
}
