<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Outlet;
use App\Http\Requests\CustomerRequest;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        return view('page.admin.customer.index');
    }

    public function data(Request $request)
    {
        $query = Customer::with('outlet')->select('customers.*');

        // Search logic
        if ($request->has('search') && $request->input('search.value') != '') {
            $searchValue = $request->input('search.value');
            $query->where(function($q) use ($searchValue) {
                $q->where('name', 'like', "%{$searchValue}%")
                  ->orWhere('phone', 'like', "%{$searchValue}%")
                  ->orWhere('member_code', 'like', "%{$searchValue}%");
            });
        }

        $recordsTotal = Customer::count();
        $recordsFiltered = $query->count();

        // Order logic
        if ($request->has('order')) {
            $orderColumnIndex = $request->input('order.0.column');
            $orderDirection = $request->input('order.0.dir');
            $columns = $request->input('columns');
            $orderColumnName = $columns[$orderColumnIndex]['data'] ?? 'id';
            
            if (!in_array($orderColumnName, ['action', 'outlet_name'])) {
                 $query->orderBy($orderColumnName, $orderDirection);
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        $limit = $request->input('length', 10);
        if ($limit == -1) {
            $limit = $recordsFiltered;
        }
        $offset = $request->input('start', 0);

        $customers = $query->offset($offset)->limit($limit)->get();

        $data = [];
        foreach ($customers as $customer) {
            $data[] = [
                'id' => $customer->id,
                'name' => $customer->name,
                'phone' => $customer->phone,
                'member_code' => $customer->member_code,
                'outlet_name' => $customer->outlet ? $customer->outlet->name : '-',
                'action' => '
                    <a href="'.route('admin.customer.edit', $customer->id).'" class="btn btn-sm btn-primary py-1 px-2" style="border-radius:0.375rem;"><i class="bi bi-pencil fs-6"></i></a>
                    <form action="'.route('admin.customer.destroy', $customer->id).'" method="POST" class="d-inline delete-form">
                        '.csrf_field().'
                        '.method_field('DELETE').'
                        <button type="submit" class="btn btn-sm btn-danger py-1 px-2" style="border-radius:0.375rem;"><i class="bi bi-trash fs-6"></i></button>
                    </form>
                '
            ];
        }

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public function create()
    {
        $outlets = Outlet::all();
        return view('page.admin.customer.create', compact('outlets'));
    }

    public function generateCode()
    {
        do {
            $code = 'MC-' . strtoupper(bin2hex(random_bytes(3)));
        } while (Customer::where('member_code', $code)->exists());

        return response()->json(['code' => $code]);
    }

    public function store(CustomerRequest $request)
    {
        Customer::create($request->validated());

        return redirect()->route('admin.customer.index')->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function edit(Customer $customer)
    {
        $outlets = Outlet::all();
        return view('page.admin.customer.edit', compact('customer', 'outlets'));
    }

    public function update(CustomerRequest $request, Customer $customer)
    {
        $customer->update($request->validated());

        return redirect()->route('admin.customer.index')->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('admin.customer.index')->with('success', 'Data pelanggan berhasil dihapus.');
    }
}
