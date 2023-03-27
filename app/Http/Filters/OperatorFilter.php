<?php


namespace App\Http\Filters;


use Illuminate\Database\Eloquent\Builder;

class OperatorFilter extends AbstractFilter
{
    public const HOSPITAL_ID = 'hospital_id';
    public const SURGEON_ID = 'surgeon_id';
    public const CARDIOLOGIST_ID = 'cardiologist_id';
    public const DATE = 'date';


    protected function getCallbacks(): array
    {
        return [
            self::HOSPITAL_ID => [$this, 'hospitalId'],
            self::SURGEON_ID => [$this, 'surgeonId'],
            self::CARDIOLOGIST_ID => [$this, 'cardiologistId'],
            self::DATE => [$this, 'date'],
        ];
    }

    public function hospitalId(Builder $builder, $value)
    {
        $builder->where('hospital_id', $value);
    }

    public function surgeonId(Builder $builder, $value)
    {
        $builder->where('surgeon_id', $value);
    }

    public function cardiologistId(Builder $builder, $value)
    {
        $builder->where('cardiologist_id', $value);
    }

    public function date(Builder $builder, $value)
    {
        $builder->where('date', $value);
    }
}