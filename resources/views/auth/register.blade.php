@extends('layouts.app')
@section('content')
<h1>会員登録</h1>
<a href="/login">ログインはこちら</a>
<form method="POST" action="/register">
@csrf
<input name="name" placeholder="お名前" value="{{ old('name') }}">
<input name="email" placeholder="メール" value="{{ old('email') }}">
<input type="password" name="password" placeholder="パスワード">
<input type="password" name="password_confirmation" placeholder="確認用パスワード">
<button>登録</button>
</form>
@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
@endsection
