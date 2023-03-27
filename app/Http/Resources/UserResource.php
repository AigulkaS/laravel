<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'role_id' => $this->role_id,
            'role_name' => $this->role == null ? null :  $this->role->name,
            'role_label' => $this->role == null ? null :  $this->role->label,
            'hospital_id' => $this->hospital_id,
            'hospital_type' => $this->hospital->type,
            'hospital_name' => $this->hospital->short_name,
            'last_name' => $this->last_name,
            'first_name' => $this->first_name,
            'patronymic' => $this->patronymic,
            'phone' => $this->phone,
            'email' => $this->email,
            'password' => $this->password,
            'push' => $this->push,
            'sms' => $this->sms,
            'email_verified_at' => $this->email_verified_at
        ];
    }
}
