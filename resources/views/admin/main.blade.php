<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Panel</title>
        <link rel="icon" type="image/x-icon" href="favicon.ico">
    </head>
    <body class="antialiased">
      <div id="app"></div>
        @vite('resources/js/app.js')
        <script src="https://dunggramer.github.io/disable-devtool/disable-devtool.min.js" defer></script>
    </body>
</html>
