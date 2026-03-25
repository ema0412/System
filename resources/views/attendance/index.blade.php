@extends('layouts.app')

@section('content')
<div>
    <h1>勤怠登録</h1>
    <p>{{ $now->format('Y年m月d日 H:i') }}</p>
    <p>ステータス: {{ $statusLabel }}</p>

    @if(session('message'))
        <p>{{ session('message') }}</p>
    @endif

    @if($attendance->status->value === 'off_duty')
        <form method="POST" action="{{ route('attendance.clock-in') }}">@csrf<button>出勤</button></form>
    @elseif($attendance->status->value === 'working')
        <form method="POST" action="{{ route('attendance.break-start') }}">@csrf<button>休憩入</button></form>
        <form method="POST" action="{{ route('attendance.clock-out') }}">@csrf<button>退勤</button></form>
    @elseif($attendance->status->value === 'on_break')
        <form method="POST" action="{{ route('attendance.break-end') }}">@csrf<button>休憩戻</button></form>
    @endif
</div>
@endsection
