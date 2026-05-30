<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Outlet;
use Illuminate\Http\Request;
use App\Http\Requests\OutletRequest;

class OutletController extends Controller
{
    public function index()
    {
        return view('page.admin.outlet.index');
    }

    public function data(Request $request)
    {
        $query = Outlet::query();

        // 1. Search Logic
        if ($request->has('search') && $request->input('search.value') != '') {
            $searchValue = $request->input('search.value');
            $query->where(function($q) use ($searchValue) {
                $q->where('name', 'like', "%{$searchValue}%")
                  ->orWhere('phone', 'like', "%{$searchValue}%")
                  ->orWhere('address', 'like', "%{$searchValue}%");
            });
        }

        $recordsTotal = Outlet::count();
        $recordsFiltered = $query->count();

        // 2. Order Logic
        if ($request->has('order')) {
            $orderColumnIndex = $request->input('order.0.column');
            $orderDirection = $request->input('order.0.dir');
            $columns = $request->input('columns');
            $orderColumnName = $columns[$orderColumnIndex]['data'] ?? 'id';
            
            $sortableColumns = ['id', 'name', 'phone', 'address'];
            if (in_array($orderColumnName, $sortableColumns)) {
                 $query->orderBy($orderColumnName, $orderDirection);
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        // 3. Pagination Logic
        $limit = $request->input('length', 10);
        $offset = $request->input('start', 0);
        if ($limit == -1) {
            $outlets = $query->get();
        } else {
            $outlets = $query->offset($offset)->limit($limit)->get();
        }

        // 4. Format Data
        $data = [];
        foreach ($outlets as $index => $outlet) {
            $data[] = [
                'DT_RowIndex' => $offset + $index + 1,
                'id' => $outlet->id,
                'name' => $outlet->name,
                'phone' => $outlet->phone ?? '-',
                'address' => $outlet->address ?? '-',
                'open_time' => $outlet->open_time ? date('H:i', strtotime($outlet->open_time)) : '-',
                'close_time' => $outlet->close_time ? date('H:i', strtotime($outlet->close_time)) : '-',
                'action' => '
                    <div class="d-flex gap-1 justify-content-center">
                        <a href="'.route('admin.outlet.detail', $outlet->id).'" class="btn btn-sm btn-info text-white py-1 px-2" style="border-radius:0.375rem;" title="Lihat Detail"><i class="bi bi-eye"></i></a>
                        <a href="'.route('admin.outlet.edit', $outlet->id).'" class="btn btn-sm btn-primary py-1 px-2" style="border-radius:0.375rem;" title="Edit Outlet"><i class="bi bi-pencil"></i></a>
                        <form action="'.route('admin.outlet.destroy', $outlet->id).'" method="POST" class="d-inline delete-form">
                            '.csrf_field().'
                            '.method_field('DELETE').'
                            <button type="submit" class="btn btn-sm btn-danger py-1 px-2" style="border-radius:0.375rem;" title="Hapus Outlet"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
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
        return view('page.admin.outlet.create');
    }

    public function store(OutletRequest $request)
    {
        Outlet::create($request->validated());

        return redirect()->route('admin.outlet.index')->with('success', 'Outlet berhasil ditambahkan.');
    }

    public function edit(Outlet $outlet)
    {
        return view('page.admin.outlet.edit', compact('outlet'));
    }

    public function update(OutletRequest $request, Outlet $outlet)
    {
        $outlet->update($request->validated());

        return redirect()->route('admin.outlet.index')->with('success', 'Outlet berhasil diperbarui.');
    }

    public function destroy(Outlet $outlet)
    {
        $outlet->delete();

        return redirect()->route('admin.outlet.index')->with('success', 'Outlet berhasil dihapus.');
    }

    public function detail($id)
    {
        $outlet = Outlet::findOrFail($id);
        return view('page.admin.outlet.detail', compact('outlet'));
    }
}
