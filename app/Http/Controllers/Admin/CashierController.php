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
    public function storeCustomer(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'outlet_id' => 'required|exists:outlets,id',
        ]);

        $user = auth()->user();

        // Check if user is authorized to create customer for this outlet
        if ($user->hasRole('kasir') || $user->hasRole('supervisor')) {
            if ($user->outlet_id != $request->outlet_id) {
                return response()->json(['message' => 'Unauthorized outlet'], 403);
            }
        }

        // Generate Member Code: CUST-YYYYMMDD-XXXX
        $date = date('Ymd');
        $lastCustomer = \App\Models\Customer::withoutGlobalScope(\App\Models\Scopes\TenantScope::class)
                            ->where('member_code', 'like', "CUST-{$date}-%")
                            ->orderBy('id', 'desc')
                            ->first();
        if ($lastCustomer) {
            $lastNumber = (int) substr($lastCustomer->member_code, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        $memberCode = "CUST-{$date}-" . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        $customer = \App\Models\Customer::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'outlet_id' => $request->outlet_id,
            'member_code' => $memberCode,
        ]);

        return response()->json([
            'success' => true,
            'customer' => $customer,
            'message' => 'Pelanggan berhasil ditambahkan.'
        ]);
    }
}
