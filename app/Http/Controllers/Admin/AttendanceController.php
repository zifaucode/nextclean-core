<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;


class AttendanceController extends Controller
{
    public function index()
    {
        return view('page.admin.attendance.index');
    }

    public function data(Request $request)
    {
        $query = Attendance::with(['employee.user', 'outlet'])->select('attendances.*');

        // 1. Search Logic
        if ($request->has('search') && $request->input('search.value') != '') {
            $searchValue = $request->input('search.value');
            $query->where(function ($q) use ($searchValue) {
                $q->whereHas('employee.user', function ($u) use ($searchValue) {
                    $u->where('name', 'like', "%{$searchValue}%");
                })
                    ->orWhereHas('outlet', function ($o) use ($searchValue) {
                        $o->where('name', 'like', "%{$searchValue}%");
                    });
            });
        }

        $recordsTotal = Attendance::count();
        $recordsFiltered = $query->count();

        // 2. Order Logic
        if ($request->has('order')) {
            $orderColumnIndex = $request->input('order.0.column');
            $orderDirection = $request->input('order.0.dir');
            $columns = $request->input('columns');
            $orderColumnName = $columns[$orderColumnIndex]['data'] ?? 'id';

            $sortableColumns = ['clock_in', 'clock_out'];
            if (in_array($orderColumnName, $sortableColumns)) {
                $query->orderBy($orderColumnName, $orderDirection);
            } else {
                $query->orderBy('clock_in', 'desc');
            }
        } else {
            $query->orderBy('clock_in', 'desc');
        }

        // 3. Pagination Logic
        $limit = $request->input('length', 10);
        $offset = $request->input('start', 0);
        if ($limit == -1) {
            $attendances = $query->get();
        } else {
            $attendances = $query->offset($offset)->limit($limit)->get();
        }

        // 4. Format Data
        $data = [];
        foreach ($attendances as $index => $attendance) {

            $clockInTime = Carbon::parse($attendance->clock_in);
            $outletOpenTime = $attendance->outlet ? Carbon::parse($attendance->outlet->open_time) : null;

            // Logika Status: Terlambat jika clock_in > open_time
            $statusHtml = '<span class="badge bg-success">Tepat Waktu</span>';
            if ($outletOpenTime) {
                // Set the date of open_time to match clock_in date for accurate time comparison
                $outletOpenTime->setDate($clockInTime->year, $clockInTime->month, $clockInTime->day);

                if ($clockInTime->greaterThan($outletOpenTime)) {
                    $statusHtml = '<span class="badge bg-danger">Terlambat</span>';
                }
            }

            // Lokasi (Google Maps)
            $locationHtml = '-';
            if ($attendance->latitude && $attendance->longitude) {
                $mapsUrl = "https://maps.google.com/?q={$attendance->latitude},{$attendance->longitude}";
                $locationHtml = '<a href="' . $mapsUrl . '" target="_blank" class="btn btn-sm btn-outline-info py-1 px-2" style="border-radius:0.375rem;"><i class="bi bi-geo-alt"></i> Peta</a>';
            }

            // Foto Thumbnail
            $photoHtml = '-';
            if ($attendance->face_image_path) {
                $imageUrl = asset('storage/' . $attendance->face_image_path);
                $photoHtml = '<a href="' . $imageUrl . '" target="_blank"><img src="' . $imageUrl . '" alt="Foto Absen" class="img-thumbnail" style="height: 40px; width: 40px; object-fit: cover; border-radius: 50%;"></a>';
            }

            $data[] = [
                'DT_RowIndex' => $offset + $index + 1,
                'employee_name' => $attendance->employee->user->name ?? '-',
                'date' => Carbon::parse($attendance->clock_in)->format('d M Y'),
                'clock_in' => Carbon::parse($attendance->clock_in)->format('H:i'),
                'clock_out' => $attendance->clock_out ? Carbon::parse($attendance->clock_out)->format('H:i') : '<span class="text-muted fst-italic">Belum Pulang</span>',
                'location' => $locationHtml,
                'photo' => $photoHtml,
                'status' => $statusHtml,
            ];
        }

        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }
}
