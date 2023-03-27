<?php


namespace App\Http\Filters;


use Illuminate\Database\Eloquent\Builder;

class HospitalFilter extends AbstractFilter
{
    public const ID = 'id';
    public const TYPE = 'type';
    public const FULL_NAME = 'full_name';
    public const SHORT_NAME = 'short_name';
    public const ADDRESS = 'address';


    protected function getCallbacks(): array
    {
        return [
            self::ID => [$this, 'id'],
            self::TYPE => [$this, 'type'],
            self::FULL_NAME => [$this, 'fullName'],
            self::SHORT_NAME => [$this, 'shortName'],
            self::ADDRESS => [$this, 'address'],
        ];
    }

    public function id(Builder $builder, $value)
    {
        $builder->where('id', $value);
    }

    public function type(Builder $builder, $value)
    {
        $builder->where('type', $value);
    }

    public function fullName(Builder $builder, $value)
    {
        $builder->where('full_name', 'like', "%{$value}%");
    }

    public function shortName(Builder $builder, $value)
    {
        $builder->where('short_name', $value);
    }

    public function address(Builder $builder, $value)
    {
        $builder->where('address', 'like', "%{$value}%");
    }
}