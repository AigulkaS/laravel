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

        $bookingsInfo = $this->service->storeNew($data);

        if ($bookingsInfo instanceof String) {
            return $bookingsInfo;
        } else {
            $messages = $this->service->getMess($bookingsInfo['hours'], $bookingsInfo['bookings']);
            return response()->json([
                'bookings' => BookingResource::collection($bookingsInfo['bookings']),
                'messages' => $messages,
            ], 200);
        }
        // event(new BookingsStoreEvent($bookings instanceof String ? $bookings : BookingResource::collection($bookings)));
        // return $bookings instanceof String ? $bookings : BookingResource::collection($bookings);

    }




}
