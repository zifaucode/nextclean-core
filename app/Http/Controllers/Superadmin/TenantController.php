<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = User::whereHas('role', function($q) {
            $q->where('slug', 'admin-outlet');
        })->get();

        return view('page.superadmin.tenant.index', compact('tenants'));
    }

    public function create()
    {
        return view('page.superadmin.tenant.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'brand_name' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            // Get Admin Role
            $adminRole = \App\Models\Role::where('slug', 'admin-outlet')->first();

            $admin = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $adminRole->id,
                'brand_name' => $request->brand_name,
            ]);

            // Auto-Duplicate default products without global scope
            $defaultProducts = [
                ['name' => 'Cuci Kering', 'type' => 'Kiloan', 'price' => 6000, 'description' => 'Cuci bersih dan kering mesin'],
                ['name' => 'Cuci Setrika', 'type' => 'Kiloan', 'price' => 8000, 'description' => 'Cuci bersih, kering, dan setrika rapi'],
                ['name' => 'Setrika Saja', 'type' => 'Kiloan', 'price' => 5000, 'description' => 'Hanya jasa setrika'],
                ['name' => 'Selimut Kecil', 'type' => 'Satuan', 'price' => 15000, 'description' => 'Cuci selimut ukuran kecil'],
                ['name' => 'Selimut Besar', 'type' => 'Satuan', 'price' => 25000, 'description' => 'Cuci selimut ukuran besar'],
            ];

            foreach ($defaultProducts as $dp) {
                // Must insert via DB facade or disable HasTenant temporarily?
                // Actually if we just pass user_id, it will be fine.
                // Wait, Product::create might trigger HasTenant which overrides user_id if we are logged in as superadmin.
                // HasTenant does: if (empty($model->user_id)... 
                // So if we pass user_id explicitly, it will not be overridden!
                Product::create([
                    'user_id' => $admin->id,
                    'name' => $dp['name'],
                    'type' => $dp['type'],
                    'price' => $dp['price'],
                    'description' => $dp['description']
                ]);
            }

            DB::commit();
            return redirect()->route('superadmin.tenant.index')->with('success', 'Tenant Admin berhasil dibuat beserta produk default.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat Tenant: ' . $e->getMessage());
        }
    }
}
