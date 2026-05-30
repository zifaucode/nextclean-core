<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasTenant;

class Customer extends Model
{
    use SoftDeletes, HasTenant;

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'address',
        'member_code',
        'outlet_id',
    ];

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }
}
