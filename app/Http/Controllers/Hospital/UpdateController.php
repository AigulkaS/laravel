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
            $hospital = $this->service->update($hospital, $data);
            return $hospital instanceof String ? $hospital : new HospitalResource($hospital);
        } else {
            return response()->json([
                'message' => 'Access is denied',
            ], 403);
        }

        
    }

        
}
