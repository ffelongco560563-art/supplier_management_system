<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    
    const DELETED_AT = 'archive';

    protected $fillable = [
        'name',
        'category',
        'litre',
        'price_unit',
        'stock',
        'expiration_date', // Added to allow saving
        'image_path',      // Added to allow saving
        'archive'
    ];
}