<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttendanceCorrectionRequestForm;
use App\Models\Attendance;
use App\Models\AttendanceCorrectionRequest;
use App\Services\AttendanceService;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceListController extends Controller
{
    public function __construct(private readonly AttendanceService $attendanceService)
    {
    }

    public function index(Request $request): View
    {
        $month = CarbonImmutable::parse($request->string('month', now()->format('Y-m')) . '-01');
        $attendances = $this->attendanceService->monthAttendances(auth()->user(), $month);

        return view('attendance.list', compact('attendances', 'month'));
    }

    public function show(Attendance $attendance): View
    {
        abort_unless($attendance->user_id === auth()->id(), 403);
        $attendance->load('breaks');

        return view('attendance.detail', compact('attendance'));
    }

    public function requestCorrection(AttendanceCorrectionRequestForm $request, Attendance $attendance): RedirectResponse
    {
        abort_unless($attendance->user_id === auth()->id(), 403);

        AttendanceCorrectionRequest::query()->create([
            'attendance_id' => $attendance->id,
            'user_id' => auth()->id(),
            'clock_in_at' => $attendance->work_date->format('Y-m-d') . ' ' . $request->string('clock_in_at') . ':00',
            'clock_out_at' => $attendance->work_date->format('Y-m-d') . ' ' . $request->string('clock_out_at') . ':00',
            'breaks_json' => $request->input('breaks', []),
            'note' => $request->string('note'),
            'status' => 'pending',
        ]);

        return redirect()->route('stamp-correction.list');
    }
}
