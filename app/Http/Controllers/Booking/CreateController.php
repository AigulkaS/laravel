<?php

namespace App\Http\Controllers\Booking;

use App\Http\Requests\Booking\CreateRequest;
use App\Http\Resources\DiseaseResource;
use App\Http\Resources\HospitalResource;
use App\Http\Resources\PatientConditionResource;
use App\Models\Disease;
use App\Models\PatientCondition;

class CreateController extends BaseController
{
    public function disease()
    {
        $diseases = Disease::all();
        $patientConditions = PatientCondition::all();
        return response()->json([
            'disease' => DiseaseResource::collection($diseases),
            'conditions' => PatientConditionResource::collection($patientConditions),
        ], 200);
    }

    public function hospital(CreateRequest $request)
    {
        $data = $request->validated();

        $dateTime = $this->service->getDateTimeForBook();

        $hospital = $this->service->getNearestHospital($data, $dateTime);
        
        return response()->json([
            'hospital' => $hospital,
            'dateTime' => $dateTime,
        ], 200);
    }




                
            
}
