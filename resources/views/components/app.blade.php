<!doctype html>

<html>

<head>
    <title>{{ $title ?? 'Mail Archive' }}</title>
    @vite('resources/css/app.css')
</head>

<body>
    {{ $slot }}
</body>

</html>
