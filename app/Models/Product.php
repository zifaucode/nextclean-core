<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasTenant;

class Product extends Model
{
    use SoftDeletes, HasTenant;

    protected $fillable = [
        'user_id',
        'name',
        'type',
        'price',
        'description',
    ];
}
