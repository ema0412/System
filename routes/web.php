<?php

use App\Http\Controllers\Admin\AdminAttendanceController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminStaffController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceListController;
use App\Http\Controllers\StampCorrectionRequestController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect('/attendance'));

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('/attendance/clock-in', [AttendanceController::class, 'clockIn'])->name('attendance.clock-in');
    Route::post('/attendance/break-start', [AttendanceController::class, 'breakStart'])->name('attendance.break-start');
    Route::post('/attendance/break-end', [AttendanceController::class, 'breakEnd'])->name('attendance.break-end');
    Route::post('/attendance/clock-out', [AttendanceController::class, 'clockOut'])->name('attendance.clock-out');

    Route::get('/attendance/list', [AttendanceListController::class, 'index'])->name('attendance.list');
    Route::get('/attendance/detail/{attendance}', [AttendanceListController::class, 'show'])->name('attendance.detail');
    Route::post('/attendance/detail/{attendance}', [AttendanceListController::class, 'requestCorrection'])->name('attendance.correction.request');

    Route::get('/stamp_correction_request/list', [StampCorrectionRequestController::class, 'index'])->name('stamp-correction.list');
    Route::get('/stamp_correction_request/approve/{request}', [StampCorrectionRequestController::class, 'show'])->name('stamp-correction.show');
    Route::post('/stamp_correction_request/approve/{request}', [StampCorrectionRequestController::class, 'approve'])
        ->name('stamp-correction.approve');
});

Route::prefix('admin')->group(function (): void {
    Route::get('/login', [AdminAuthController::class, 'create'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'store'])->name('admin.login.store');

    Route::middleware(['auth', 'admin'])->group(function (): void {
        Route::get('/attendance/list', [AdminAttendanceController::class, 'index'])->name('admin.attendance.list');
        Route::get('/attendance/{attendance}', [AdminAttendanceController::class, 'show'])->name('admin.attendance.show');
        Route::post('/attendance/{attendance}', [AdminAttendanceController::class, 'update'])->name('admin.attendance.update');

        Route::get('/staff/list', [AdminStaffController::class, 'index'])->name('admin.staff.list');
        Route::get('/attendance/staff/{user}', [AdminStaffController::class, 'attendance'])->name('admin.staff.attendance');
        Route::get('/attendance/staff/{user}/csv', [AdminStaffController::class, 'exportCsv'])->name('admin.staff.attendance.csv');
    });
});
