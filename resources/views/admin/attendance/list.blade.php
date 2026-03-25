@extends('layouts.app')
@section('content')
<h1>管理者 勤怠一覧</h1>
<p>{{ $date->format('Y-m-d') }}</p>
@foreach($attendances as $a)
<div>{{ $a->user->name }} <a href="{{ route('admin.attendance.show', $a) }}">詳細</a></div>
@endforeach
@endsection
