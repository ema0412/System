@extends('layouts.app')
@section('content')
<h1>メール認証誘導画面</h1>
<p>認証はこちらから</p>
<form method="POST" action="{{ route('verification.send') }}">@csrf<button>認証メール再送</button></form>
@endsection
