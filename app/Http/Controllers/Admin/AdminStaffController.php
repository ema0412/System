<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;

class AdminStaffController extends Controller
{
    public function index(): View
    {
        $staff = User::query()->where('is_admin', false)->orderBy('name')->get();

        return view('admin.staff.list', compact('staff'));
    }

    public function attendance(Request $request, User $user): View
    {
        $month = CarbonImmutable::parse($request->string('month', now()->format('Y-m')) . '-01');
        $attendances = Attendance::query()
            ->where('user_id', $user->id)
            ->whereBetween('work_date', [$month->startOfMonth()->toDateString(), $month->endOfMonth()->toDateString()])
            ->orderBy('work_date')
            ->get();

        return view('admin.staff.attendance', compact('user', 'attendances', 'month'));
    }

    public function exportCsv(Request $request, User $user)
    {
        $month = CarbonImmutable::parse($request->string('month', now()->format('Y-m')) . '-01');
        $rows = Attendance::query()
            ->where('user_id', $user->id)
            ->whereBetween('work_date', [$month->startOfMonth()->toDateString(), $month->endOfMonth()->toDateString()])
            ->orderBy('work_date')
            ->get(['work_date', 'clock_in_at', 'clock_out_at', 'note']);

        $csv = collect([['日付', '出勤', '退勤', '備考']])
            ->merge($rows->map(fn ($r) => [
                $r->work_date,
                optional($r->clock_in_at)->format('H:i'),
                optional($r->clock_out_at)->format('H:i'),
                $r->note,
            ]))
            ->map(fn ($line) => implode(',', array_map(fn ($v) => '"' . (string) $v . '"', $line)))
            ->implode("\n");

        return Response::make($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="attendance-' . $user->id . '-' . $month->format('Y-m') . '.csv"',
        ]);
    }
}
