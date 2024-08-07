<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>

        <meta charset="utf-8">
        
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        @vite('resources/css/app.css')

    </head>

    <body class="font-sans antialiased dark:bg-black dark:text-white/50">

        <div id="app">

            <p>@{{test_vue}}</p>

            <test-vue></test-vue>

        </div>

    </body>

    @vite('resources/js/app.js')

</html>
