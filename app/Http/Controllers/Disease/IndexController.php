<?php

namespace App\Http\Controllers\Disease;

use App\Http\Filters\DiseaseFilter;
use App\Http\Requests\Disease\FilterRequest;
use App\Http\Resources\DiseaseResource;
use App\Models\Disease;

class IndexController extends BaseController
{
    public function __invoke(FilterRequest $request)
    {

        $data = $request->validated();
        
        $filter = app()->make(DiseaseFilter::class, ['queryParams' => array_filter($data)]);
        
        $disease = Disease::filter($filter)->get();
        
        return DiseaseResource::collection($disease);
    }
  
}
