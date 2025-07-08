<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class user extends Authenticatable
{
    protected $table = 'users';
    protected $fillable = ['name', 'email', 'password','image'];
    public $timestamps = false;


    public function profiles()
    {
        return $this->hasOne(Profiles::class);



    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function roles()
    {
        return $this->hasManyThrough(
            Role::class,
            Userrole::class,
            'user_id',
            'id',
            'id',
            'role_id'
        );
    }
}

