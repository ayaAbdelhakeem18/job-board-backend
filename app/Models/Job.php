<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'title',
        'salary',
        'location',
        'requirements',
        'description',
        'applicants_number',
        'type',
        'category_id',
        'employer_id',
        'created_at','updated_at'
    ];
}
