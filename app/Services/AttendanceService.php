<?php

namespace App\Services;

use App\Enums\AttendanceStatus;
use App\Models\Attendance;
use App\Models\AttendanceBreak;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Collection;

class AttendanceService
{
    public function todayAttendance(User $user): Attendance
    {
        return Attendance::query()->firstOrCreate(
            ['user_id' => $user->id, 'work_date' => now()->toDateString()],
            ['status' => AttendanceStatus::OffDuty]
        );
    }

    public function clockIn(User $user): Attendance
    {
        $attendance = $this->todayAttendance($user);
        if ($attendance->clock_in_at !== null) {
            return $attendance;
        }

        $attendance->update([
            'clock_in_at' => now(),
            'status' => AttendanceStatus::Working,
        ]);

        return $attendance->refresh();
    }

    public function startBreak(User $user): Attendance
    {
        $attendance = $this->todayAttendance($user);
        if ($attendance->status !== AttendanceStatus::Working) {
            return $attendance;
        }

        AttendanceBreak::query()->create([
            'attendance_id' => $attendance->id,
            'started_at' => now(),
        ]);

        $attendance->update(['status' => AttendanceStatus::OnBreak]);

        return $attendance->refresh();
    }

    public function endBreak(User $user): Attendance
    {
        $attendance = $this->todayAttendance($user);
        if ($attendance->status !== AttendanceStatus::OnBreak) {
            return $attendance;
        }

        $lastBreak = $attendance->breaks()->whereNull('ended_at')->latest('id')->first();
        if ($lastBreak !== null) {
            $lastBreak->update(['ended_at' => now()]);
        }

        $attendance->update(['status' => AttendanceStatus::Working]);

        return $attendance->refresh();
    }

    public function clockOut(User $user): Attendance
    {
        $attendance = $this->todayAttendance($user);
        if ($attendance->clock_in_at === null || $attendance->clock_out_at !== null) {
            return $attendance;
        }

        $attendance->update([
            'clock_out_at' => now(),
            'status' => AttendanceStatus::Finished,
        ]);

        return $attendance->refresh();
    }

    public function monthAttendances(User $user, CarbonImmutable $month): Collection
    {
        return Attendance::query()
            ->with('breaks')
            ->where('user_id', $user->id)
            ->whereBetween('work_date', [$month->startOfMonth()->toDateString(), $month->endOfMonth()->toDateString()])
            ->orderBy('work_date')
            ->get();
    }
}
