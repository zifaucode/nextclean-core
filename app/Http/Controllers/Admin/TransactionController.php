<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Customer;
use App\Models\Outlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function index()
    {
        return view('page.admin.transaction.index');
    }

    public function data(Request $request)
    {
        $query = Transaction::with(['customer', 'outlet'])->select('transactions.*');

        $user = auth()->user();
        if ($user->hasRole('kasir') || $user->hasRole('supervisor')) {
            $query->where('transactions.outlet_id', $user->outlet_id);
        }

        // 1. Search Logic
        if ($request->has('search') && $request->input('search.value') != '') {
            $searchValue = $request->input('search.value');
            $query->where(function($q) use ($searchValue) {
                $q->where('invoice_code', 'like', "%{$searchValue}%")
                  ->orWhere('status', 'like', "%{$searchValue}%")
                  ->orWhere('payment_status', 'like', "%{$searchValue}%")
                  ->orWhereHas('customer', function($c) use ($searchValue) {
                      $c->where('name', 'like', "%{$searchValue}%");
                  })
                  ->orWhereHas('outlet', function($o) use ($searchValue) {
                      $o->where('name', 'like', "%{$searchValue}%");
                  });
            });
        }

        $recordsTotal = Transaction::count();
        $recordsFiltered = $query->count();

        // 2. Order Logic
        if ($request->has('order')) {
            $orderColumnIndex = $request->input('order.0.column');
            $orderDirection = $request->input('order.0.dir');
            $columns = $request->input('columns');
            $orderColumnName = $columns[$orderColumnIndex]['data'] ?? 'id';
            
            $sortableColumns = ['invoice_code', 'transaction_date', 'grand_total', 'status', 'payment_status'];
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
            $transactions = $query->get();
        } else {
            $transactions = $query->offset($offset)->limit($limit)->get();
        }

        // 4. Format Data
        $data = [];
        foreach ($transactions as $index => $transaction) {
            $badgeStatus = match($transaction->status) {
                'Diterima' => 'secondary',
                'Dicuci' => 'info',
                'Disetrika' => 'warning',
                'Selesai' => 'success',
                'Diambil' => 'primary',
                default => 'light'
            };
            
            $badgePayment = $transaction->payment_status == 'Dibayar' ? 'success' : 'danger';

            $data[] = [
                'DT_RowIndex' => $offset + $index + 1,
                'invoice_code' => '<span class="fw-bold text-primary">'.$transaction->invoice_code.'</span>',
                'customer_name' => $transaction->customer->name ?? '-',
                'outlet_name' => $transaction->outlet->name ?? '-',
                'transaction_date' => date('d M Y H:i', strtotime($transaction->transaction_date)),
                'grand_total' => 'Rp ' . number_format($transaction->grand_total, 0, ',', '.'),
                'status' => '<span class="badge bg-'.$badgeStatus.'">'.$transaction->status.'</span>',
                'payment_status' => '<span class="badge bg-'.$badgePayment.'">'.$transaction->payment_status.'</span>',
                'action' => '
                    <div class="d-flex gap-1 justify-content-center">
                        <a href="'.route('admin.transaction.detail', $transaction->id).'" class="btn btn-sm btn-info text-white py-1 px-2" style="border-radius:0.375rem;" title="Detail Transaksi"><i class="bi bi-eye"></i></a>
                        <button class="btn btn-sm btn-primary py-1 px-2" style="border-radius:0.375rem;" title="Cetak Nota"><i class="bi bi-printer"></i></button>
                        <form action="'.route('admin.transaction.destroy', $transaction->id).'" method="POST" class="d-inline delete-form">
                            '.csrf_field().'
                            '.method_field('DELETE').'
                            <button type="submit" class="btn btn-sm btn-danger py-1 px-2" style="border-radius:0.375rem;" title="Hapus Transaksi"><i class="bi bi-trash"></i></button>
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
        $user = auth()->user();
        if ($user->hasRole('kasir') || $user->hasRole('supervisor')) {
            $customers = Customer::where('outlet_id', $user->outlet_id)->get();
            $outlets = Outlet::where('id', $user->outlet_id)->get();
        } else {
            $customers = Customer::all();
            $outlets = Outlet::all();
        }
        $products = \App\Models\Product::all();
        return view('page.admin.transaction.create', compact('customers', 'outlets', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'outlet_id' => 'required',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.1',
        ]);

        try {
            DB::beginTransaction();

            // Fetch products to get prices
            $productIds = collect($request->items)->pluck('product_id')->toArray();
            $products = \App\Models\Product::whereIn('id', $productIds)->get()->keyBy('id');

            $total_price = 0;
            foreach ($request->items as $item) {
                $product = $products[$item['product_id']];
                $total_price += $item['quantity'] * $product->price;
            }

            $outlet = Outlet::find($request->outlet_id);
            $additional_fee = $outlet->additional_fee;
            $grand_total = $total_price + $additional_fee;

            $transaction = Transaction::create([
                'invoice_code' => 'NC-' . strtoupper(Str::random(8)),
                'outlet_id' => $request->outlet_id,
                'customer_id' => $request->customer_id,
                'user_id' => auth()->id() ?? 1, // Default fallback
                'transaction_date' => now(),
                'total_price' => $total_price,
                'additional_fee' => $additional_fee,
                'grand_total' => $grand_total,
                'status' => 'Diterima',
                'payment_status' => $request->input('is_paid', '0') == '1' ? 'Dibayar' : 'Belum Bayar',
                'notes' => $request->input('notes'),
            ]);

            foreach ($request->items as $item) {
                $product = $products[$item['product_id']];
                $transaction->details()->create([
                    'item_name' => $product->name,
                    'type' => $product->type,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $item['quantity'] * $product->price,
                ]);
            }

            DB::commit();

            if ($request->input('source') === 'cashier') {
                return redirect()->route('cashier.index')->with('success', 'Transaksi berhasil disimpan!');
            }

            return redirect()->route('admin.transaction.index')->with('success', 'Transaksi berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function detail($id)
    {
        $transaction = Transaction::with(['customer', 'outlet', 'details', 'user'])->findOrFail($id);
        return view('page.admin.transaction.detail', compact('transaction'));
    }

    public function updateStatus(Request $request, Transaction $transaction)
    {
        $request->validate([
            'status' => 'required|in:Diterima,Dicuci,Disetrika,Selesai,Diambil'
        ]);

        $transaction->update(['status' => $request->status]);

        return back()->with('success', 'Status transaksi berhasil diperbarui.');
    }

    public function updatePayment(Request $request, Transaction $transaction)
    {
        $request->validate([
            'payment_status' => 'required|in:Belum Bayar,Dibayar'
        ]);

        $transaction->update(['payment_status' => $request->payment_status]);

        return back()->with('success', 'Status pembayaran berhasil diperbarui.');
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return redirect()->route('admin.transaction.index')->with('success', 'Transaksi berhasil dihapus.');
    }
    public function exportExcel()
    {
        $transactions = Transaction::with(['customer', 'outlet'])->orderBy('transaction_date', 'desc')->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'Invoice');
        $sheet->setCellValue('B1', 'Tanggal');
        $sheet->setCellValue('C1', 'Outlet');
        $sheet->setCellValue('D1', 'Pelanggan');
        $sheet->setCellValue('E1', 'Total Pembayaran (Rp)');
        $sheet->setCellValue('F1', 'Status Cucian');
        $sheet->setCellValue('G1', 'Status Pembayaran');

        // Data
        $row = 2;
        foreach ($transactions as $t) {
            $sheet->setCellValue('A' . $row, $t->invoice_code);
            $sheet->setCellValue('B' . $row, date('d/m/Y', strtotime($t->transaction_date)));
            $sheet->setCellValue('C' . $row, $t->outlet->name ?? '-');
            $sheet->setCellValue('D' . $row, $t->customer->name ?? '-');
            $sheet->setCellValue('E' . $row, $t->grand_total);
            $sheet->setCellValue('F' . $row, $t->status);
            $sheet->setCellValue('G' . $row, $t->payment_status);
            $row++;
        }

        // Auto size columns
        foreach (range('A', 'G') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $fileName = 'Data_Transaksi_NextClean_' . date('Ymd_His') . '.xlsx';

        // Direct output to browser
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    public function exportPdf()
    {
        $transactions = Transaction::with(['customer', 'outlet'])->orderBy('transaction_date', 'desc')->get();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('page.admin.transaction.pdf', compact('transactions'));
        return $pdf->download('Data_Transaksi_NextClean_' . date('Ymd_His') . '.pdf');
    }
}
