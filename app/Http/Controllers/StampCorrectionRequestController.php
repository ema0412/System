<?php

namespace App\Http\Controllers;

use App\Models\AttendanceBreak;
use App\Models\AttendanceCorrectionRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StampCorrectionRequestController extends Controller
{
    public function index(): View
    {
        $query = AttendanceCorrectionRequest::query()->with('attendance', 'user')->latest();

        if (! auth()->user()->is_admin) {
            $query->where('user_id', auth()->id());
        }

        $pending = (clone $query)->where('status', 'pending')->get();
        $approved = (clone $query)->where('status', 'approved')->get();

        return view('stamp_correction_request.list', compact('pending', 'approved'));
    }

    public function show(AttendanceCorrectionRequest $request): View
    {
        if (! auth()->user()->is_admin) {
            abort_unless($request->user_id === auth()->id(), 403);
        }

        return view('stamp_correction_request.detail', ['correctionRequest' => $request]);
    }

    public function approve(AttendanceCorrectionRequest $request): RedirectResponse
    {
        abort_unless(auth()->user()->is_admin, 403);
        abort_if($request->status !== 'pending', 422, '既に処理済みです。');

        $attendance = $request->attendance;
        $attendance->update([
            'clock_in_at' => $request->clock_in_at,
            'clock_out_at' => $request->clock_out_at,
            'note' => $request->note,
        ]);

        $attendance->breaks()->delete();
        foreach ($request->breaks_json ?? [] as $break) {
            if (empty($break['start']) || empty($break['end'])) {
                continue;
            }
            AttendanceBreak::query()->create([
                'attendance_id' => $attendance->id,
                'started_at' => $attendance->work_date->format('Y-m-d') . ' ' . $break['start'] . ':00',
                'ended_at' => $attendance->work_date->format('Y-m-d') . ' ' . $break['end'] . ':00',
            ]);
        }

        $request->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('stamp-correction.list');
    }
}
