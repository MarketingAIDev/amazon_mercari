<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exhibition extends Model
{
    use HasFactory;

    protected $fillable = [
        'amazon_id',
        'user_id',
        'ASIN',
        'product',
        'image',
        'prime',
        'feature',
        'price',
        'e_price',
        'a_category',
        'm_category',
        'm_category_id',
        'postage',
        'etc',
        'exclusion',
        'profit'
    ];
}
