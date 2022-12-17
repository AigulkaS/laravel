<?php

namespace App\Http\Controllers\Disease;

use App\Http\Resources\DiseaseResource;
use App\Models\Disease;

class EditController extends BaseController
{
    public function __invoke(Disease $disease)
    {
        return new DiseaseResource($disease);
    }
}
