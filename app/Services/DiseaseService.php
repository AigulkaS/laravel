<?php

namespace App\Services;

use App\Models\Disease;
use Illuminate\Support\Facades\DB;

class DiseaseService {

    public function store($data) {
        $disease = Disease::create($data);
        return $disease;
    }

    public function update($disease, $data) {
        $disease->update($data);
        return $disease->fresh();
    }

    public function delete($disease) {
        try {
            $disease->delete();
        } catch(\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        } 
        return "delete successfully";
    }
}