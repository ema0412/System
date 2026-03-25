@extends('layouts.app')
@section('content')
<h1>管理者 勤怠詳細</h1>
<p>{{ $attendance->user->name }}</p>
<form method="POST">@csrf
<input name="clock_in_at" value="{{ optional($attendance->clock_in_at)->format('H:i') }}">
<input name="clock_out_at" value="{{ optional($attendance->clock_out_at)->format('H:i') }}">
<textarea name="note">{{ $attendance->note }}</textarea>
<button>修正</button>
</form>
@endsection
