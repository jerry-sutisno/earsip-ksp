<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'e-Arsip KSP')</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background: #f4f6f9; color: #222; }
        nav { background: #1f3864; color: #fff; padding: 12px 24px; display: flex; justify-content: space-between; align-items: center; }
        nav a { color: #fff; margin-right: 16px; text-decoration: none; }
        nav form { display: flex; align-items: center; gap: 12px; margin: 0; }
        main { max-width: 900px; margin: 24px auto; padding: 0 16px; }
        .card { background: #fff; border-radius: 8px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,.1); }
        .error { color: #b00020; }
        input { width: 100%; padding: 8px; margin: 4px 0 12px; box-sizing: border-box; }
        button { padding: 8px 16px; cursor: pointer; }
    </style>
</head>
<body>
@auth
    <nav>
        <div>
            <strong>e-Arsip KSP</strong> &nbsp;
            <a href="/dashboard">Dashboard</a>
            <a href="/arsip">Arsip</a>
            @if (auth()->user()->role === 'pimpinan')
                <a href="/approval">Approval</a>
            @endif
        </div>
        <form method="POST" action="/logout">
            @csrf
            <span>{{ auth()->user()->name }} ({{ auth()->user()->role }})</span>
            <button type="submit">Keluar</button>
        </form>
    </nav>
@endauth
<main>
    @yield('content')
</main>
</body>
</html>