<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Requests\Hospital\StoreRequest;
use App\Http\Resources\HospitalResource;

class StoreController extends BaseController
{

    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();

        $hospital = $this->service->store($data);

        return new HospitalResource($hospital);
    }

}
