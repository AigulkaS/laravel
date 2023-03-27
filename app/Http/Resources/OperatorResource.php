<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OperatorResource extends JsonResource
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
            'date' => $this->date,
            'hospital_id' => $this->hospital_id,
            'hospital_name' => $this->hospital->short_name,
            'surgeon_id' => $this->surgeon_id ?? "",
            'surgeon_last_name' => $this->surgeon->last_name ?? "",
            'surgeon_first_name' => $this->surgeon->first_name ?? "",
            'surgeon_patronymic' => $this->surgeon->patronymic ?? "",
            'cardiologist_id' => $this->cardiologist_id ?? "",
            'cardiologist_last_name' => $this->cardiologist->last_name ?? "",
            'cardiologist_first_name' => $this->cardiologist->first_name ?? "",
            'cardiologist_patronymic' => $this->cardiologist->patronymic ?? "",
        ];
    }
}
