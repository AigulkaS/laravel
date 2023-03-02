<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Requests\Hospital\UpdateRequest;
use App\Http\Resources\HospitalResource;
use App\Models\Hospital;

class UpdateController extends BaseController
{
    public function __invoke(UpdateRequest $request, Hospital $hospital)
    {
        $data = $request->validated();

        $hospital = $this->service->update($hospital, $data);
            
        // return new HospitalResource($hospital);
        return $hospital instanceof String ? $hospital : new HospitalResource($hospital);
    }

        
}
