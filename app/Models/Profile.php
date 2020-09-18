<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 
        'userId', 
        'firstName', 
        'lastName', 
        'email',
        'picture', 
        'resume', 
        'skills', 
        'created_at', 
        'updated_at'
    ];
}
