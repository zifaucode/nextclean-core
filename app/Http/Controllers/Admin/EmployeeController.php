<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Outlet;
use App\Models\User;
use App\Http\Requests\EmployeeRequest;

class EmployeeController extends Controller
{
    public function index()
    {
        return view('page.admin.employee.index');
    }

    public function data(\Illuminate\Http\Request $request)
    {
        $query = Employee::with(['outlet', 'user'])->select('employees.*');

        // 1. Search Logic
        if ($request->has('search') && $request->input('search.value') != '') {
            $searchValue = $request->input('search.value');
            $query->where(function($q) use ($searchValue) {
                $q->where('employee_code', 'like', "%{$searchValue}%")
                  ->orWhere('position', 'like', "%{$searchValue}%")
                  ->orWhereHas('user', function($u) use ($searchValue) {
                      $u->where('name', 'like', "%{$searchValue}%");
                  })
                  ->orWhereHas('outlet', function($o) use ($searchValue) {
                      $o->where('name', 'like', "%{$searchValue}%");
                  });
            });
        }

        $recordsTotal = Employee::count();
        $recordsFiltered = $query->count();

        // 2. Order Logic
        if ($request->has('order')) {
            $orderColumnIndex = $request->input('order.0.column');
            $orderDirection = $request->input('order.0.dir');
            $columns = $request->input('columns');
            $orderColumnName = $columns[$orderColumnIndex]['data'] ?? 'id';
            
            $sortableColumns = ['employee_code', 'position', 'salary'];
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
            $employees = $query->get();
        } else {
            $employees = $query->offset($offset)->limit($limit)->get();
        }

        // 4. Format Data
        $data = [];
        foreach ($employees as $index => $employee) {
            $data[] = [
                'DT_RowIndex' => $offset + $index + 1,
                'employee_code' => $employee->employee_code,
                'user_name' => $employee->user->name ?? '-',
                'position' => $employee->position ?? '-',
                'salary' => 'Rp ' . number_format($employee->salary ?? 0, 0, ',', '.'),
                'outlet_name' => $employee->outlet->name ?? '-',
                'action' => '
                    <div class="d-flex gap-1 justify-content-center">
                        <a href="'.route('admin.employee.edit', $employee->id).'" class="btn btn-sm btn-primary py-1 px-2" style="border-radius:0.375rem;" title="Edit Karyawan"><i class="bi bi-pencil"></i></a>
                        <form action="'.route('admin.employee.destroy', $employee->id).'" method="POST" class="d-inline delete-form">
                            '.csrf_field().'
                            '.method_field('DELETE').'
                            <button type="submit" class="btn btn-sm btn-danger py-1 px-2" style="border-radius:0.375rem;" title="Hapus Karyawan"><i class="bi bi-trash"></i></button>
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

    public function generateCode()
    {
        do {
            $code = 'EMP-' . strtoupper(bin2hex(random_bytes(3)));
        } while (Employee::where('employee_code', $code)->exists());

        return response()->json(['code' => $code]);
    }

    public function create()
    {
        $outlets = Outlet::all();
        $users = User::all();
        return view('page.admin.employee.create', compact('outlets', 'users'));
    }

    public function store(EmployeeRequest $request)
    {
        Employee::create($request->validated());

        return redirect()->route('admin.employee.index')->with('success', 'Karyawan berhasil ditambahkan.');
    }

    public function edit(Employee $employee)
    {
        $outlets = Outlet::all();
        $users = User::all();
        return view('page.admin.employee.edit', compact('employee', 'outlets', 'users'));
    }

    public function update(EmployeeRequest $request, Employee $employee)
    {
        $employee->update($request->validated());

        return redirect()->route('admin.employee.index')->with('success', 'Data karyawan berhasil diperbarui.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->route('admin.employee.index')->with('success', 'Data karyawan berhasil dihapus.');
    }
}
