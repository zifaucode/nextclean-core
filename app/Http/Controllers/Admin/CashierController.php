<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CashierController extends Controller
{
    /**
     * Tampilkan halaman Point of Sale (POS) Kasir.
     */
    public function index()
    {
        $user = auth()->user();
        
        $products = \App\Models\Product::all(); // Products are shared per tenant
        
        if ($user->hasRole('kasir') || $user->hasRole('supervisor')) {
            $customers = \App\Models\Customer::where('outlet_id', $user->outlet_id)->get();
            $outlets = \App\Models\Outlet::where('id', $user->outlet_id)->get();
        } else {
            $customers = \App\Models\Customer::all();
            $outlets = \App\Models\Outlet::all();
        }
        
        return view('page.cashier.index', compact('products', 'customers', 'outlets'));
    }
}
