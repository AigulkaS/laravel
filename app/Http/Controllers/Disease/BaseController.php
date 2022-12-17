<?php

namespace App\Http\Controllers\Disease;

use App\Http\Controllers\Controller;
use App\Services\DiseaseService;

class BaseController extends Controller
{

    public $service;

    public function __construct(DiseaseService $service)
    {
        $this->service = $service;
    }
  
}
