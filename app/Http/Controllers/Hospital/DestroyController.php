<?php

namespace App\Http\Controllers\Hospital;

use App\Models\Hospital;

class DestroyController extends BaseController
{
    public function __invoke(Hospital $hospital)
    {
        return $this->service->delete($hospital);
    }


}
