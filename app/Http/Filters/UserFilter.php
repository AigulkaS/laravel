<?php


namespace App\Http\Filters;


use Illuminate\Database\Eloquent\Builder;

class UserFilter extends AbstractFilter
{
    public const ID = 'id';
    public const ROLE_ID = 'role_id';
    public const HOSPITAL_ID = 'hospital_id';
    public const EMAIL = 'email';


    protected function getCallbacks(): array
    {
        return [
            self::ID => [$this, 'id'],
            self::ROLE_ID => [$this, 'roleId'],
            self::HOSPITAL_ID => [$this, 'hospitalId'],
            self::EMAIL => [$this, 'email'],
        ];
    }

    public function id(Builder $builder, $value)
    {
        $builder->where('id', $value);
    }

    public function roleId(Builder $builder, $value)
    {
        $builder->where('role_id', $value);
    }

    public function hospitalId(Builder $builder, $value)
    {
        $builder->where('hospital_id', $value);
    }

    public function email(Builder $builder, $value)
    {
        $builder->where('email', $value);
    }
}