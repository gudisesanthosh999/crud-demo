<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="icon" type="image/png" href="/images/log-in.png">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite('resources/js/app.js')
</head>
<body>

<div id="app">
    <login></login>
</div>


</body>
</html>
