@extends('layouts.app')
@section('content')
<h1>勤怠一覧</h1>
<p>{{ $month->format('Y年m月') }}</p>
<table>
<tr><th>日付</th><th>出勤</th><th>退勤</th><th>詳細</th></tr>
@foreach($attendances as $attendance)
<tr>
<td>{{ $attendance->work_date->format('m/d') }}</td>
<td>{{ optional($attendance->clock_in_at)->format('H:i') }}</td>
<td>{{ optional($attendance->clock_out_at)->format('H:i') }}</td>
<td><a href="{{ route('attendance.detail', $attendance) }}">詳細</a></td>
</tr>
@endforeach
</table>
@endsection
