@extends('layouts.app')
@section('content')
<h1>ログイン</h1>
<a href="/register">会員登録はこちら</a>
<form method="POST" action="/login">
@csrf
<input name="email" placeholder="メール" value="{{ old('email') }}">
<input type="password" name="password" placeholder="パスワード">
<button>ログイン</button>
</form>
@error('email')<p>{{ $message === 'These credentials do not match our records.' ? 'ログイン情報が登録されていません' : $message }}</p>@enderror
@endsection
