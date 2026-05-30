<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasTenant;

class Outlet extends Model
{
    use SoftDeletes, HasTenant;

    protected $fillable = [
        'user_id',
        'name',
        'address',
        'phone',
        'open_time',
        'close_time',
        'additional_fee',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'outlet_id');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'outlet_id');
    }

    public function customers()
    {
        return $this->hasMany(Customer::class, 'outlet_id');
    }
}
