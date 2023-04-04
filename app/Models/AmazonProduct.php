<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AmazonProduct extends Model
{
    use HasFactory;
    protected $table = 'amazon_products';

    protected $fillable = [
        'image',
        'user_id',
        'ASIN',
        'prime',
        'product',
        'attribute',
        'feature_1',
        'feature_2',
        'feature_3',
        'feature_4',
        'feature_5',
        'feature',
        'price',
        'r_price',
        'rank',
        'a_c_root',
        'a_c_sub',
        'a_c_tree',
        'p_length',
        'p_width',
        'p_height',
        'm_code'
    ];
}
