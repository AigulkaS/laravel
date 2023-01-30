<?php

namespace App\Http\Controllers\Disease;

use App\Http\Requests\Disease\StoreRequest;
use App\Http\Resources\DiseaseResource;


class StoreController extends BaseController
{

    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();

        $disease = $this->service->store($data);

        return new DiseaseResource($disease);
    }




}
