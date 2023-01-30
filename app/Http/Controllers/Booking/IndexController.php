<?php

namespace App\Http\Controllers\Booking;

use App\Http\Requests\Booking\FilterRequest;


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
