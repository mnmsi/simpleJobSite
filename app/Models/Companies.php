<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Companies extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 
        'userId', 
        'firstName', 
        'lastName', 
        'businessName', 
        'email', 
        'created_at', 
        'updated_at'
    ];
}
