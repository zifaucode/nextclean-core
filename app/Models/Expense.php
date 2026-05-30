<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasTenant;

class Expense extends Model
{
    use SoftDeletes, HasTenant;

    protected $fillable = [
        'user_id',
        'outlet_id',
        'name',
        'amount',
        'date',
        'description',
    ];

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }
}
