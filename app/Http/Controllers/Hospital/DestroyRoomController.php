<?php

namespace App\Http\Controllers\Hospital;

use App\Models\HospitalRoom;

class DestroyRoomController extends BaseController
{
    public function __invoke(HospitalRoom $hospitalRoom)
    {
        try {
            $hospitalRoom->delete();
            return response()->json([
                "msg" => "delete successfully",
            ], 200);
        } catch(\Exception $exception) {
            return response()->json([
                "msg" => $exception->getMessage(),
            ], 419);
        }
    }


}
