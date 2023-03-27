<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Services\OperatorService;

class BaseController extends Controller
{

    public $service;

    public function __construct(OperatorService $service)
    {
        $this->service = $service;
    }
  
}
