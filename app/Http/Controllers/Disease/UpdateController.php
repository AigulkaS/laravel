<?php

namespace App\Http\Controllers\Disease;

use App\Http\Requests\Disease\UpdateRequest;
use App\Http\Resources\DiseaseResource;
use App\Models\Disease;

class UpdateController extends BaseController
{
    public function __invoke(UpdateRequest $request, Disease $disease)
    {
        $data = $request->validated();

        $disease = $this->service->update($disease, $data);
            
        return new DiseaseResource($disease);
    }

        
}
