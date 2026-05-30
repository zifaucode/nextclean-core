<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Product;
use App\Models\Outlet;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Expense;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Super Admin', 'slug' => 'super-admin'],
            ['name' => 'Admin Outlet', 'slug' => 'admin-outlet'],
            ['name' => 'Kasir', 'slug' => 'kasir'],
            ['name' => 'Supervisor', 'slug' => 'supervisor'],
            ['name' => 'Karyawan', 'slug' => 'karyawan'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(['slug' => $role['slug']], $role);
        }

        $superAdminRole = Role::where('slug', 'super-admin')->first();
        $adminOutletRole = Role::where('slug', 'admin-outlet')->first();
        $kasirRole = Role::where('slug', 'kasir')->first();

        // 1. Create Super Admin
        $superAdmin = User::updateOrCreate(
            ['email' => 'admin@nextclean.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('12345678'),
                'role_id' => $superAdminRole->id,
                'email_verified_at' => now(),
            ]
        );

        // 2. Create Tenant (Admin Outlet)
        $tenantAdmin = User::updateOrCreate(
            ['email' => 'owner@washup.com'],
            [
                'name' => 'Owner WashUP',
                'brand_name' => 'WashUP Laundry',
                'password' => Hash::make('12345678'),
                'role_id' => $adminOutletRole->id,
                'email_verified_at' => now(),
            ]
        );

        // 3. Create Outlets for Tenant
        $outlet1 = Outlet::withoutGlobalScopes()->firstOrCreate(['name' => 'OUTLET CUCI BOGOR'], [
            'user_id' => $tenantAdmin->id,
            'address' => 'Jl. Sudirman No. 1, Jakarta',
            'phone' => '081234567890',
            'open_time' => '08:00:00',
            'close_time' => '20:00:00',
            'additional_fee' => 0
        ]);

        $outlet2 = Outlet::withoutGlobalScopes()->firstOrCreate(['name' => 'OUTLET CUCI MALANG'], [
            'user_id' => $tenantAdmin->id,
            'address' => 'Jl. TB Simatupang No. 8, Jakarta',
            'phone' => '081234567891',
            'open_time' => '08:00:00',
            'close_time' => '20:00:00',
            'additional_fee' => 0
        ]);

        // 4. Create Kasir assigned to Outlet 1
        User::updateOrCreate(
            ['email' => 'kasir@washup.com'],
            [
                'name' => 'Kasir Outlet Bogor',
                'password' => Hash::make('12345678'),
                'role_id' => $kasirRole->id,
                'outlet_id' => $outlet1->id,
                'email_verified_at' => now(),
            ]
        );

        // 5. Dummy Default Products (Template for Superadmin, user_id = null)
        $defaultProducts = [
            ['name' => 'Cuci Kering', 'type' => 'Kiloan', 'price' => 6000, 'description' => 'Cuci bersih dan kering mesin', 'user_id' => null],
            ['name' => 'Cuci Setrika', 'type' => 'Kiloan', 'price' => 8000, 'description' => 'Cuci bersih, kering, dan setrika rapi', 'user_id' => null],
            ['name' => 'Setrika Saja', 'type' => 'Kiloan', 'price' => 5000, 'description' => 'Hanya jasa setrika', 'user_id' => null],
            ['name' => 'Selimut Kecil', 'type' => 'Satuan', 'price' => 15000, 'description' => 'Cuci selimut ukuran kecil', 'user_id' => null],
            ['name' => 'Selimut Besar', 'type' => 'Satuan', 'price' => 25000, 'description' => 'Cuci selimut ukuran besar', 'user_id' => null],
            ['name' => 'Bed Cover', 'type' => 'Satuan', 'price' => 35000, 'description' => 'Cuci bed cover', 'user_id' => null],
            ['name' => 'Karpet', 'type' => 'Satuan', 'price' => 15000, 'description' => 'Cuci karpet per meter persegi', 'user_id' => null],
            ['name' => 'Boneka Kecil', 'type' => 'Satuan', 'price' => 10000, 'description' => 'Cuci boneka ukuran kecil', 'user_id' => null],
            ['name' => 'Boneka Besar', 'type' => 'Satuan', 'price' => 25000, 'description' => 'Cuci boneka ukuran besar', 'user_id' => null],
        ];

        foreach ($defaultProducts as $product) {
            Product::withoutGlobalScopes()->firstOrCreate(['name' => $product['name'], 'user_id' => null], $product);
        }

        // 5b. Dummy Products for Tenant
        $products = [
            ['name' => 'Cuci Kering', 'type' => 'Kiloan', 'price' => 6000, 'description' => 'Cuci bersih dan kering mesin', 'user_id' => $tenantAdmin->id],
            ['name' => 'Cuci Setrika', 'type' => 'Kiloan', 'price' => 8000, 'description' => 'Cuci bersih, kering, dan setrika rapi', 'user_id' => $tenantAdmin->id],
            ['name' => 'Setrika Saja', 'type' => 'Kiloan', 'price' => 5000, 'description' => 'Hanya jasa setrika', 'user_id' => $tenantAdmin->id],
            ['name' => 'Selimut Kecil', 'type' => 'Satuan', 'price' => 15000, 'description' => 'Cuci selimut ukuran kecil', 'user_id' => $tenantAdmin->id],
            ['name' => 'Selimut Besar', 'type' => 'Satuan', 'price' => 25000, 'description' => 'Cuci selimut ukuran besar', 'user_id' => $tenantAdmin->id],
        ];

        foreach ($products as $product) {
            Product::withoutGlobalScopes()->firstOrCreate(['name' => $product['name'], 'user_id' => $tenantAdmin->id], $product);
        }

        // 6. Dummy Customers
        $customer1 = Customer::withoutGlobalScopes()->firstOrCreate(['phone' => '08999999999'], [
            'user_id' => $tenantAdmin->id,
            'name' => 'Krisna Maulana',
            'address' => 'Jl. Cibogo 2, Bogor',
            'member_code' => 'NC-001',
            'outlet_id' => $outlet1->id
        ]);

        // 7. Dummy Transactions
        $statuses = ['Diterima', 'Dicuci', 'Disetrika', 'Selesai', 'Diambil'];
        for ($i = 0; $i < 15; $i++) {
            $date = now()->subDays(rand(0, 7));
            $total = rand(20000, 150000);

            Transaction::withoutGlobalScopes()->create([
                'outlet_id' => rand(0, 1) ? $outlet1->id : $outlet2->id,
                'customer_id' => $customer1->id,
                'user_id' => $tenantAdmin->id, // Owner's ID
                'invoice_code' => 'INV-' . $date->format('Ymd') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'transaction_date' => $date,
                'total_price' => $total,
                'additional_fee' => 0,
                'grand_total' => $total,
                'payment_status' => rand(0, 1) ? 'Dibayar' : 'Belum Bayar',
                'status' => $statuses[array_rand($statuses)],
                'notes' => 'Dummy transaksi ' . $i
            ]);
        }

        // 8. Dummy Expenses
        Expense::withoutGlobalScopes()->create([
            'user_id' => $tenantAdmin->id,
            'outlet_id' => $outlet1->id,
            'name' => 'Beli Detergen 5kg',
            'amount' => 150000,
            'date' => now(),
            'description' => 'Untuk kebutuhan operasional minggu ini'
        ]);
    }
}
