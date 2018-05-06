<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['name'];

    /**
     * Get the user that owns the task.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeTasksLeft($query){

        return $query->where('user_id', 1)->count();

    }

    public function getUserId(){


    	$user = $this->user();

    	return $user->id;
    }

    
}
