<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('page.admin.product.index');
    }

    public function data(Request $request)
    {
        $query = Product::select('products.*');

        // 1. Search Logic
        if ($request->has('search') && $request->input('search.value') != '') {
            $searchValue = $request->input('search.value');
            $query->where(function($q) use ($searchValue) {
                $q->where('name', 'like', "%{$searchValue}%")
                  ->orWhere('type', 'like', "%{$searchValue}%");
            });
        }

        $recordsTotal = Product::count();
        $recordsFiltered = $query->count();

        // 2. Order Logic
        if ($request->has('order')) {
            $orderColumnIndex = $request->input('order.0.column');
            $orderDirection = $request->input('order.0.dir');
            $columns = $request->input('columns');
            $orderColumnName = $columns[$orderColumnIndex]['data'] ?? 'id';
            
            $sortableColumns = ['name', 'type', 'price'];
            if (in_array($orderColumnName, $sortableColumns)) {
                 $query->orderBy($orderColumnName, $orderDirection);
            } else {
                 $query->orderBy('id', 'desc');
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        // 3. Pagination Logic
        $limit = $request->input('length', 10);
        $offset = $request->input('start', 0);
        if ($limit == -1) {
            $products = $query->get();
        } else {
            $products = $query->offset($offset)->limit($limit)->get();
        }

        // 4. Format Data
        $data = [];
        foreach ($products as $index => $product) {
            $data[] = [
                'DT_RowIndex' => $offset + $index + 1,
                'name' => $product->name,
                'type' => '<span class="badge bg-'.($product->type == 'Kiloan' ? 'info' : 'primary').'">'.$product->type.'</span>',
                'price' => 'Rp ' . number_format($product->price, 0, ',', '.'),
                'action' => '
                    <div class="d-flex gap-1 justify-content-center">
                        <a href="'.route('admin.product.edit', $product->id).'" class="btn btn-sm btn-primary py-1 px-2" style="border-radius:0.375rem;" title="Edit"><i class="bi bi-pencil"></i></a>
                        <form action="'.route('admin.product.destroy', $product->id).'" method="POST" class="d-inline delete-form">
                            '.csrf_field().'
                            '.method_field('DELETE').'
                            <button type="submit" class="btn btn-sm btn-danger py-1 px-2" style="border-radius:0.375rem;" title="Hapus"><i class="bi bi-trash"></i></button>
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
        return view('page.admin.product.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:Kiloan,Satuan',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        Product::create($validated);

        return redirect()->route('admin.product.index')->with('success', 'Layanan / Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        return view('page.admin.product.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:Kiloan,Satuan',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $product->update($validated);

        return redirect()->route('admin.product.index')->with('success', 'Layanan / Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.product.index')->with('success', 'Layanan / Produk berhasil dihapus.');
    }
}
