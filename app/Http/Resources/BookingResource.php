<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
//        return parent::toArray($request);
        return [
            'id' => $this->id,
            'status' => $this->status,
            'hospital_id' => $this->hospital_id,
            'hospital_name' => $this->hospital->short_name,
            'room_id' => $this->room_id,
            'room_name' => $this->room->name,
            // 'surgeon_id' => $this->surgeon_id ?? 'default',
            // 'surgeon_last_name' => $this->surgeon->last_name,
            // 'surgeon_first_name' => $this->surgeon->first_name,
            // 'surgeon_patronymic' => $this->surgeon->patronymic,
            // 'cardiologist_id' => $this->cardiologist_id ?? 'default',
            // 'cardiologist_last_name' => $this->cardiologist->last_name,
            // 'cardiologist_first_name' => $this->cardiologist->first_name,
            // 'cardiologist_patronymic' => $this->cardiologist->patronymic,
            'user_id' => $this->user_id,
            'user_last_name' => $this->user->last_name,
            'user_first_name' => $this->user->first_name,
            'user_patronymic' => $this->user->patronymic,
            'disease_id' => $this->disease_id,
            'disease_name' => $this->disease_id == null ? null : $this->disease->name,
            'condition_id' => $this->condition_id,
            'condition_name' => $this->condition_id == null ? null : $this->condition->name,
            'date_time' => $this->date_time,
        ];
    }
}
