<?php

namespace App\Http\Controllers\Permission;

use App\Http\Controllers\Controller;
use App\Services\PermissionService;

class BaseController extends Controller
{

    public $service;

    public function __construct(PermissionService $service)
    {
        $this->service = $service;
    }
  
}
