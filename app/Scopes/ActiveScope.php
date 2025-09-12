<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ActiveScope implements Scope
{
    //boot method 

    public function apply(Builder $builder, Model $model)
    {
        $builder->where('status', 'active');
    }
}
