<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Services\HospitalService;

class BaseController extends Controller
{

    public $service;

    public function __construct(HospitalService $service)
    {
        $this->service = $service;
    }
  
}
