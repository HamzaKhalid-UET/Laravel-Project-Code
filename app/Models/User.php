<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class user extends Model
{

    protected $table = 'users';
    protected $fillable = ['name', 'email', 'password','image'];
}
