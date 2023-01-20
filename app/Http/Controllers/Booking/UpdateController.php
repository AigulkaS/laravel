<?php

namespace App\Http\Controllers\Booking;

use App\Http\Requests\Booking\UpdateRequest;
use App\Http\Resources\BookingResource;

class UpdateController extends BaseController
{
    public function __invoke(UpdateRequest $request)
    {
        
        $data = $request->validated();

        $bookings = $this->service->update($data);

        return $bookings instanceof String ? $bookings : BookingResource::collection($bookings);
    }

        
}
