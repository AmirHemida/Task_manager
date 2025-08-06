<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $guarded=['id'];
    function user()
    {
        return $this->belongsTo(User::class);
    }
    function category()
    {
        return $this->belongsToMany(Category::class,'category_tasks');
    }
    function favouriteuser()
    {
        return $this->belongsToMany(User::class,'favourites','user_id','task_id');
    }
}
