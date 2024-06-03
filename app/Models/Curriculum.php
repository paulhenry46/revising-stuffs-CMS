<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curriculum extends Model
{
    use HasFactory;

    public function schools()
    {
        return $this->belongsToMany(School::class);
    }

    public function levels()
    {
        return $this->hasMany(Level::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
