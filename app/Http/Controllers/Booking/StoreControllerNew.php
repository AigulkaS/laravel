<?php

namespace App\Http\Controllers\Booking;

use App\Events\BookingsStoreEvent;
use App\Http\Requests\Booking\StoreRequest;
use App\Http\Requests\Booking\StoreRequestNew;
use App\Http\Resources\BookingResource;

// use App\Http\Requests\User\StoreRequest;
// use App\Http\Resources\UserResource;

class StoreControllerNew extends BaseController
{

    public function __invoke(StoreRequestNew $request)
    {
        $data = $request->validated();

        $bookings = $this->service->storeNew($data);

        $message = $this->service->getMess($bookings);

        // event(new BookingsStoreEvent($bookings instanceof String ? $bookings : BookingResource::collection($bookings)));

        return $bookings instanceof String ? $bookings : BookingResource::collection($bookings);

    }




}
