<?php

namespace App\Http\Controllers\Booking;

use App\Events\BookingsUpdateEvent;
use App\Http\Requests\Booking\UpdateRequest;
use App\Http\Resources\BookingResource;

class UpdateController extends BaseController
{
    public function __invoke(UpdateRequest $request)
    {

        $data = $request->validated();

        $bookings = $this->service->update($data);

        // event(new BookingsUpdateEvent($bookings instanceof String ? $bookings : BookingResource::collection($bookings)));
         event(new BookingsUpdateEvent($bookings instanceof String ? $bookings : BookingResource::collection($bookings)));


        return $bookings instanceof String ? $bookings : BookingResource::collection($bookings);
    }


}
