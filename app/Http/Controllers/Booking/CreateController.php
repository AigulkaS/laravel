<?php

namespace App\Http\Controllers\Booking;

use App\Http\Requests\Booking\CreateRequest;
use App\Http\Resources\DiseaseResource;
use App\Http\Resources\HospitalResource;
use App\Models\Disease;

class CreateController extends BaseController
{
    public function disease()
    {
        $diseases = Disease::all();
        return response()->json([
            'disease' => DiseaseResource::collection($diseases),
        ], 200);
    }

    public function hospital(CreateRequest $request)
    {
        $data = $request->validated();

        $hospital = $this->service->getNearestHospital($data);
        
        return response()->json([
            'data' => $hospital,
        ], 200);
    }




                
            
}
