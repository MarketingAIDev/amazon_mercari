<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'prime',
        'mark',
        'sentence',
        'etc',
        'price_cut',
        'price_cut_date',
        'price_reduction'
    ];
}
