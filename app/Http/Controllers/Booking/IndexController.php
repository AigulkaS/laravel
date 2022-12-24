<?php

namespace App\Http\Controllers\Booking;

use App\Http\Filters\BookingFilter;
use App\Http\Requests\Booking\FilterRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Hospital;

// use App\Http\Filters\DiseaseFilter;
// use App\Http\Requests\Disease\FilterRequest;
// use App\Http\Resources\DiseaseResource;
// use App\Models\Disease;

class IndexController extends BaseController
{
    public function __invoke(FilterRequest $request)
    {
        $data = $request->validated();
        if(empty($data)) {
            return $this->service->getAllHospitalsInfo();
        } else {
            return $this->service->getHospitalInfoById($data['hospital_id']);
        }
    }
  
}
