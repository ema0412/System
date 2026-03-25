@extends('layouts.app')
@section('content')
<h1>申請詳細</h1>
<p>状態: {{ $correctionRequest->status === 'pending' ? '承認待ち' : '承認済み' }}</p>
@if(auth()->user()->is_admin && $correctionRequest->status === 'pending')
<form method="POST" action="{{ route('stamp-correction.approve', $correctionRequest) }}">@csrf<button>承認</button></form>
@elseif($correctionRequest->status === 'pending')
<p>承認待ちのため修正はできません。</p>
@endif
@endsection
