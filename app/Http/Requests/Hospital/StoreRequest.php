<?php

namespace App\Http\Requests\Hospital;

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
            'type' => 'required|integer',
            'full_name' => 'required|string',
            'short_name' => 'required|string',
            'address' => 'string',
            'hospital_rooms' => '',
            'geo_lat' => 'string', 
            'geo_lon' => 'string', 
        ];

        // 'hospital_rooms' => [
        //     'name' => 'required|integer',
        //     'start' => 'string',
        //     'end' => 'string',
        // ],

    }
}
