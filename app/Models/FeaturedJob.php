<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeaturedJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'job_id',

    ];
}
