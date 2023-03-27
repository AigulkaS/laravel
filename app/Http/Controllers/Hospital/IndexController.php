<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Filters\HospitalFilter;
use App\Http\Requests\Hospital\FilterRequest;
use App\Http\Resources\HospitalResource;
use App\Models\Hospital;

class IndexController extends BaseController
{
    public function __invoke(FilterRequest $request)
    {
        $data = $request->validated();
        
        $filter = app()->make(HospitalFilter::class, ['queryParams' => array_filter($data)]);
        
        $hospitals = Hospital::filter($filter)->get();
        
        return HospitalResource::collection($hospitals);
    }
  
}
