<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use App\Services\RoleService;

class BaseController extends Controller
{

    public $service;

    public function __construct(RoleService $service)
    {
        $this->service = $service;
    }
  
}
