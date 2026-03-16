<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Store\Models\Product as BaseProduct;

class CustomProduct extends BaseProduct
{
    protected $table = 'products';
}
