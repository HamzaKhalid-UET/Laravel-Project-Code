<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profiles extends Model
{
    protected $table = 'profiles';
    protected $fillable = ['user_id', 'image', 'address', 'phone', 'gender', 'dob'];
    // public $timestamps = false;  
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
