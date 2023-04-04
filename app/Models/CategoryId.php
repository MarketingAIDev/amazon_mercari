<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryId extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id', //mercari category_id
        'category',
        'all_category'
    ];
}
