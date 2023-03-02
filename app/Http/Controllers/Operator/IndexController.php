<?php

namespace App\Http\Controllers\Operator;

use App\Http\Filters\OperatorFilter;
use App\Http\Requests\Operator\FilterRequest;
use App\Http\Resources\OperatorResource;
use App\Models\Operator;

class IndexController extends BaseController
{
    public function __invoke(FilterRequest $request)
    {

        $data = $request->validated();

        $filter = app()->make(OperatorFilter::class, ['queryParams' => array_filter($data)]);

        $operators = Operator::filter($filter)->get();

        return OperatorResource::collection($operators);
    }

}
