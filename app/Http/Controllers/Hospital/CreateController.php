<?php

namespace App\Http\Controllers\Hospital;


class CreateController extends BaseController
{
    public function __invoke()
    {
        return response()->json([], 200);
    }

}
