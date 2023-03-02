<?php

namespace App\Services;

use App\Models\Operator;

class OperatorService {

    public function update($data) {
        
        $operator = Operator::where('date', $data['date'])
                    ->where('hospital_id', $data['hospital_id'])
                    ->first();
                   
        if ($operator == null) {
            $operator = Operator::create($data);
        } else {
            $operator->update($data);
            $operator->fresh();
        }

        return $operator;
    }
}