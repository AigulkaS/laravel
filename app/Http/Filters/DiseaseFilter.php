<?php


namespace App\Http\Filters;


use Illuminate\Database\Eloquent\Builder;

class DiseaseFilter extends AbstractFilter
{
    public const ID = 'id';
    public const NAME = 'name';
    public const CODE = 'code';


    protected function getCallbacks(): array
    {
        return [
            self::ID => [$this, 'id'],
            self::NAME => [$this, 'name'],
            self::CODE => [$this, 'code'],
        ];
    }

    public function id(Builder $builder, $value)
    {
        $builder->where('id', $value);
    }

    public function name(Builder $builder, $value)
    {
        $builder->where('name', 'like', "%{$value}%");
    }

    public function code(Builder $builder, $value)
    {
        $builder->where('code', $value);
    }
}