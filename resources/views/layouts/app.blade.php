<!doctype html>
<html lang="ja">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>勤怠管理</title></head>
<body>
<header>
    <nav>
        <a href="{{ route('attendance.index') }}">打刻</a>
        <a href="{{ route('attendance.list') }}">勤怠一覧</a>
        <a href="{{ route('stamp-correction.list') }}">申請一覧</a>
        <form method="POST" action="/logout" style="display:inline">@csrf<button>ログアウト</button></form>
    </nav>
</header>
<main>@yield('content')</main>
</body>
</html>
