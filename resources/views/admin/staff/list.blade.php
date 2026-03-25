@extends('layouts.app')
@section('content')
<h1>スタッフ一覧</h1>
@foreach($staff as $user)
<div>{{ $user->name }} / {{ $user->email }} <a href="{{ route('admin.staff.attendance', $user) }}">詳細</a></div>
@endforeach
@endsection
