<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasTenant;

class Employee extends Model
{
    use SoftDeletes, HasTenant;

    protected $fillable = [
        'user_id',
        'outlet_id',
        'employee_code',
        'position',
        'salary',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }
}
