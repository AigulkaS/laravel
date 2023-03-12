<?php

namespace App\Http\Controllers\Hospital;

use App\Models\HospitalRoom;

class DestroyRoomController extends BaseController
{
    public function __invoke(HospitalRoom $hospitalRoom)
    {
        $user = auth('sanctum')->user();
        if ($user->role->name == 'admin' || $user->hospiatl_id == $hospitalRoom->hospital_id) {
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
        } else {
            return response()->json([
                'message' => 'Access is denied',
            ], 403);
        }
        
    }


}
