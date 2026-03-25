<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttendanceCorrectionRequestForm;
use App\Models\Attendance;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminAttendanceController extends Controller
{
    public function index(Request $request): View
    {
        $date = CarbonImmutable::parse($request->string('date', now()->toDateString()));
        $attendances = Attendance::query()->with('user', 'breaks')->whereDate('work_date', $date)->get();

        return view('admin.attendance.list', compact('attendances', 'date'));
    }

    public function show(Attendance $attendance): View
    {
        $attendance->load('user', 'breaks');

        return view('admin.attendance.detail', compact('attendance'));
    }

    public function update(AttendanceCorrectionRequestForm $request, Attendance $attendance): RedirectResponse
    {
        $attendance->update([
            'clock_in_at' => $attendance->work_date->format('Y-m-d') . ' ' . $request->string('clock_in_at') . ':00',
            'clock_out_at' => $attendance->work_date->format('Y-m-d') . ' ' . $request->string('clock_out_at') . ':00',
            'note' => $request->string('note'),
        ]);

        return back()->with('message', '勤怠を修正しました。');
    }
}
