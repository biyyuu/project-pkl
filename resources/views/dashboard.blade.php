<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>

    <h1>Dashboard</h1>

    @auth
        <h2>Selamat datang, {{ auth()->user()->name }}</h2>
        <p>Role: {{ auth()->user()->role }}</p>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit">Logout</button>
        </form>
    @endauth

</body>
</html>