<?php

namespace App\Http\Controllers\Today;

use App\Http\Controllers\Controller;
use App\Services\TodayService;

class BaseController extends Controller
{

    public $service;

    public function __construct(TodayService $service)
    {
        $this->service = $service;
    }
  
}
