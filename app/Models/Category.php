<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'a_c_root',
        'a_c_sub',
        // 'a_c_tree',
        'm_category',  //all categories
        // 'm_category_name',
    ];
}
