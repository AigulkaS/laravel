<?php

namespace App\Services;

use App\Models\Today;

class TodayService {
    public function update($data, $today) {
        $today->update($data);
        return $today->fresh();
    }
}