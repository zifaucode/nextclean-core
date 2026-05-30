<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Outlet;
use Illuminate\Http\Request;
use App\Http\Requests\ExpenseRequest;

class ExpenseController extends Controller
{
    public function index()
    {
        return view('page.admin.expense.index');
    }

    public function data(Request $request)
    {
        $query = Expense::with('outlet')->select('expenses.*');

        // 1. Search Logic
        if ($request->has('search') && $request->input('search.value') != '') {
            $searchValue = $request->input('search.value');
            $query->where(function($q) use ($searchValue) {
                $q->where('name', 'like', "%{$searchValue}%")
                  ->orWhere('amount', 'like', "%{$searchValue}%")
                  ->orWhereHas('outlet', function($o) use ($searchValue) {
                      $o->where('name', 'like', "%{$searchValue}%");
                  });
            });
        }

        $recordsTotal = Expense::count();
        $recordsFiltered = $query->count();

        // 2. Order Logic
        if ($request->has('order')) {
            $orderColumnIndex = $request->input('order.0.column');
            $orderDirection = $request->input('order.0.dir');
            $columns = $request->input('columns');
            $orderColumnName = $columns[$orderColumnIndex]['data'] ?? 'id';
            
            $sortableColumns = ['date', 'name', 'amount'];
            if (in_array($orderColumnName, $sortableColumns)) {
                 $query->orderBy($orderColumnName, $orderDirection);
            } else {
                 $query->orderBy('date', 'desc');
            }
        } else {
            $query->orderBy('date', 'desc');
        }

        // 3. Pagination Logic
        $limit = $request->input('length', 10);
        $offset = $request->input('start', 0);
        if ($limit == -1) {
            $expenses = $query->get();
        } else {
            $expenses = $query->offset($offset)->limit($limit)->get();
        }

        // 4. Format Data
        $data = [];
        foreach ($expenses as $index => $expense) {
            $data[] = [
                'DT_RowIndex' => $offset + $index + 1,
                'date' => date('d M Y', strtotime($expense->date)),
                'outlet_name' => $expense->outlet->name ?? '-',
                'name' => $expense->name,
                'amount' => 'Rp ' . number_format($expense->amount, 0, ',', '.'),
                'action' => '
                    <div class="d-flex gap-1 justify-content-center">
                        <a href="'.route('admin.expense.edit', $expense->id).'" class="btn btn-sm btn-primary py-1 px-2" style="border-radius:0.375rem;" title="Edit"><i class="bi bi-pencil"></i></a>
                        <form action="'.route('admin.expense.destroy', $expense->id).'" method="POST" class="d-inline delete-form">
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
        $outlets = Outlet::all();
        return view('page.admin.expense.create', compact('outlets'));
    }

    public function store(ExpenseRequest $request)
    {
        Expense::create($request->validated());

        return redirect()->route('admin.expense.index')->with('success', 'Pengeluaran berhasil dicatat.');
    }

    public function edit(Expense $expense)
    {
        $outlets = Outlet::all();
        return view('page.admin.expense.edit', compact('expense', 'outlets'));
    }

    public function update(ExpenseRequest $request, Expense $expense)
    {
        $expense->update($request->validated());

        return redirect()->route('admin.expense.index')->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('admin.expense.index')->with('success', 'Pengeluaran berhasil dihapus.');
    }

    public function exportExcel()
    {
        $expenses = Expense::with('outlet')->orderBy('date', 'desc')->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'Tanggal');
        $sheet->setCellValue('B1', 'Outlet');
        $sheet->setCellValue('C1', 'Nama Pengeluaran');
        $sheet->setCellValue('D1', 'Jumlah (Rp)');
        $sheet->setCellValue('E1', 'Keterangan');

        // Data
        $row = 2;
        foreach ($expenses as $e) {
            $sheet->setCellValue('A' . $row, date('d/m/Y', strtotime($e->date)));
            $sheet->setCellValue('B' . $row, $e->outlet->name ?? '-');
            $sheet->setCellValue('C' . $row, $e->name);
            $sheet->setCellValue('D' . $row, $e->amount);
            $sheet->setCellValue('E' . $row, $e->description);
            $row++;
        }

        // Auto size columns
        foreach (range('A', 'E') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $fileName = 'Data_Pengeluaran_NextClean_' . date('Ymd_His') . '.xlsx';

        // Direct output to browser
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    public function exportPdf()
    {
        $expenses = Expense::with('outlet')->orderBy('date', 'desc')->get();
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('page.admin.expense.pdf', compact('expenses'));
        return $pdf->download('Data_Pengeluaran_NextClean_' . date('Ymd_His') . '.pdf');
    }
}
