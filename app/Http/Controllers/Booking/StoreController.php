<?php

namespace App\Http\Controllers\Booking;

use App\Events\BookingsStoreEvent;
use App\Http\Requests\Booking\StoreRequest;
use App\Http\Resources\BookingResource;

// use App\Http\Requests\User\StoreRequest;
// use App\Http\Resources\UserResource;

class StoreController extends BaseController
{

    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();

        $bookings = $this->service->store($data);

        // return new BookingResource($booking);

        event(new BookingsStoreEvent($bookings instanceof String ? $bookings : BookingResource::collection($bookings)));


        return $bookings instanceof String ? $bookings : BookingResource::collection($bookings);

        // return redirect()->route('user.index');
    }




}
