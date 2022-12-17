<?php

namespace App\Http\Controllers\Disease;


class CreateController extends BaseController
{
    public function __invoke()
    {
        return response()->json([], 200);
    }

}
