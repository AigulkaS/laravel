<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Requests\Hospital\UpdateRequest;
use App\Http\Resources\HospitalResource;
use App\Models\Hospital;

class UpdateController extends BaseController
{
    public function __invoke(UpdateRequest $request, Hospital $hospital)
    {
        $user = auth('sanctum')->user();
        if ($user->role->name == 'admin' || $user->hospiatl_id == $hospital->id) {
            $data = $request->validated();

            $error = false;
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
            if ($error) {
                return response()->json([
                    'message' => 'wrong time data',
                ], 422);
            } else {
                $hospital = $this->service->update($hospital, $data);
                return $hospital instanceof String ? $hospital : new HospitalResource($hospital);
            }
        } else {
            return response()->json([
                'message' => 'Access is denied',
            ], 403);
        }


    }


}
