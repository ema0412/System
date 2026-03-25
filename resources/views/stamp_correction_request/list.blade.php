@extends('layouts.app')
@section('content')
<h1>申請一覧</h1>
<h2>承認待ち</h2>
@foreach($pending as $row)
<div><a href="{{ route('stamp-correction.show', $row) }}">詳細</a> {{ $row->attendance->work_date }}</div>
@endforeach
<h2>承認済み</h2>
@foreach($approved as $row)
<div><a href="{{ route('stamp-correction.show', $row) }}">詳細</a> {{ $row->attendance->work_date }}</div>
@endforeach
@endsection
