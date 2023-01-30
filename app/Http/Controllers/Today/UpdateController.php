<?php

namespace App\Http\Controllers\Today;

use App\Events\TodaysUpdateEvent;
use App\Http\Filters\TodayFilter;
use App\Http\Requests\Today\UpdateRequest;
use App\Http\Resources\TodayResource;
use App\Models\Today;

class UpdateController extends BaseController
{
    public function __invoke(UpdateRequest $request)
    {
        $data = $request->validated();

        $dataFilter = [
            'hospital_id' => $data['hospital_id']
        ];

        $filter = app()->make(TodayFilter::class, ['queryParams' => array_filter($dataFilter)]);

        $todays = Today::filter($filter)->get();

        $today = $this->service->update($data, $todays[0]);

        event(new TodaysUpdateEvent(new TodayResource($today)));

        return new TodayResource($today);
    }


}
