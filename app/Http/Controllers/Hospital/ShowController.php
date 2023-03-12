<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Resources\HospitalResource;
use App\Models\Hospital;

class ShowController extends BaseController
{
    public function __invoke(Hospital $hospital)
    {
        $user = auth('sanctum')->user();
        if ($user->role->name == 'admin' || $user->hospiatl_id == $hospital->id) {
            return new HospitalResource($hospital);
        } else {
            return response()->json([
                'message' => 'Access is denied',
            ], 403);
        }
    }         
}
