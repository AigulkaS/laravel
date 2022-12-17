<?php

namespace App\Http\Controllers\Today;

use App\Http\Filters\TodayFilter;
use App\Http\Requests\Today\FilterRequest;
use App\Http\Resources\TodayResource;
use App\Models\Today;

class IndexController extends BaseController
{
    public function __invoke(FilterRequest $request)
    {

        $data = $request->validated();
        
        $filter = app()->make(TodayFilter::class, ['queryParams' => array_filter($data)]);
        
        $today = Today::filter($filter)->get();
        
        return TodayResource::collection($today);
    }
  
}
