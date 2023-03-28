<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Requests\Hospital\StoreRequest;
use App\Http\Resources\HospitalResource;

class StoreController extends BaseController
{

    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();

        $error = false;
        if (array_key_exists('hospital_rooms', $data)) {
            $rooms = $data['hospital_rooms'];
            foreach ($rooms as $room) {
                if (array_key_exists('start', $room)) {
                    if (!array_key_exists('end', $room)) {
                        $error = true;
                        break;
                    }
                    $arr = explode( ':', $room['start']);
                    $start = $arr[0];
                    $arr = explode( ':', $room['end']);
                    $end = $arr[0];
                    if ($end <= $start) {
                        $error = true;
                        break;
                    }
                } else {
                    if (array_key_exists('end', $room)) {
                        $error = true;
                        break;
                    }
                }
                
            }
        }
        
        if ($error) {
            return response()->json([
                'messages' => 'wrong time data',
            ], 422);
        } else {
            $hospital = $this->service->store($data);
            return $hospital instanceof String ? $hospital : new HospitalResource($hospital);
        }
        
    }

}
