<?php

namespace App\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use App\Services\BookingService;

class BaseController extends Controller
{

    public $service;

    public function __construct(BookingService $service)
    {
        $this->service = $service;
    }
  
}
