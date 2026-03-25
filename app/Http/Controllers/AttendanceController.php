<?php

namespace App\Http\Controllers;

use App\Services\AttendanceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function __construct(private readonly AttendanceService $attendanceService)
    {
    }

    public function index(): View
    {
        $attendance = $this->attendanceService->todayAttendance(auth()->user());

        return view('attendance.index', [
            'attendance' => $attendance,
            'now' => now(),
            'statusLabel' => $attendance->status->label(),
        ]);
    }

    public function clockIn(): RedirectResponse
    {
        $this->attendanceService->clockIn(auth()->user());

        return back();
    }

    public function breakStart(): RedirectResponse
    {
        $this->attendanceService->startBreak(auth()->user());

        return back();
    }

    public function breakEnd(): RedirectResponse
    {
        $this->attendanceService->endBreak(auth()->user());

        return back();
    }

    public function clockOut(): RedirectResponse
    {
        $this->attendanceService->clockOut(auth()->user());

        return back()->with('message', 'お疲れ様でした。');
    }
}
