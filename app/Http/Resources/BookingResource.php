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
            'surgeon_id' => $this->surgeon_id,
            'surgeon_last_name' => $this->surgeon->last_name,
            'surgeon_first_name' => $this->surgeon->first_name,
            'surgeon_patronymic' => $this->surgeon->patronymic,
            'cardiologist_id' => $this->cardiologist_id,
            'cardiologist_last_name' => $this->cardiologist->last_name,
            'cardiologist_first_name' => $this->cardiologist->first_name,
            'cardiologist_patronymic' => $this->cardiologist->patronymic,
            'dispatcher_id' => $this->dispatcher_id,
            'dispatcher_last_name' => $this->dispatcher->last_name,
            'dispatcher_first_name' => $this->dispatcher->first_name,
            'dispatcher_patronymic' => $this->dispatcher->patronymic,
            'disease_id' => $this->disease_id,
            'disease_name' => $this->disease->name,
            'disease_code' => $this->disease->code,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
        ];
    }
}
