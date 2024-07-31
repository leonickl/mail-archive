<!doctype html>

<html>

<head>
    <title>Mail Archive - {{ $title ?? '---' }}</title>
    @vite('resources/css/app.css')
</head>

<body>
    {{ $slot }}
</body>

</html>
