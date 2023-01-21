<?php

namespace App\Http\Requests\Booking;

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
            'room_id' => 'required|integer',
            'status' => 'required|integer',
            'date_time' => 'required|string',
            'booking_hours' => 'required|integer',
        ];

    }
}