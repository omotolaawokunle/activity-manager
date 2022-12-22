<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'image', 'activity_date', 'global_activity_id'];

    protected $casts = ['activity_date' => 'date'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_activities');
    }
}
