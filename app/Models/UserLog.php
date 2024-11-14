<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    use HasFactory;

    // Define the fillable attributes to allow mass assignment
    protected $fillable = [
        'user_id',     // The user associated with the log
        'log_level',   // The log level (e.g., 'success', 'info', 'warning', 'error')
        'description', // The description of the event or action
    ];

    // Define a relationship to the User model (optional, if you want to access the user related to the log)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

