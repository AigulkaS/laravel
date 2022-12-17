<?php

namespace App\Http\Controllers\Disease;

use App\Models\Disease;

class DestroyController extends BaseController
{
    public function __invoke(Disease $disease)
    {
        return $this->service->delete($disease);
    }


}
