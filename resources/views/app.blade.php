<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->



        <!-- Styles -->
        <link href="/css/app.css" rel="stylesheet">
        <link href="/css/all.min.css" rel="stylesheet">
        <link href="/css/site.css" rel="stylesheet">
    </head>
    <body>
    <div id="app">
        <header-component></header-component>
        <div class="wrapper">
            <router-view></router-view>
        </div>

    </div>
    </body>
    <script src="/js/app.js"></script>








</html>
