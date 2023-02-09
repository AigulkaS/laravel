<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Resources\HospitalResource;
use App\Models\Hospital;

class EditController extends BaseController
{
    public function __invoke(Hospital $hospital)
    {
        return new HospitalResource($hospital);
    }
}
