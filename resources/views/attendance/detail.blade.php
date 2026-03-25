@extends('layouts.app')
@section('content')
<h1>勤怠詳細</h1>
<p>名前: {{ auth()->user()->name }}</p>
<p>日付: {{ $attendance->work_date->format('Y-m-d') }}</p>
<form method="POST" action="{{ route('attendance.correction.request', $attendance) }}">
@csrf
<label>出勤 <input name="clock_in_at" value="{{ optional($attendance->clock_in_at)->format('H:i') }}"></label>
<label>退勤 <input name="clock_out_at" value="{{ optional($attendance->clock_out_at)->format('H:i') }}"></label>
<label>備考 <textarea name="note"></textarea></label>
<button>修正</button>
</form>
@endsection
