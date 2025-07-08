<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Userrole extends Model
{
    protected $table = 'user_roles';
    protected $fillable = ['user_id', 'role_id'];


    public function user()
    {
        return $this->belongsToMany(User::class);
    }

    public function role()
    {
        return $this->belongsToMany(Role::class);
    }
}