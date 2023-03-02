<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Requests\Hospital\DisableRequest;
use App\Http\Resources\HospitalRoomResource;

class DisableController extends BaseController
{
    public function __invoke(DisableRequest $request)
    {
        $data = $request->validated();

        $room = $this->service->disable($data);

        return $room instanceof String ? $room : new HospitalRoomResource($room);
    }

        
}
