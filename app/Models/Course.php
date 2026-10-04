<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $guarded = [];

    public function chapters()
    {
        return $this->hasMany(Chapter::class)->orderBy('order');
    }

    public function threads()
    {
        return $this->hasMany(Thread::class);
    }
}