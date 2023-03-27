<?php

namespace App\Http\Controllers\Operator;

use App\Events\OperatorsUpdateEvent;
use App\Http\Filters\OperatorFilter;
use App\Http\Requests\Operator\UpdateRequest;
use App\Http\Resources\OperatorResource;
use App\Models\Operator;

class UpdateController extends BaseController
{
    public function __invoke(UpdateRequest $request)
    {
        $data = $request->validated();

        $operator = $this->service->update($data);

        event(new OperatorsUpdateEvent(new OperatorResource($operator)));

        return new OperatorResource($operator);
    }


}
