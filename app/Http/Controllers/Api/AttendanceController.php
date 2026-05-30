<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Carbon;

class AttendanceController extends Controller
{
    public function clockIn(Request $request)
    {
        $user = $request->user();

        // Cek apakah user memiliki data employee dan outlet
        if (!$user->employee || !$user->outlet_id) {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda belum terikat dengan data Karyawan atau Outlet.',
            ], 400);
        }

        // Cek apakah hari ini sudah absen masuk
        $todayAttendance = Attendance::where('employee_id', $user->employee->id)
            ->whereDate('clock_in', Carbon::today())
            ->first();

        if ($todayAttendance) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan absen masuk hari ini.',
            ], 400);
        }

        $request->validate([
            'latitude' => 'required|string',
            'longitude' => 'required|string',
            'face_image' => 'required|image|max:2048', // Maksimal 2MB
        ]);

        $imagePath = $request->file('face_image')->store('attendances', 'public');

        $attendance = Attendance::create([
            'employee_id' => $user->employee->id,
            'outlet_id' => $user->outlet_id,
            'clock_in' => Carbon::now(),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'face_image_path' => $imagePath,
            'is_fake_gps' => false, // Simplifikasi untuk saat ini
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Absen masuk berhasil direkam.',
            'data' => $attendance
        ]);
    }

    public function clockOut(Request $request)
    {
        $user = $request->user();

        if (!$user->employee) {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda belum terikat dengan data Karyawan.',
            ], 400);
        }

        $attendance = Attendance::where('employee_id', $user->employee->id)
            ->whereDate('clock_in', Carbon::today())
            ->first();

        if (!$attendance) {
            return response()->json([
                'success' => false,
                'message' => 'Anda belum melakukan absen masuk hari ini.',
            ], 400);
        }

        if ($attendance->clock_out) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan absen keluar hari ini.',
            ], 400);
        }

        $attendance->update([
            'clock_out' => Carbon::now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Absen keluar berhasil direkam.',
            'data' => $attendance
        ]);
    }
}
