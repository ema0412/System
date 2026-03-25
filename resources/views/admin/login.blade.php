@extends('layouts.app')
@section('content')
<h1>管理者ログイン</h1>
<form method="POST" action="{{ route('admin.login.store') }}">@csrf
<input name="email"><input type="password" name="password"><button>ログイン</button>
</form>
@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
@endsection
