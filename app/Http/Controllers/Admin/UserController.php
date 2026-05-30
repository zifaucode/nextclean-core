<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Outlet;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('page.admin.user.index');
    }

    public function data(Request $request)
    {
        $query = User::with(['role', 'outlet'])->select('users.*');

        // 1. Search Logic
        if ($request->has('search') && $request->input('search.value') != '') {
            $searchValue = $request->input('search.value');
            $query->where(function($q) use ($searchValue) {
                $q->where('name', 'like', "%{$searchValue}%")
                  ->orWhere('email', 'like', "%{$searchValue}%")
                  ->orWhereHas('role', function($r) use ($searchValue) {
                      $r->where('name', 'like', "%{$searchValue}%");
                  });
            });
        }

        $recordsTotal = User::count();
        $recordsFiltered = $query->count();

        // 2. Order Logic
        if ($request->has('order')) {
            $orderColumnIndex = $request->input('order.0.column');
            $orderDirection = $request->input('order.0.dir');
            $columns = $request->input('columns');
            $orderColumnName = $columns[$orderColumnIndex]['data'] ?? 'id';
            
            $sortableColumns = ['name', 'email'];
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
            $users = $query->get();
        } else {
            $users = $query->offset($offset)->limit($limit)->get();
        }

        // 4. Format Data
        $data = [];
        foreach ($users as $index => $user) {
            $data[] = [
                'DT_RowIndex' => $offset + $index + 1,
                'name' => $user->name,
                'email' => $user->email,
                'role_name' => $user->role->name ?? '-',
                'outlet_name' => $user->outlet->name ?? '-',
                'action' => '
                    <div class="d-flex gap-1 justify-content-center">
                        <a href="'.route('admin.user.edit', $user->id).'" class="btn btn-sm btn-primary py-1 px-2" style="border-radius:0.375rem;" title="Edit User"><i class="bi bi-pencil"></i></a>
                        <form action="'.route('admin.user.destroy', $user->id).'" method="POST" class="d-inline delete-form">
                            '.csrf_field().'
                            '.method_field('DELETE').'
                            <button type="submit" class="btn btn-sm btn-danger py-1 px-2" style="border-radius:0.375rem;" title="Hapus User"><i class="bi bi-trash"></i></button>
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
        $roles = Role::all();
        $outlets = Outlet::all();
        return view('page.admin.user.create', compact('roles', 'outlets'));
    }

    public function store(UserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('admin.user.index')->with('success', 'Akun User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        $outlets = Outlet::all();
        return view('page.admin.user.edit', compact('user', 'roles', 'outlets'));
    }

    public function update(UserRequest $request, User $user)
    {
        $data = $request->validated();
        
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.user.index')->with('success', 'Akun User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.user.index')->with('success', 'Akun User berhasil dihapus.');
    }
}
