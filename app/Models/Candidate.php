<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'id',
        'user_id',
        'firstname',
        'lastname',
        'title',
        'location',
        'overview',
        'resume',
        'skills',
        'created_at',
        'updated_at',
    ];
}
