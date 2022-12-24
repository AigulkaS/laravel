<?php


namespace App\Http\Filters;


use Illuminate\Database\Eloquent\Builder;

class BookingFilter extends AbstractFilter
{

    public const STATUS = 'status';
    public const HOSPITAL_ID = 'hospital_id';
    public const SURGEON_ID = 'surgeon_id';
    public const CARDIOLOGIST_ID = 'cardiologist_id';
    // public const START_TIME = 'start_time';
    // public const END_TIME = 'end_time';
    public const DATE = 'date';
    public const PERIOD = 'period';


    protected function getCallbacks(): array
    {
        return [
            self::STATUS => [$this, 'status'],
            self::HOSPITAL_ID => [$this, 'hospitalId'],
            self::SURGEON_ID => [$this, 'surgeonId'],
            self::CARDIOLOGIST_ID => [$this, 'cardiologistId'],
            // self::START_TIME => [$this, 'startTime'],
            // self::END_TIME => [$this, 'endTime'],
            self::DATE => [$this, 'date'],
            self::PERIOD => [$this, 'betweenDateTime'],
        ];
    }

    public function status(Builder $builder, $value)
    {
        $builder->where('status', $value);
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

    // public function startTime(Builder $builder, $value)
    // {
    //     $builder->where('start_time', $value);
    // }

    // public function endTime(Builder $builder, $value)
    // {
    //     $builder->where('end_time', $value);
    // }

    public function date(Builder $builder, $dateValue)
    {
        // $builder
        //     ->whereDate('start_time', $dateValue)
        //     ->orWhereDate('end_time', $dateValue);

        $builder->where(function($query) use ($dateValue) {
            $query->whereDate('start_time', $dateValue)
                    ->orWhereDate('end_time', $dateValue);
            });
    }

    public function betweenDateTime(Builder $builder, $dateTimeArr)
    {
        $builder->where(function($query) use ($dateTimeArr) {
            $query->whereBetween('start_time', $dateTimeArr)
                  ->orWhereBetween('end_time', $dateTimeArr);
        });
    }

}