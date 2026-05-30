<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\HasTenant;

class Transaction extends Model
{
    use SoftDeletes, HasTenant;

    protected $fillable = [
        'outlet_id',
        'customer_id',
        'user_id',
        'invoice_code',
        'transaction_date',
        'estimate_date',
        'pickup_date',
        'total_price',
        'additional_fee',
        'discount_amount',
        'grand_total',
        'status',
        'payment_status',
        'notes',
    ];

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class, 'transaction_id');
    }
}
