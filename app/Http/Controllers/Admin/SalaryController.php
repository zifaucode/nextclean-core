<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SalaryController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', Carbon::now()->format('m'));
        $year = $request->input('year', Carbon::now()->format('Y'));

        $employees = Employee::with('outlet', 'user')->get();

        $salaries = [];
        foreach ($employees as $employee) {
            $attendanceCount = Attendance::where('employee_id', $employee->id)
                ->whereMonth('clock_in', $month)
                ->whereYear('clock_in', $year)
                ->count();
                
            $baseSalary = $employee->salary;
            
            // Logika sederhana: Gaji penuh atau proporsional?
            // Kita asumsikan gaji pokok adalah per bulan. Kehadiran hanya untuk informasi.
            // Atau bisa dihitung per hari (jika base salary = gaji per hari). 
            // Kita tampilkan saja gaji pokok dan jumlah kehadiran.
            
            $salaries[] = [
                'employee_code' => $employee->employee_code,
                'name' => $employee->user ? $employee->user->name : '-',
                'outlet' => $employee->outlet ? $employee->outlet->name : '-',
                'position' => $employee->position,
                'base_salary' => $baseSalary,
                'attendance_count' => $attendanceCount,
                'total_salary' => $baseSalary // Asumsi MVP: total gaji = gaji pokok
            ];
        }

        return view('page.admin.salary.index', compact('salaries', 'month', 'year'));
    }

    public function exportPdf(Request $request, $employee_code)
    {
        $month = $request->input('month', Carbon::now()->format('m'));
        $year = $request->input('year', Carbon::now()->format('Y'));

        $employee = Employee::with('outlet', 'user')->where('employee_code', $employee_code)->firstOrFail();

        $attendanceCount = Attendance::where('employee_id', $employee->id)
            ->whereMonth('clock_in', $month)
            ->whereYear('clock_in', $year)
            ->count();
            
        $baseSalary = $employee->salary;
        $totalSalary = $baseSalary; // Asumsi MVP: total gaji = gaji pokok
        
        $monthName = date('F', mktime(0, 0, 0, $month, 10));

        $data = [
            'employee' => $employee,
            'month' => $month,
            'year' => $year,
            'monthName' => $monthName,
            'attendanceCount' => $attendanceCount,
            'baseSalary' => $baseSalary,
            'totalSalary' => $totalSalary,
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('page.admin.salary.pdf', $data);
        
        // Atur ukuran kertas ke A5 landscape atau portrait, biasanya slip gaji bentuk memanjang horizontal
        $pdf->setPaper('A5', 'landscape');

        return $pdf->download('Slip_Gaji_' . $employee->employee_code . '_' . $monthName . '_' . $year . '.pdf');
    }
}
