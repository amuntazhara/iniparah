<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login</title>
        <link rel="icon" type="image/x-icon" href="favicon.ico">
        <style>body{height:100vh}.antialiased{-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}canvas{display:block;position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);}</style>
    </head>
    <body class="antialiased">
        <div id="app"></div>
        @vite('resources/js/login.js')
        <script src="https://dunggramer.github.io/disable-devtool/disable-devtool.min.js" defer></script>
    </body>
</html>
