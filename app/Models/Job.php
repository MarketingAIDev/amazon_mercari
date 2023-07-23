<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'corporation',
        'job_content',
        'service_type',
        'salary',
        'salary_remarks',
        'treatment',
        'hours',
        'vacation',
        'long_spec',
        'access',
        'url',
    ];
}
