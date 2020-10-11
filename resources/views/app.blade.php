<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Наукові публікації Сумського державного університету</title>

        <!-- Fonts -->

        <!-- Styles -->
        <link href="/css/app.css" rel="stylesheet">
        <link href="/css/site.css" rel="stylesheet">
    </head>
    <body>
    <div id="app">
        <header-component></header-component>
        <div class="wrapper">
            <transition name="component-fade" mode="out-in">
                <router-view></router-view>
            </transition>
        </div>

    </div>
    </body>
    <script src="/js/app.js"></script>








</html>
