<?php


namespace App\Http\Filters;


use Illuminate\Database\Eloquent\Builder;

class RoleFilter extends AbstractFilter
{
    public const ID = 'id';


    protected function getCallbacks(): array
    {
        return [
            self::ID => [$this, 'id'],
        ];
    }

    public function id(Builder $builder, $value)
    {
        $builder->where('id', $value);
    }
}