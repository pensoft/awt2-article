<?php

namespace App\Transformers;

use App\Models\Role;
use League\Fractal\TransformerAbstract;

class RoleTransformer extends TransformerAbstract
{
    public function transform(Role $role){
        return [
            'id' => (int) $role->id,
            'name' => (string) $role->name,
            'role' => (string) $role->role,
        ];
    }
}
