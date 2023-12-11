<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'job_id',
        'candidate_id',
        'status',
        'name',
        'email',
        'cover_letter',
        'resume',
        'created_at',
    ];
}
