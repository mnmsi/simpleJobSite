<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobs extends Model
{
    use HasFactory;

    protected $fillable = [
        'id', 
        'companyId', 
        'title', 
        'description', 
        'salary', 
        'location', 
        'country', 
        'created_at', 
        'updated_at'
    ];
}
