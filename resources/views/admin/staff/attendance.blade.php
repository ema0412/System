@extends('layouts.app')
@section('content')
<h1>{{ $user->name }}の月次勤怠</h1>
<a href="{{ route('admin.staff.attendance.csv', ['user' => $user, 'month' => $month->format('Y-m')]) }}">CSV出力</a>
@foreach($attendances as $a)
<div>{{ $a->work_date }} {{ optional($a->clock_in_at)->format('H:i') }}-{{ optional($a->clock_out_at)->format('H:i') }}</div>
@endforeach
@endsection
