<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';
    protected $fillable = ['name'];

    public function users()
    {
        return $this->hasManyThrough(
            User::class,
            Userrole::class,
            'role_id',
            'id',
            'id',
            'user_id'
        );
    }
    // public function userroles()
    // {
    //     return $this->hasMany(Userrole::class);
    // }
}
