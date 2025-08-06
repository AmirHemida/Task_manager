<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    function task()
    {
        return $this->belongsToMany(Task::class,'category_tasks');
    }
}
